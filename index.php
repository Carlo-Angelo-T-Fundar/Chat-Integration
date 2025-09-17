<?php
/**
 * CodeIgniter Root Redirect
 * 
 * This file automatically redirects requests from the root directory
 * to the public folder where the actual CodeIgniter application resides.
 * 
 * This allows users to access the application via:
 * http://localhost:8080/Chat-Integration/
 * instead of having to manually navigate to:
 * http://localhost:8080/Chat-Integration/public/
 */

// Check if public directory exists
if (!is_dir(__DIR__ . '/public')) {
    die('Error: Public directory not found. Please ensure CodeIgniter is properly installed.');
}

// Check if CodeIgniter index.php exists in public folder
if (!file_exists(__DIR__ . '/public/index.php')) {
    die('Error: CodeIgniter application not found in public directory.');
}

// Redirect to public folder
header('Location: public/');
exit();