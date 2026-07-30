<?php
/**
 * Laravel Cache Clearing Script
 * این اسکریپت تمام کش های لاراول را پاک می کند
 */

// Set execution time limit to prevent timeout
set_time_limit(300);

// Start output buffering for better display
ob_start();

echo "<h2>Laravel Cache Clearing Script</h2>\n";
echo "<p>در حال پاک کردن کش های لاراول...</p>\n";

// Function to execute Laravel artisan commands
function runArtisanCommand($command) {
    $output = '';
    $return_var = 0;
    
    // Try different possible paths for artisan
    $artisan_paths = [
        __DIR__ . '/artisan',
        __DIR__ . '/../artisan',
        __DIR__ . '/../AFP/artisan',
        getcwd() . '/artisan',
        getcwd() . '/../AFP/artisan',
        '/artisan'
    ];
    
    $artisan_found = false;
    $artisan_path = '';
    
    foreach ($artisan_paths as $path) {
        if (file_exists($path)) {
            $artisan_path = $path;
            $artisan_found = true;
            break;
        }
    }
    
    if (!$artisan_found) {
        return ['success' => false, 'output' => 'فایل artisan پیدا نشد. مسیرهای بررسی شده: ' . implode(', ', $artisan_paths)];
    }
    
    // Execute the command with timeout (cross-platform)
    $timeout_cmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'timeout /t 30' : 'timeout 30';
    $full_command = $timeout_cmd . " php " . escapeshellarg($artisan_path) . " " . $command . " 2>&1";
    
    // Set a shorter execution time for individual commands
    $start_time = time();
    exec($full_command, $output_array, $return_var);
    $execution_time = time() - $start_time;
    
    $output = implode("\n", $output_array);
    
    // If it takes too long, consider it failed
    if ($execution_time > 25) {
        $return_var = 1;
        $output .= "\n(دستور به دلیل طولانی بودن متوقف شد)";
    }
    
    return [
        'success' => $return_var === 0,
        'output' => $output,
        'command' => $full_command
    ];
}

// Function to manually clear file-based caches
function clearFileCache($path, $description) {
    if (!is_dir($path)) {
        return "مسیر $description موجود نیست: $path";
    }
    
    $files = glob($path . '/*');
    $count = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    
    return "$description پاک شد: $count فایل حذف شد";
}

// Function to recursively clear directories
function clearDirectoryRecursive($dir, $preserve_dir = true) {
    if (!is_dir($dir)) {
        return 0;
    }
    
    $count = 0;
    $files = array_diff(scandir($dir), array('.', '..'));
    
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $count += clearDirectoryRecursive($path, false);
            @rmdir($path);
        } else {
            if (@unlink($path)) {
                $count++;
            }
        }
    }
    
    return $count;
}

// Array of artisan commands to clear different caches
$commands = [
    'cache:clear' => 'Application Cache',
    'route:clear' => 'Route Cache', 
    'config:clear' => 'Configuration Cache',
    'view:clear' => 'View Cache',
    'event:clear' => 'Event Cache',
    'queue:clear' => 'Queue Cache',
    'optimize:clear' => 'All Optimization Caches'
];

// First try manual cache clearing (faster and more reliable)
echo "<h3>پاک سازی دستی کش ها (روش سریع):</h3>\n";
echo "<ul>\n";

$quick_cache_paths = [
    '../AFP/bootstrap/cache/config.php' => 'Config Cache',
    '../AFP/bootstrap/cache/routes-v7.php' => 'Routes Cache V7',
    '../AFP/bootstrap/cache/routes.php' => 'Routes Cache',
    '../AFP/bootstrap/cache/packages.php' => 'Packages Cache',
    '../AFP/bootstrap/cache/services.php' => 'Services Cache',
    '../AFP/storage/framework/cache' => 'Framework Cache Directory',
    '../AFP/storage/framework/views' => 'Compiled Views',
    '../AFP/storage/framework/sessions' => 'Sessions Directory'
];

foreach ($quick_cache_paths as $path => $desc) {
    echo "<li><strong>$desc:</strong> ";
    
    if (is_file($path)) {
        if (@unlink($path)) {
            echo "<span style='color: green;'>✓ فایل پاک شد</span>";
        } else {
            echo "<span style='color: red;'>✗ خطا در پاک کردن</span>";
        }
    } elseif (is_dir($path)) {
        $count = clearDirectoryRecursive($path);
        echo "<span style='color: green;'>✓ $count فایل پاک شد</span>";
    } else {
        echo "<span style='color: orange;'>موجود نیست</span>";
    }
    
    echo "</li>\n";
    ob_flush();
    flush();
}

echo "</ul>\n";

echo "<h3>اجرای دستورات Artisan (اختیاری):</h3>\n";
echo "<ul>\n";

// Execute each artisan command with timeout protection
foreach ($commands as $command => $description) {
    echo "<li><strong>$description ($command):</strong> ";
    ob_flush();
    flush();
    
    $result = runArtisanCommand($command);
    
    if ($result['success']) {
        echo "<span style='color: green;'>✓ موفق</span>";
        if (!empty($result['output'])) {
            echo "<br><small>" . htmlspecialchars($result['output']) . "</small>";
        }
    } else {
        echo "<span style='color: red;'>✗ خطا</span>";
        echo "<br><small>" . htmlspecialchars($result['output']) . "</small>";
    }
    echo "</li>\n";
    
    // Flush output for real-time display
    ob_flush();
    flush();
}

echo "</ul>\n";

// Manual file cache clearing for additional security
echo "<h3>پاک کردن دستی فایل های کش:</h3>\n";
echo "<ul>\n";

