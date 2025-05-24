<?php
/**
 * Plugin Name: Appetiser Image Optimization 
 * Plugin URI:  https://appetiser.com.au
 * Description: Optimizes newly uploaded images by reducing file size and automatically converting them to WebP format for improved performance and faster loading times!.!
 * Version: 1.0.0
 * Author: Landing page team
 * Author URI: https://appetiser.com.au
 * License: GPL v3
 *  License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit; 
}

// Define plugin constants .
define('APPETISER_IMAGE_OPTIMIZATION_PATH', plugin_dir_path(__FILE__));
define('APPETISER_IMAGE_OPTIMIZATION_URL', plugin_dir_url(__FILE__));

// Include plugin files
include_once APPETISER_IMAGE_OPTIMIZATION_PATH . 'admin/app-io-admin.php';
include_once APPETISER_IMAGE_OPTIMIZATION_PATH . 'inc/app-io-inc.php';
