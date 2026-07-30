<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseVideo;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CourseImportController extends Controller
{
    /**
     * Show import form
     */
    public function index()
    {
        $user = auth()->user();
        
        return view('dashboard.courses.import', compact('user'));
    }

    /**
     * Process CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('csv_file');
            
            // Use fgetcsv for better handling of complex CSV with newlines in fields
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle === false) {
                throw new \Exception('Unable to open CSV file');
            }
            
            // Get header row
            $header = fgetcsv($handle);
            if ($header === false) {
                throw new \Exception('Unable to read CSV header');
            }
            
            $imported = 0;
            $errors = [];
            $rowIndex = 0;

            DB::beginTransaction();

            while (($row = fgetcsv($handle)) !== false) {
                $rowIndex++;
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    if (count($row) !== count($header)) {
                        $errors[] = "Row " . ($rowIndex + 1) . ": Column count mismatch (expected " . count($header) . ", got " . count($row) . ")";
                        continue;
                    }

                    $data = array_combine($header, $row);
                    
                    // Import course
                    $course = $this->importCourse($data);
                    
                    if ($course) {
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($rowIndex + 1) . ": " . $e->getMessage();
                    Log::error('Course import error', [
                        'row' => $rowIndex + 1,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            fclose($handle);

            DB::commit();

            $message = "$imported دوره با موفقیت وارد شد.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " خطا رخ داد.";
            }

            return redirect()->route('dashboard.admin.courses.import')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Course import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'خطا در وارد کردن فایل: ' . $e->getMessage());
        }
    }

    /**
     * Import single course from CSV row
     */
    private function importCourse($data)
    {
        // Skip if name is empty or None
        if (empty($data['name']) || $data['name'] === 'None') {
            return null;
        }

        // Parse info JSON
        $info = [];
        if (!empty($data['info']) && $data['info'] !== 'None') {
            $info = $this->parseInfo($data['info']);
        }

        // Create or update course
        $course = Course::updateOrCreate(
            ['name' => $data['name']],
            [
                'slug' => Str::slug($data['name']) . '-' . Str::random(6),
                'image_url' => $this->cleanValue($data['image'] ?? null),
                'duration' => $this->cleanValue($data['time'] ?? null),
                'source' => $this->cleanValue($data['source'] ?? null),
                'short_description' => $this->extractHtmlContent($data['short description'] ?? null),
                'description' => $this->extractHtmlContent($data['description'] ?? null),
                'what_you_learn' => $this->extractHtmlContent($data['what you will learn'] ?? null),
                'who_this_for' => $this->extractHtmlContent($data['who this course is for'] ?? null),
                'prerequisites' => $this->extractHtmlContent($data['course prerequisites'] ?? null),
                'info' => $info,
                'language' => $info['زبان'] ?? 'fa',
                'sessions_count' => intval($info['جلسات'] ?? 0),
                'published_at' => $this->parseDate($info['تاریخ انتشار'] ?? null),
                'is_active' => true,
            ]
        );

        // Process tags (categories)
        if (!empty($data['tags']) && $data['tags'] !== 'None') {
            $this->processTags($course, $data['tags']);
        }

        // Process videos
        if (!empty($data['video titles']) && $data['video titles'] !== 'None') {
            $this->processVideos($course, $data);
        }

        return $course;
    }

    /**
     * Parse info field (JSON-like string)
     */
    private function parseInfo($infoString)
    {
        try {
            // Remove outer quotes and parse as JSON
            $infoString = trim($infoString, "'\"");
            $info = json_decode($infoString, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($info)) {
                return $info;
            }

            // Try alternative parsing
            $info = [];
            preg_match_all("/'([^']+)'\\s*:\\s*'([^']+)'/", $infoString, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $info[$match[1]] = $match[2];
            }

            return $info;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Extract content from HTML string
     */
    private function extractHtmlContent($htmlString)
    {
        if (empty($htmlString) || $htmlString === 'None') {
            return null;
        }

        // Remove outer quotes
        $htmlString = trim($htmlString, "'\"");
        
        return $htmlString;
    }

    /**
     * Process tags and create categories
     */
    private function processTags($course, $tagsString)
    {
        // Parse tags from string like: "'صنعت 4.0'"
        $tags = $this->parseArrayString($tagsString);
        
        $categoryIds = [];
        
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (empty($tag)) {
                continue;
            }

            // Create or get category
            $category = CourseCategory::firstOrCreate(
                ['name' => $tag],
                [
                    'slug' => Str::slug($tag),
                    'is_active' => true,
                ]
            );

            $categoryIds[] = $category->id;
        }

        // Sync categories
        $course->categories()->sync($categoryIds);
    }

    /**
     * Process videos and create video records
     */
    private function processVideos($course, $data)
    {
        $titles = $this->parseArrayString($data['video titles'] ?? '');
        $links = $this->parseArrayString($data['video links'] ?? '');
        $subtitles = $this->parseArrayString($data['subtitles'] ?? '');

        // Delete existing videos
        $course->videos()->delete();

        $order = 0;
        foreach ($titles as $index => $title) {
            $link = $links[$index] ?? null;
            $subtitle = $subtitles[$index] ?? null;

            if (empty($link) || $link === 'None') {
                continue;
            }

            // Determine type based on URL
            $type = (strpos($link, 'video-player') !== false) ? 'video' : 'document';

            // Only add subtitle if it's a video and subtitle is not None/empty
            $subtitleUrl = null;
            if ($type === 'video' && !empty($subtitle) && $subtitle !== 'None') {
                $subtitleUrl = $subtitle;
            }

            // Clean title from zero-width characters (both actual Unicode and escaped strings)
            $cleanTitle = $title;
            // Remove actual Unicode zero-width characters
            $cleanTitle = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $cleanTitle);
            // Remove escaped Unicode strings like \u200c
            $cleanTitle = preg_replace('/\\\\u200[bcd]/i', '', $cleanTitle);
            $cleanTitle = preg_replace('/\\\\ufeff/i', '', $cleanTitle);
            
            CourseVideo::create([
                'course_id' => $course->id,
                'title' => $cleanTitle,
                'video_url' => $link,
                'subtitle_url' => $subtitleUrl,
                'type' => $type,
                'order' => $order++,
                'is_free' => false,
            ]);
        }

        // Update course sessions count
        $course->update(['sessions_count' => $order]);
    }

    /**
     * Parse array-like string from CSV
     */
    private function parseArrayString($string)
    {
        if (empty($string) || $string === 'None') {
            return [];
        }

        // Remove outer quotes
        $string = trim($string, "'\"");

        // Try to parse as JSON array
        if (strpos($string, '[') === 0) {
            try {
                $decoded = json_decode($string, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            } catch (\Exception $e) {
                // Continue to alternative parsing
            }
        }

        // Split by comma and clean up
        $items = explode(',', $string);
        $cleaned = [];
        
        foreach ($items as $item) {
            $item = trim($item, " '\"\t\n\r\0\x0B");
            if (!empty($item) && $item !== 'None') {
                $cleaned[] = $item;
            }
        }

        return $cleaned;
    }

    /**
     * Clean value (remove None, empty strings, unicode characters)
     */
    private function cleanValue($value)
    {
        if (empty($value) || $value === 'None') {
            return null;
        }

        // Remove zero-width characters (ZWNJ, ZWJ, etc.)
        $value = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value);
        
        return trim($value, "'\"");
    }

    /**
     * Parse date string
     */
    private function parseDate($dateString)
    {
        if (empty($dateString) || $dateString === 'None') {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($dateString)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Download sample CSV
     */
    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="course_import_sample.csv"',
        ];

        $sampleData = [
            ['name', 'image', 'time', 'tags', 'info', 'source', 'short description', 'what you will learn', 'who this course is for', 'course prerequisites', 'description', 'video titles', 'video links', 'subtitles'],
            ['نمونه دوره', 'https://example.com/image.jpg', '2:30:00', "'برنامه نویسی','وب'", "{'زبان': 'فارسی', 'جلسات': '10', 'مدت زمان': '2:30:00', 'تاریخ انتشار': '2024-01-01'}", 'Udemy', '<p>توضیحات کوتاه</p>', '<ul><li>یادگیری مفهوم A</li><li>یادگیری مفهوم B</li></ul>', '<ul><li>مبتدیان</li></ul>', '<ul><li>بدون پیش نیاز</li></ul>', '<p>توضیحات کامل دوره</p>', "'ویدیو 1','ویدیو 2'", "'https://example.com/video1','https://example.com/video2'", "'https://example.com/sub1',''"],
        ];

        $callback = function () use ($sampleData) {
            $file = fopen('php://output', 'w');
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
