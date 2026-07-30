<?php

/**
 * Create a symbolic link from public_html/storage to Laravel's storage/app/public directory
 * This script helps access storage files in shared hosting environments
 */

// Set error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define paths for shared hosting environment
$publicPath = __DIR__;
// Try different possible paths where Laravel might be installed
$possiblePaths = [
    // Path directly in public_html directory
    __DIR__ . '/storage/app/public',
    // Path one level up (typical shared hosting setup)
    dirname(__DIR__) . '/storage/app/public',
    // Path with Laravel in a subdirectory
    dirname(__DIR__) . '/AFP/storage/app/public',
    // Try Laravel in same directory as public_html
    dirname(__DIR__) . '/public/storage/app/public',
    // Try with 'private' directory that some hosts use
    dirname(__DIR__) . '/private/storage/app/public',
];

// Storage link destination
$linkPath = $publicPath . '/storage';

// Find the correct storage path
$storagePath = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $storagePath = $path;
        break;
    }
}

// Function to recursively list directory contents
function listDirectoryContents($dir, $indent = '') {
    if (!is_dir($dir)) return;
    
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $fullPath = $dir . '/' . $file;
            echo "{$indent}- {$file}" . (is_dir($fullPath) ? ' (directory)' : ' (file)') . "\n";
            
            // Recursively list subdirectories (limit depth to 2 levels)
            if (is_dir($fullPath) && strlen($indent) < 4) {
                listDirectoryContents($fullPath, $indent . '  ');
            }
        }
    }
}

// Path autodiscovery - print directory structure to help troubleshoot
echo "<h3>Directory Structure:</h3>";
echo "<pre>";
// List the parent directory to find Laravel installation
$parentDir = dirname(__DIR__);
echo "Parent directory ({$parentDir}):\n";
if (is_dir($parentDir)) {
    $files = scandir($parentDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- {$file}" . (is_dir($parentDir . '/' . $file) ? ' (directory)' : ' (file)') . "\n";
        }
    }
} else {
    echo "Cannot access parent directory\n";
}

// Check current directory
echo "\nCurrent directory (" . __DIR__ . "):\n";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- {$file}" . (is_dir(__DIR__ . '/' . $file) ? ' (directory)' : ' (file)') . "\n";
    }
}

// Check storage path if it exists
if ($storagePath && is_dir($storagePath)) {
    echo "\nStorage directory ({$storagePath}):\n";
    $files = scandir($storagePath);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $fullPath = $storagePath . '/' . $file;
            echo "- {$file}" . (is_dir($fullPath) ? ' (directory)' : ' (file)') . "\n";
            
            // If this is the products directory, check its contents
            if ($file == 'products' && is_dir($fullPath)) {
                echo "  Products directory contents:\n";
                listDirectoryContents($fullPath, '  ');
            }
        }
    }
}
echo "</pre>";

// If storage path still not found
if (!$storagePath) {
    echo "<h3 style='color:red'>Cannot find Laravel storage directory!</h3>";
    echo "<p>Please enter the correct path below:</p>";
    echo "<form method='post'>";
    echo "<input type='text' name='storage_path' placeholder='/path/to/your/storage/app/public' style='width: 400px;'>";
    echo "<input type='submit' value='Create Storage Link'>";
    echo "</form>";
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['storage_path'])) {
        $storagePath = $_POST['storage_path'];
        if (file_exists($storagePath)) {
            echo "<p style='color:green'>Path found! Creating link...</p>";
        } else {
            echo "<p style='color:red'>Warning: The specified path does not exist, but will attempt to create link anyway.</p>";
        }
    } else {
        die();
    }
}

echo "<h3>Using storage path: {$storagePath}</h3>";

// Function to recursively copy directories
function recursiveCopy($source, $destination) {
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcFile = $source . '/' . $file;
            $destFile = $destination . '/' . $file;
            
            if (is_dir($srcFile)) {
                recursiveCopy($srcFile, $destFile);
            } else {
                copy($srcFile, $destFile);
            }
        }
    }
    closedir($dir);
    return true;
}