// Common Laravel cache directories
$cache_directories = [
    'bootstrap/cache' => 'Bootstrap Cache',
    'storage/framework/cache' => 'Framework Cache',
    'storage/framework/sessions' => 'Sessions',
    'storage/framework/views' => 'Compiled Views',
    'storage/logs' => 'Log Files',
    '../AFP/bootstrap/cache' => 'AFP Bootstrap Cache',
    '../AFP/storage/framework/cache' => 'AFP Framework Cache',
    '../AFP/storage/framework/sessions' => 'AFP Sessions',
    '../AFP/storage/framework/views' => 'AFP Compiled Views',
    '../AFP/storage/logs' => 'AFP Log Files'
];

foreach ($cache_directories as $dir => $description) {
    $full_path = __DIR__ . '/' . $dir;
    // Also try parent directory
    if (!is_dir($full_path)) {
        $full_path = __DIR__ . '/../' . $dir;
    }
    
    echo "<li><strong>$description:</strong> ";
    
    if (is_dir($full_path)) {
        $result = clearFileCache($full_path, $description);
        echo "<span style='color: green;'>$result</span>";
    } else {
        echo "<span style='color: orange;'>مسیر موجود نیست: $dir</span>";
    }
    
    echo "</li>\n";
    ob_flush();
    flush();
}

echo "</ul>\n";

// Additional manual cache clearing with recursive function
echo "<h3>پاک سازی عمیق کش ها (روش دستی):</h3>\n";
echo "<ul>\n";

$manual_cache_paths = [
    '../AFP/storage/framework/cache/data' => 'Cache Data Files',
    '../AFP/storage/framework/sessions' => 'Session Files',
    '../AFP/storage/framework/views' => 'Compiled View Files',
    '../AFP/storage/logs' => 'Log Files (اختیاری)',
    '../AFP/bootstrap/cache' => 'Bootstrap Cache Files'
];

foreach ($manual_cache_paths as $path => $desc) {
    echo "<li><strong>$desc:</strong> ";
    if (is_dir($path)) {
        $cleared_count = clearDirectoryRecursive($path);
        echo "<span style='color: green;'>✓ $cleared_count فایل پاک شد</span>";
    } else {
        echo "<span style='color: orange;'>مسیر موجود نیست</span>";
    }
    echo "</li>\n";
    ob_flush();
    flush();
}

echo "</ul>\n";

// Clear opcache if available
echo "<h3>پاک کردن OPCache:</h3>\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "<span style='color: green;'>✓ OPCache با موفقیت پاک شد</span>\n";
    } else {
        echo "<span style='color: red;'>✗ خطا در پاک کردن OPCache</span>\n";
    }
} else {
    echo "<span style='color: orange;'>OPCache در دسترس نیست</span>\n";
}

// Additional cleanup commands
echo "<h3>عملیات های اضافی:</h3>\n";
echo "<ul>\n";

// Clear composer autoload
echo "<li><strong>بازسازی Composer Autoload:</strong> ";
$composer_result = runArtisanCommand('dump-autoload');
if (!$composer_result['success']) {
    // Try direct composer command
    exec('composer dump-autoload 2>&1', $composer_output, $composer_return);
    if ($composer_return === 0) {
        echo "<span style='color: green;'>✓ موفق</span>";
    } else {
        echo "<span style='color: orange;'>در دسترس نیست</span>";
    }
} else {
    echo "<span style='color: green;'>✓ موفق</span>";
}
echo "</li>\n";

// Try to restart queue workers (if any)
echo "<li><strong>راه اندازی مجدد Queue Workers:</strong> ";
$queue_result = runArtisanCommand('queue:restart');
if ($queue_result['success']) {
    echo "<span style='color: green;'>✓ موفق</span>";
} else {
    echo "<span style='color: orange;'>در دسترس نیست یا خطا</span>";
}
echo "</li>\n";

echo "</ul>\n";

// Manual Laravel cache clearing (alternative method)
echo "<h3>پاک سازی کش های خاص لاراول:</h3>\n";
echo "<ul>\n";

// Try to clear specific Laravel caches manually
$laravel_specific_caches = [
    '../AFP/storage/framework/cache' => 'تمام کش های فریم ورک',
    '../AFP/storage/app/public' => 'فایل های public storage',
    '../AFP/bootstrap/cache/packages.php' => 'Package Cache',
    '../AFP/bootstrap/cache/services.php' => 'Services Cache',
    '../AFP/bootstrap/cache/config.php' => 'Config Cache',
    '../AFP/bootstrap/cache/routes-v7.php' => 'Routes Cache'
];

foreach ($laravel_specific_caches as $path => $desc) {
    echo "<li><strong>$desc:</strong> ";
    
    if (is_file($path)) {
        if (@unlink($path)) {
            echo "<span style='color: green;'>✓ فایل پاک شد</span>";
        } else {
            echo "<span style='color: red;'>✗ خطا در پاک کردن</span>";
        }
    } elseif (is_dir($path)) {
        $count = clearDirectoryRecursive($path);
        echo "<span style='color: green;'>✓ $count فایل پاک شد</span>";
    } else {
        echo "<span style='color: orange;'>موجود نیست</span>";
    }
    
    echo "</li>\n";
    ob_flush();
    flush();
}

echo "</ul>\n";

echo "<h3 style='color: green;'>✓ تمام عملیات پاک سازی کش تکمیل شد!</h3>\n";
echo "<p><em>توصیه: صفحه را رفرش کنید و عملکرد سایت را بررسی کنید.</em></p>\n";

// End output buffering
ob_end_flush();
?>
