<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\AfghanWallet;
use App\Models\Transaction;
use App\Models\HesabPayPayment;

class HesabPayController extends Controller
{
    protected $apiKey;
    protected $sandboxMode;
    protected $apiBaseUrl;

	public function __construct()
	{
		$this->apiKey = env('HESABPAY_API_KEY');

		if (empty($this->apiKey)) {
			throw new \Exception('HesabPay API Key is not set.');
		}

		// فقط از production استفاده می‌کنیم
		$this->apiBaseUrl = 'https://api.hesab.com/api/v1';
	}


    /**
     * Process payment through HesabPay
     */
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone_number' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Create tracking code
        $trackingCode = 'HPAY' . time() . rand(1000, 9999);
        
        // Save payment request to database
        $hesabPayment = new HesabPayPayment();
        $hesabPayment->user_id = $user->id;
        $hesabPayment->amount = $validated['amount'];
        $hesabPayment->phone_number = $validated['phone_number'];
        $hesabPayment->description = $validated['description'] ?? null;
        $hesabPayment->tracking_code = $trackingCode;
        $hesabPayment->status = 'pending';
        $hesabPayment->save();
        
		// Create HesabPay session
		try {
			// Prepare request data
			$requestData = [
				'items' => [
					[
						'id' => $trackingCode,
						'name' => 'شارژ کیف پول افغانی',
						'price' => $validated['amount']
					]
				]
			];

			// Add email if available
			if (Auth::user()->email) {
				$requestData['email'] = Auth::user()->email;
			}

            // Log the request payload for debugging
            Log::info('HesabPay session creation request', [
                'url' => $this->apiBaseUrl . '/payment/create-session',
                'headers' => [
                    'Authorization' => 'API-KEY ' . $this->apiKey,
                ],
                'data' => $requestData
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'API-KEY ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->apiBaseUrl . '/payment/create-session', $requestData);
            
            // Log the response for debugging
            Log::info('HesabPay session creation response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json()
            ]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Update payment with session data
                $hesabPayment->payment_url = $responseData['url'] ?? null;
                
                // Extract session_id from URL if available
                if (isset($responseData['url'])) {
                    $urlParts = parse_url($responseData['url']);
                    if (isset($urlParts['path'])) {
                        $pathParts = explode('/', $urlParts['path']);
                        if (isset($pathParts[2])) {
                            $sessionId = $pathParts[2];
                            $hesabPayment->session_id = $sessionId;
                            Log::info('HesabPay - extracted session_id from URL path', [
                                'session_id' => $sessionId
                            ]);
                        }
                    }
                }
                
                $hesabPayment->save();
                
                // Create pending transaction
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $validated['amount'];
                $transaction->currency_type = 'AFN';
                $transaction->transaction_type = 'deposit';
                $transaction->status = 'pending';
                $transaction->description = 'شارژ کیف پول افغانی از طریق حساب پی';
                $transaction->reference_id = $trackingCode;
                $transaction->reference_type = 'hesabpay_payment';
                $transaction->save();
                
                // Redirect to payment URL
                return redirect()->away($responseData['url']);
            } else {
                Log::error('HesabPay session creation failed', [
                    'response' => $response->json(),
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $hesabPayment->status = 'failed';
                $hesabPayment->response_data = json_encode([
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $hesabPayment->save();
                
                return redirect()->route('dashboard.wallets.deposit.afghani')
                    ->with('error', 'خطا در ایجاد درگاه پرداخت. لطفا دوباره تلاش کنید. کد خطا: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('HesabPay API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $hesabPayment->status = 'failed';
            $hesabPayment->response_data = $e->getMessage();
            $hesabPayment->save();
            
            return redirect()->route('dashboard.wallets.deposit.afghani')
                ->with('error', 'خطا در ارتباط با درگاه پرداخت. لطفا دوباره تلاش کنید. ' . $e->getMessage());
        }
    }
    
    /**
     * Handle HesabPay webhook
     */
    public function webhook(Request $request)
    {
        // Log the raw webhook data
        Log::info('HesabPay Webhook received', [
            'raw_data' => $request->all()
        ]);

        // Get the webhook data
        $data = $request->all();
        
        // Check if data is available
        if (empty($data)) {
            Log::error('HesabPay webhook - no data received');
            return response()->json(['status' => 'error', 'message' => 'No data received'], 400);
        }
        
        // Check if the required fields are present
        if (!isset($data['signature']) || !isset($data['timestamp']) || !isset($data['transaction_id'])) {
            Log::error('HesabPay webhook - missing required fields', [
                'data' => $data
            ]);
            return response()->json(['status' => 'error', 'message' => 'Missing required fields'], 400);
        }
        
        // Verify the signature
        try {
            $verifyResponse = Http::withHeaders([
                'Authorization' => 'API-KEY ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiBaseUrl . '/hesab/webhooks/verify-signature', [
                'signature' => $data['signature'],
                'timestamp' => $data['timestamp'],
            ]);

            Log::info('HesabPay webhook - signature verification response', [
                'response' => $verifyResponse->json()
            ]);

            if (!$verifyResponse->successful() || !($verifyResponse->json()['success'] ?? false)) {
                Log::error('HesabPay webhook - invalid signature', [
                    'response' => $verifyResponse->json()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }
        } catch (\Exception $e) {
            Log::error('HesabPay webhook - signature verification failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Signature verification failed'], 400);
        }

        // Process payment data
        if (!($data['success'] ?? false)) {
            Log::error('HesabPay webhook - payment failed', [
                'data' => $data
            ]);
            return response()->json(['status' => 'error', 'message' => 'Payment failed'], 400);
        }

        // Extract transaction ID
        $transactionId = $data['transaction_id'];
        
        // Try to find payment by transaction_id
        $payment = HesabPayPayment::where('transaction_id', $transactionId)->first();
        
        // If not found by transaction_id, try to find by item ID (tracking_code)
        if (!$payment && isset($data['items']) && is_array($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if (isset($item['id'])) {
                    $payment = HesabPayPayment::where('tracking_code', $item['id'])->first();
                    if ($payment) {
                        break;
                    }
                }
            }
        }
        
        // If still not found, try to get most recent pending payment
        if (!$payment) {
            $payment = HesabPayPayment::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        if (!$payment) {
            Log::error('HesabPay webhook - payment record not found', [
                'transaction_id' => $transactionId,
                'items' => $data['items'] ?? []
            ]);
            return response()->json(['status' => 'error', 'message' => 'Payment record not found'], 404);
        }

        // Check if payment is already processed
        if ($payment->status === 'completed') {
            Log::info('HesabPay webhook - payment already processed', [
                'tracking_code' => $payment->tracking_code,
                'transaction_id' => $transactionId
            ]);
            return response()->json(['status' => 'success', 'message' => 'Payment already processed']);
        }

        // Update payment status
        $payment->status = 'completed';
        $payment->transaction_id = $transactionId;
        $payment->response_data = json_encode($data);
        $payment->completed_at = now();
        $payment->save();

        // Find the transaction
        $transaction = Transaction::where('reference_id', $payment->tracking_code)
            ->where('reference_type', 'hesabpay_payment')
            ->first();

        if ($transaction) {
            // Update transaction
            $transaction->status = 'completed';
            $transaction->save();

            // Add money to user's wallet
            $wallet = AfghanWallet::where('user_id', $payment->user_id)->first();
            if ($wallet) {
                try {
                    $wallet->deposit($payment->amount);
                    Log::info('HesabPay webhook - wallet charged successfully', [
                        'user_id' => $payment->user_id,
                        'amount' => $payment->amount,
                        'tracking_code' => $payment->tracking_code,
                        'transaction_id' => $transactionId
                    ]);
                } catch (\Exception $e) {
                    Log::error('HesabPay webhook - wallet charge failed', [
                        'user_id' => $payment->user_id,
                        'amount' => $payment->amount,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                Log::error('HesabPay webhook - wallet not found', [
                    'user_id' => $payment->user_id
                ]);
            }
        } else {
            Log::error('HesabPay webhook - transaction not found', [
                'tracking_code' => $payment->tracking_code,
                'transaction_id' => $transactionId
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
    }
    
    /**
     * Payment success callback page
     */
	public function success(Request $request)
	{
		// گرفتن اطلاعات از پارامترهای GET
		$data = $request->query(); // چون حساب‌پی فقط ریدایرکت می‌کنه

		// ثبت لاگ داده‌های اولیه با اطلاعات مرورگر
		Log::info('HesabPay success callback received', [
			'raw_data' => $data,
			'user_agent' => $request->userAgent(),
			'ip' => $request->ip(),
			'is_mobile' => $this->isMobile($request),
			'referer' => $request->header('referer'),
			'all_headers' => $request->headers->all()
		]);

		// اگر داده‌ها به صورت JSON داخل یک پارامتر باشه
		if (isset($data['data']) && is_string($data['data'])) {
			$decoded = json_decode($data['data'], true);
			if (is_array($decoded)) {
				$data = $decoded;
			}
		}

		Log::info('HesabPay success callback decoded data', [
			'decoded_data' => $data
		]);

		// بررسی صحت داده‌ها
		if (!$data || !isset($data['success']) || !$data['success']) {
			Log::error('HesabPay success - invalid or failed payment data', [
				'data' => $data,
				'user_agent' => $request->userAgent()
			]);
			
			// برای موبایل از meta refresh استفاده کنیم
			if ($this->isMobile($request)) {
				return $this->mobileRedirect('dashboard.wallets', 'error', 'پرداخت ناموفق بود. لطفا دوباره تلاش کنید.');
			}
			
			return redirect()->route('dashboard.wallets')
				->with('error', 'پرداخت ناموفق بود. لطفا دوباره تلاش کنید.');
		}

		// تلاش برای پیدا کردن پرداخت با transaction_id
		$payment = null;
		if (isset($data['transaction_id'])) {
			$payment = HesabPayPayment::where('transaction_id', $data['transaction_id'])->first();
		}

		// اگر پیدا نشد، جستجو در پرداخت‌های معلق اخیر
		if (!$payment) {
			$recentPayments = HesabPayPayment::where('status', 'pending')
				->orderBy('created_at', 'desc')
				->limit(5)
				->get();

			foreach ($recentPayments as $recentPayment) {
				if (isset($data['transaction_id'])) {
					$payment = $recentPayment;
					break;
				}
			}
		}

		if (!$payment) {
			Log::error('HesabPay success - payment not found', [
				'transaction_id' => $data['transaction_id'] ?? null,
				'user_agent' => $request->userAgent()
			]);
			
			// برای موبایل از meta refresh استفاده کنیم
			if ($this->isMobile($request)) {
				return $this->mobileRedirect('dashboard.wallets', 'error', 'اطلاعات پرداخت یافت نشد. لطفا با پشتیبانی تماس بگیرید.');
			}
			
			return redirect()->route('dashboard.wallets')
				->with('error', 'اطلاعات پرداخت یافت نشد. لطفا با پشتیبانی تماس بگیرید.');
		}

		// بروزرسانی وضعیت پرداخت
		if ($payment->status !== 'completed') {
			$payment->status = 'completed';
			$payment->transaction_id = $data['transaction_id'] ?? null;
			$payment->response_data = json_encode($data);
			$payment->completed_at = now();
			$payment->save();

			// پیدا کردن تراکنش مرتبط
			$transaction = Transaction::where('reference_id', $payment->tracking_code)
				->where('reference_type', 'hesabpay_payment')
				->first();

			if ($transaction) {
				$transaction->status = 'completed';
				$transaction->save();

				// شارژ کیف پول
				$wallet = AfghanWallet::where('user_id', $payment->user_id)->first();
				if ($wallet) {
					try {
						$wallet->deposit($payment->amount);
						Log::info('HesabPay success - wallet charged successfully', [
							'user_id' => $payment->user_id,
							'amount' => $payment->amount,
							'transaction_id' => $payment->transaction_id,
							'user_agent' => $request->userAgent()
						]);
					} catch (\Exception $e) {
						Log::error('HesabPay success - wallet charge failed', [
							'user_id' => $payment->user_id,
							'amount' => $payment->amount,
							'error' => $e->getMessage()
						]);
					}
				} else {
					Log::error('HesabPay success - wallet not found', [
						'user_id' => $payment->user_id
					]);
				}
			} else {
				Log::error('HesabPay success - transaction not found', [
					'tracking_code' => $payment->tracking_code
				]);
			}
		}

		// برای موبایل از meta refresh استفاده کنیم
		if ($this->isMobile($request)) {
			return $this->mobileRedirect('dashboard.wallets', 'success', 'پرداخت با موفقیت انجام شد. کیف پول شما شارژ شد.');
		}

		return redirect()->route('dashboard.wallets')
			->with('success', 'پرداخت با موفقیت انجام شد. کیف پول شما شارژ شد.');
	}

	/**
	 * Check if request is from mobile device
	 */
	private function isMobile(Request $request)
	{
		$userAgent = $request->userAgent();
		return preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
	}

	/**
	 * Handle mobile redirect with aggressive redirect methods
	 */
	private function mobileRedirect($route, $type, $message)
	{
		$url = route($route);
		
		// اضافه کردن پیام به session
		session()->flash($type, $message);
		
		// ایجاد یک صفحه HTML با روش‌های مختلف ریدایرکت
		$html = '<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1;url=' . $url . '">
    <title>در حال انتقال...</title>
    <style>
        body { 
            font-family: "Vazir", Arial, sans-serif; 
            text-align: center; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { 
            background: rgba(255,255,255,0.95); 
            color: #333;
            padding: 40px 20px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            max-width: 350px; 
            width: 100%;
            text-align: center;
        }
        .spinner { 
            border: 3px solid #f3f3f3; 
            border-top: 3px solid #667eea; 
            border-radius: 50%; 
            width: 50px; 
            height: 50px; 
            animation: spin 1s linear infinite; 
            margin: 20px auto; 
        }
        @keyframes spin { 
            0% { transform: rotate(0deg); } 
            100% { transform: rotate(360deg); } 
        }
        .message { 
            color: ' . ($type === 'success' ? '#28a745' : '#dc3545') . '; 
            margin: 20px 0; 
            font-size: 18px;
            font-weight: bold;
        }
        .redirect-text {
            font-size: 14px;
            margin: 15px 0;
            color: #666;
        }
        .redirect-link { 
            color: #667eea; 
            text-decoration: none; 
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #667eea;
            border-radius: 25px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }
        .redirect-link:hover {
            background: #667eea;
            color: white;
        }
        .countdown {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <div class="message">' . htmlspecialchars($message) . '</div>
        <div class="redirect-text">در حال انتقال به داشبورد...</div>
        <div class="countdown" id="countdown">انتقال خودکار در <span id="timer">3</span> ثانیه</div>
        <a href="' . $url . '" class="redirect-link" id="manualLink">انتقال دستی</a>
    </div>
    
    <script>
        // چندین روش برای اطمینان از ریدایرکت
        var targetUrl = "' . $url . '";
        var attempts = 0;
        var maxAttempts = 5;
        
        // تایمر شمارش معکوس
        var timeLeft = 3;
        var timerElement = document.getElementById("timer");
        var countdownTimer = setInterval(function() {
            timeLeft--;
            timerElement.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                document.getElementById("countdown").textContent = "در حال انتقال...";
            }
        }, 1000);
        
        // فوری: روش اول - جلوگیری از هرگونه تاخیر
        try {
            console.log("Immediate redirect attempt 1: window.location.href");
            window.location.href = targetUrl;
        } catch(e) {
            console.log("Immediate redirect failed:", e);
        }
        
        // روش 2: تلاش فوری دوم
        setTimeout(function() {
            attempts++;
            console.log("Redirect attempt " + attempts + ": location.replace");
            try {
                window.location.replace(targetUrl);
            } catch(e) {
                console.log("location.replace failed:", e);
            }
        }, 100);
        
        // روش 3: Meta refresh fallback
        setTimeout(function() {
            attempts++;
            console.log("Redirect attempt " + attempts + ": Meta refresh backup");
            try {
                window.location.href = targetUrl;
            } catch(e) {
                console.log("Meta refresh backup failed:", e);
            }
        }, 500);
        
        // روش 4: window.location.assign
        setTimeout(function() {
            if (attempts < maxAttempts) {
                attempts++;
                console.log("Redirect attempt " + attempts + ": location.assign");
                try {
                    window.location.assign(targetUrl);
                } catch(e) {
                    console.log("location.assign failed:", e);
                }
            }
        }, 1000);
        
        // روش 5: Direct assignment
        setTimeout(function() {
            if (attempts < maxAttempts) {
                attempts++;
                console.log("Redirect attempt " + attempts + ": Direct assignment");
                try {
                    window.location = targetUrl;
                } catch(e) {
                    console.log("Direct assignment failed:", e);
                }
            }
        }, 2000);
        
        // روش 6: document.location fallback
        setTimeout(function() {
            if (attempts < maxAttempts) {
                attempts++;
                console.log("Redirect attempt " + attempts + ": document.location");
                try {
                    document.location.href = targetUrl;
                } catch(e) {
                    console.log("document.location failed:", e);
                }
            }
        }, 3000);
        
        // روش 7: Form submission fallback
        setTimeout(function() {
            if (attempts < maxAttempts) {
                attempts++;
                console.log("Redirect attempt " + attempts + ": Form submission");
                try {
                    var form = document.createElement("form");
                    form.method = "GET";
                    form.action = targetUrl;
                    document.body.appendChild(form);
                    form.submit();
                } catch(e) {
                    console.log("Form submission failed:", e);
                }
            }
        }, 4000);
        
        // Event handlers برای کلیک دستی
        document.getElementById("manualLink").addEventListener("click", function(e) {
            e.preventDefault();
            console.log("Manual redirect clicked");
            window.location.href = targetUrl;
        });
        
        // کیبورد support
        document.addEventListener("keydown", function(e) {
            if (e.key === "Enter" || e.key === " ") {
                console.log("Keyboard redirect triggered");
                window.location.href = targetUrl;
            }
        });
        
        // Focus و visibility handlers
        window.addEventListener("focus", function() {
            console.log("Window focused, attempting redirect");
            window.location.href = targetUrl;
        });
        
        document.addEventListener("visibilitychange", function() {
            if (!document.hidden) {
                console.log("Page became visible, attempting redirect");
                window.location.href = targetUrl;
            }
        });
        
        // اضافه کردن پیام به localStorage برای صفحه بعدی
        try {
            localStorage.setItem("hesabpay_flash_' . $type . '", "' . addslashes($message) . '");
            localStorage.setItem("hesabpay_flash_timestamp", Date.now().toString());
        } catch(e) {
            console.log("localStorage not available");
        }
        
        // Back button handling
        window.addEventListener("popstate", function(e) {
            console.log("Back button pressed, redirecting");
            window.location.href = targetUrl;
        });
        
        console.log("Mobile redirect page loaded for: " + targetUrl);
        console.log("User agent:", navigator.userAgent);
        console.log("Platform:", navigator.platform);
        console.log("Page location:", window.location.href);
        
        // Test if location change is blocked
        var originalHref = window.location.href;
        setTimeout(function() {
            if (window.location.href === originalHref) {
                console.log("WARNING: Location did not change after 5 seconds");
                console.log("Current location:", window.location.href);
                console.log("Target location:", targetUrl);
                
                // Last resort: Show alert to user
                alert("ریدایرکت خودکار کار نکرد. لطفا روی دکمه زیر کلیک کنید.");
                document.getElementById("manualLink").style.backgroundColor = "#ff4444";
                document.getElementById("manualLink").style.color = "white";
                document.getElementById("manualLink").textContent = "کلیک کنید - ریدایرکت اجباری";
            }
        }, 5000);
    </script>
</body>
</html>';

		return response($html)
			->header('Content-Type', 'text/html; charset=utf-8')
			->header('Cache-Control', 'no-cache, no-store, must-revalidate')
			->header('Pragma', 'no-cache')
			->header('Expires', '0')
			->header('X-Frame-Options', 'SAMEORIGIN')
			->header('X-Content-Type-Options', 'nosniff');
	}


    /**
     * Payment failure callback page
     */
    public function fail(Request $request)
    {
        // Log the failure callback data
        Log::info('HesabPay failure callback received', [
            'data' => $request->all(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'is_mobile' => $this->isMobile($request)
        ]);

        // Get and decode the data parameter
        $data = json_decode($request->input('data'), true);

        $errorMessage = $data['message'] ?? 'پرداخت ناموفق بود. لطفا دوباره تلاش کنید.';

        // برای موبایل از meta refresh استفاده کنیم
        if ($this->isMobile($request)) {
            return $this->mobileRedirect('dashboard.wallets', 'error', $errorMessage);
        }

        return redirect()->route('dashboard.wallets')
            ->with('error', $errorMessage);
    }

    /**
     * Payment callback page (generic handler)
     */
    public function callback(Request $request)
    {
        // Log the callback data
        Log::info('HesabPay callback received', [
            'data' => $request->all()
        ]);
        
        // Get and decode the data parameter
        $data = $request->all();
        if (isset($data['data']) && is_string($data['data'])) {
            $decodedData = json_decode($data['data'], true);
            if ($decodedData) {
                $data = $decodedData;
            }
        }
        
        // Check if success is true/false and redirect accordingly
        if (isset($data['success'])) {
            if ($data['success']) {
                // Redirect to success route
                return redirect()->route('payment.success', ['data' => $request->input('data')]);
            } else {
                // Redirect to failure route
                return redirect()->route('payment.fail', ['data' => $request->input('data')]);
            }
        }
        
        // Default redirect to wallets page
        return redirect()->route('dashboard.wallets')
            ->with('info', 'پرداخت شما در حال پردازش است. پس از تایید، کیف پول شما شارژ خواهد شد.');
    }
} 