// Ensure storage directory exists in public
if (!file_exists($linkPath)) {
    try {
        // Try to create symlink
        if (symlink($storagePath, $linkPath)) {
            echo "<h3 style='color:green'>✓ Symbolic link created successfully!</h3>";
            echo "<p>Linked: {$linkPath} → {$storagePath}</p>";
        } else {
            echo "<h3 style='color:red'>✗ Failed to create symbolic link.</h3>";
            echo "<p>Error: " . error_get_last()['message'] . "</p>";
            
            // Alternative: Try with directory copying instead of symlink
            echo "<p>Trying alternative method (directory copy)...</p>";
            
            if (!is_dir($linkPath)) {
                mkdir($linkPath, 0755, true);
            }
            
            // Create .htaccess file for symlink-like behavior
            $htaccess = "RewriteEngine On\nRewriteBase /storage/\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^(.*)$ {$storagePath}/$1 [L]";
            file_put_contents($linkPath . '/.htaccess', $htaccess);
            
            // Copy the contents recursively
            recursiveCopy($storagePath, $linkPath);
            
            echo "<p style='color:blue'>Alternative method applied: Copied storage contents to public directory.</p>";
        }
    } catch (Exception $e) {
        echo "<h3 style='color:red'>✗ Exception occurred:</h3>";
        echo "<p>{$e->getMessage()}</p>";
    }
} else {
    echo "<h3 style='color:blue'>ℹ The storage directory already exists.</h3>";
    
    // Check if it's a symlink
    if (is_link($linkPath)) {
        $target = readlink($linkPath);
        echo "<p>Current link: {$linkPath} → {$target}</p>";
        
        // Check if the target is correct
        if ($target !== $storagePath) {
            echo "<p style='color:orange'>⚠ Warning: Link points to a different location than expected.</p>";
            echo "<p>Expected: {$storagePath}</p>";
            
            // Remove and recreate if needed
            echo "<p>Removing old link and creating new one...</p>";
            unlink($linkPath);
            
            if (symlink($storagePath, $linkPath)) {
                echo "<p style='color:green'>✓ Link updated successfully!</p>";
            } else {
                echo "<p style='color:red'>✗ Failed to update link.</p>";
            }
        }
    } else {
        echo "<p style='color:orange'>⚠ Warning: The storage path exists but is not a symbolic link.</p>";
        echo "<p>This might cause issues with accessing product images. Try removing the storage directory and running this script again.</p>";
        
        // Offer to fix by recreating the link
        echo "<form method='post'>";
        echo "<input type='hidden' name='force_recreate' value='1'>";
        echo "<input type='submit' value='Force Recreate Storage Link' style='background-color: #ff9900; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;'>";
        echo "</form>";
        
        // Handle force recreation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['force_recreate'])) {
            echo "<p>Removing existing storage directory...</p>";
            
            // Use a safer approach to remove directory
            $backupDir = $publicPath . '/storage_backup_' . date('YmdHis');
            rename($linkPath, $backupDir);
            
            echo "<p>Old directory backed up to: {$backupDir}</p>";
            
            // Create new symlink
            if (symlink($storagePath, $linkPath)) {
                echo "<p style='color:green'>✓ Storage link recreated successfully!</p>";
            } else {
                echo "<p style='color:red'>✗ Failed to recreate link. Error: " . error_get_last()['message'] . "</p>";
                
                // Try copy method as fallback
                mkdir($linkPath, 0755, true);
                recursiveCopy($storagePath, $linkPath);
                echo "<p>Tried to copy files as a fallback.</p>";
            }
        }
    }
}

// Ensure products directory exists and is accessible
$productsSourceDir = $storagePath . '/products';
$productsTargetDir = $linkPath . '/products';

if (!file_exists($productsSourceDir)) {
    echo "<h3 style='color:red'>Products directory does not exist in storage!</h3>";
    echo "<p>Creating products directory in storage...</p>";
    mkdir($productsSourceDir, 0755, true);
}

if (!file_exists($productsTargetDir) && !is_link($linkPath)) {
    echo "<p>Creating products directory in public storage...</p>";
    
    // If storage is not a symlink, we need to create the products directory
    mkdir($productsTargetDir, 0755, true);
    
    // Copy products recursively
    recursiveCopy($productsSourceDir, $productsTargetDir);
    echo "<p style='color:green'>✓ Products copied to public storage!</p>";
}

