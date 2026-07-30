<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DownloadProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:download-images {count=20}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download sample product images from Lorem Picsum';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("Downloading {$count} sample product images...");

        // Create directory for product images if it doesn't exist
        $directory = public_path('storage/products');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 1; $i <= $count; $i++) {
            $imagePath = $directory . '/' . $i . '.jpg';
            
            // Skip if image already exists
            if (File::exists($imagePath)) {
                $this->info("Image {$i}.jpg already exists, skipping.");
                $bar->advance();
                continue;
            }

            try {
                // Download random image from Lorem Picsum
                $imageUrl = "https://picsum.photos/800/600.jpg?random=" . mt_rand(1, 1000);
                $response = Http::get($imageUrl);
                
                if ($response->successful()) {
                    File::put($imagePath, $response->body());
                    $this->info("Downloaded image {$i}.jpg");
                } else {
                    $this->error("Failed to download image {$i}.jpg: " . $response->status());
                }
            } catch (\Exception $e) {
                $this->error("Error downloading image {$i}.jpg: " . $e->getMessage());
                
                // Create a colored placeholder image as fallback
                $image = imagecreatetruecolor(800, 600);
                $color = imagecolorallocate(
                    $image, 
                    rand(0, 255),  // Red
                    rand(0, 255),  // Green
                    rand(0, 255)   // Blue
                );
                imagefill($image, 0, 0, $color);
                imagejpeg($image, $imagePath);
                imagedestroy($image);
                
                $this->info("Created placeholder image {$i}.jpg instead");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nCompleted downloading product images.");
    }
} 