// Check for product subdirectories and ensure they're accessible
echo "<h3>Checking product subdirectories:</h3>";
if (is_dir($productsSourceDir)) {
    $productDirs = scandir($productsSourceDir);
    foreach ($productDirs as $dir) {
        if ($dir != '.' && $dir != '..' && is_dir($productsSourceDir . '/' . $dir)) {
            $subdirSource = $productsSourceDir . '/' . $dir;
            $subdirTarget = $productsTargetDir . '/' . $dir;
            
            echo "<p>Checking product directory: {$dir}</p>";
            
            // If it's a number (product ID directory), ensure it exists in target
            if (is_numeric($dir) && !file_exists($subdirTarget) && !is_link($linkPath)) {
                echo "<p>Creating directory for product ID {$dir}...</p>";
                mkdir($subdirTarget, 0755, true);
                
                // Copy all files from this product ID directory
                recursiveCopy($subdirSource, $subdirTarget);
                echo "<p style='color:green'>✓ Product ID {$dir} files copied successfully!</p>";
            }
            
            // Test if files in this product directory are accessible
            $testFiles = scandir($subdirSource);
            foreach ($testFiles as $file) {
                if ($file != '.' && $file != '..' && is_file($subdirSource . '/' . $file)) {
                    $sourceFile = $subdirSource . '/' . $file;
                    $targetFile = $subdirTarget . '/' . $file;
                    $webPath = "/storage/products/{$dir}/{$file}";
                    
                    echo "<p>Testing file: {$webPath} - ";
                    if (file_exists($targetFile) || is_link($linkPath)) {
                        echo "<span style='color:green'>Accessible</span></p>";
                        echo "<p><a href='{$webPath}' target='_blank'>Test Link for {$file}</a></p>";
                        break; // Just test one file per directory
                    } else {
                        echo "<span style='color:red'>Not accessible</span></p>";
                        
                        // If we're not using symlinks, try to copy the file
                        if (!is_link($linkPath)) {
                            if (!is_dir($subdirTarget)) {
                                mkdir($subdirTarget, 0755, true);
                            }
                            copy($sourceFile, $targetFile);
                            echo "<p style='color:blue'>Attempted to copy file directly</p>";
                        }
                    }
                }
            }
        }
    }
}

// Test the link by checking for a few directories
echo "<h3>Testing storage access:</h3>";
$testDirs = ['images', 'products', 'app'];

// First ensure directories exist in storage
foreach ($testDirs as $dir) {
    $fullStorageDir = $storagePath . '/' . $dir;
    if (!is_dir($fullStorageDir)) {
        mkdir($fullStorageDir, 0755, true);
        echo "<p style='color:blue'>Created directory in storage: {$dir}</p>";
    }
}

// Then test access
foreach ($testDirs as $dir) {
    $fullDir = $linkPath . '/' . $dir;
    if (file_exists($fullDir)) {
        echo "<p style='color:green'>✓ Directory {$dir} is accessible</p>";
    } else {
        echo "<p style='color:red'>✗ Directory {$dir} is not accessible</p>";
    }
}

echo "<p><a href='/storage/images' target='_blank'>Test Images Link</a></p>";
echo "<p><a href='/storage/products' target='_blank'>Test Products Link</a></p>";

// Add specific tests for product subdirectories
if (is_dir($productsSourceDir)) {
    $productDirs = scandir($productsSourceDir);
    foreach ($productDirs as $dir) {
        if (is_numeric($dir) && is_dir($productsSourceDir . '/' . $dir)) {
            echo "<p><a href='/storage/products/{$dir}' target='_blank'>Test Product ID {$dir} Link</a></p>";
            break; // Just test one product ID directory
        }
    }
}

// File permissions check
echo "<h3>File Permissions Check:</h3>";
$permissionPaths = [
    $linkPath,
    $productsTargetDir,
    $storagePath,
    $productsSourceDir
];

// Add a product ID directory if one exists
if (is_dir($productsSourceDir)) {
    $productDirs = scandir($productsSourceDir);
    foreach ($productDirs as $dir) {
        if (is_numeric($dir) && is_dir($productsSourceDir . '/' . $dir)) {
            $permissionPaths[] = $productsSourceDir . '/' . $dir;
            $permissionPaths[] = $productsTargetDir . '/' . $dir;
            break;
        }
    }
}

foreach ($permissionPaths as $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $owner = function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($path))['name'] : fileowner($path);
        $group = function_exists('posix_getgrgid') ? posix_getgrgid(filegroup($path))['name'] : filegroup($path);
        
        echo "<p>" . $path . " - Permissions: " . $perms . ", Owner: " . $owner . ", Group: " . $group . "</p>";
    } else {
        echo "<p style='color:red'>" . $path . " does not exist</p>";
    }
}

// Provide .htaccess file option for troubleshooting
echo "<h3>Additional Options:</h3>";
echo "<p>If you're still having issues with product images, you can try adding a special .htaccess file to your storage directory:</p>";

echo "<form method='post'>";
echo "<input type='hidden' name='create_htaccess' value='1'>";
echo "<input type='submit' value='Create .htaccess for Storage' style='background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;'>";
echo "</form>";

// Handle htaccess creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_htaccess'])) {
    $htaccessContent = "Options +FollowSymLinks\n";
    $htaccessContent .= "RewriteEngine On\n";
    $htaccessContent .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
    $htaccessContent .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
    $htaccessContent .= "RewriteRule ^(.*)$ index.php [L]\n";
    $htaccessContent .= "<IfModule mod_headers.c>\n";
    $htaccessContent .= "  Header set Access-Control-Allow-Origin *\n";
    $htaccessContent .= "</IfModule>\n";
    
    file_put_contents($linkPath . '/.htaccess', $htaccessContent);
    echo "<p style='color:green'>✓ .htaccess file created in storage directory!</p>";
}
