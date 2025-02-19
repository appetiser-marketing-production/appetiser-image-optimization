<?php
/**
 * Appetiser Image Optimization - Functions file
 *
 * Handles the image optimization logic, including WebP conversion
 * and updating metadata for new uploads.
 *
 * @package   AppetiserImageOptimization
 * @author    Landing page team
 * @license   GPL v3 https://www.gnu.org/licenses/gpl-3.0.html
 * @link      https://appetiser.com.au
 * @version   1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Optimizes the uploaded image and converts it to WebP format.
 *
 * - Applies compression settings based on image type.
 * - Saves a WebP version of the uploaded image.
 *
 * @param array $upload The upload file array.
 * @return array Modified upload file array.
 * @since 1.0.0
 */
add_filter('wp_handle_upload', '_process_uploaded_image_optimization');
function _process_uploaded_image_optimization($upload) {
    error_log("appetiser_enable_optimization value: " . var_export(get_option('appetiser_enable_optimization', 1), true));

    if (get_option('appetiser_enable_optimization', '1') !== '1') {
        return $upload;
    }

    // Supported image formats for conversion
    $convert_types = ['image/jpeg', 'image/png'];

    $file_path = $upload['file']; 
    $file_info = pathinfo($file_path);
    $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

    $image_editor = wp_get_image_editor($file_path);
    if (is_wp_error($image_editor)) {
        error_log('Image editor could not load: ' . print_r($image_editor->get_error_message(), true));
        return $upload;
    }

    // Apply optimization
    if ($upload['type'] === 'image/jpeg') {
        $image_editor->set_quality(98);
    } elseif ($upload['type'] === 'image/png' && method_exists($image_editor, 'set_quality')) {
        $image_editor->set_quality(100);
    }

    // Save optimized image
    $saved_image = $image_editor->save($file_path);
    if (is_wp_error($saved_image)) {
        error_log('Failed to save optimized image: ' . print_r($saved_image->get_error_message(), true));
    }

    // Convert to WebP
    if (in_array($upload['type'], $convert_types)) {
        $webp_image = $image_editor->save($webp_path, 'image/webp');
        if (!is_wp_error($webp_image) && file_exists($webp_image['path'])) {
            error_log('WebP successfully created: ' . $webp_image['path']);
            $upload['webp_file'] = $webp_image['path'];
        } else {
            error_log('WebP conversion failed: ' . print_r($webp_image->get_error_message(), true));
        }
    }

    return $upload;
}

/**
 * Generates a WebP version of the scaled image.
 *
 * - Detects the WordPress-generated scaled image.
 * - Converts it to WebP format for optimization.
 *
 * @param array $metadata The attachment metadata.
 * @param int   $attachment_id The attachment ID.
 * @return array Modified metadata.
 * @since 1.0.0
 */
add_filter('wp_generate_attachment_metadata', '_generate_webp_for_scaled_image', 10, 2);
function _generate_webp_for_scaled_image($metadata, $attachment_id) {
    if (get_option('appetiser_enable_optimization', '1') !== '1') {
        return $metadata;
    }

    $file_path = get_attached_file($attachment_id);
    $file_info = pathinfo($file_path);
    
    $scaled_path = $file_path; 
    $webp_path = $file_info['dirname'] . '/' . preg_replace('/-scaled$/', '', $file_info['filename']) . '-scaled.webp';

    if (file_exists($scaled_path)) {
        error_log("Correctly detected scaled image: " . $scaled_path);
        $image_editor = wp_get_image_editor($scaled_path);
        if (!is_wp_error($image_editor)) {
            $result = $image_editor->save($webp_path, 'image/webp');
            if (!is_wp_error($result) && file_exists($webp_path)) {
                error_log("WebP successfully created: " . $webp_path);
            }
        }
    }

    return $metadata;
}

/**
 * Replaces the scaled image in the media library with its WebP version.
 *
 * - Updates the attachment metadata to use WebP.
 *
 * @param array $metadata The attachment metadata.
 * @param int   $attachment_id The attachment ID.
 * @return array Modified metadata.
 * @since 1.0.0
 */
add_filter('wp_generate_attachment_metadata', '_replace_scaled_image_with_webp', 15, 2);
function _replace_scaled_image_with_webp($metadata, $attachment_id) {
    if (get_option('appetiser_enable_optimization', '1') !== '1') {
        return $metadata;
    }

    $file_path = get_attached_file($attachment_id);
    $upload_dir = wp_upload_dir();
    $file_info = pathinfo($file_path);

    $webp_filename = preg_replace('/-scaled$/', '', $file_info['filename']) . '-scaled.webp';
    $webp_path = $file_info['dirname'] . '/' . $webp_filename;

    if (file_exists($webp_path)) {
        error_log("Updating metadata to use WebP: " . $webp_path);
        $relative_webp_path = str_replace($upload_dir['basedir'] . '/', '', $webp_path);

        update_attached_file($attachment_id, $webp_path);
        $metadata['file'] = $relative_webp_path;
        update_post_meta($attachment_id, '_wp_attached_file', $relative_webp_path);

        $webp_url = $upload_dir['baseurl'] . '/' . $relative_webp_path;
        wp_update_post(['ID' => $attachment_id, 'guid' => $webp_url]);

        error_log("WebP file now set as attachment URL: " . $webp_url);
    }

    return $metadata;
}

/**
 * Converts all generated image sizes to WebP format.
 *
 * - Applies optimized quality settings for different sizes.
 * - Deletes the original JPG version after successful WebP conversion.
 *
 * @param array $metadata The attachment metadata.
 * @param int   $attachment_id The attachment ID.
 * @return array Modified metadata.
 * @since 1.0.0
 */
add_filter('wp_generate_attachment_metadata', '_convert_image_sizes_to_webp', 20, 2);
function _convert_image_sizes_to_webp($metadata, $attachment_id) {
    if (get_option('appetiser_enable_optimization', '1') !== '1') {
        return $metadata;
    }

    error_log("_convert_image_sizes_to_webp is running for attachment ID: " . $attachment_id);
    $upload_dir = wp_upload_dir();
    $file_path = get_attached_file($attachment_id);
    $file_info = pathinfo($file_path);

    if (!isset($metadata['sizes']) || empty($metadata['sizes'])) {
        return $metadata;
    }

    foreach ($metadata['sizes'] as $size => $size_data) {
        $jpg_path = $upload_dir['path'] . '/' . $size_data['file'];
        $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $jpg_path);

        if (file_exists($jpg_path)) {
            $image_editor = wp_get_image_editor($jpg_path);
            if (!is_wp_error($image_editor)) {
                if ($size === 'thumbnail') {
                    $image_editor->set_quality(75);
                } elseif ($size === 'medium') {
                    $image_editor->set_quality(85);
                } else {
                    $image_editor->set_quality(90);
                }

                $result = $image_editor->save($webp_path, 'image/webp');
                if (!is_wp_error($result) && file_exists($webp_path)) {
                    $metadata['sizes'][$size]['file'] = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $size_data['file']);
                    unlink($jpg_path);
                    error_log("Deleted original JPG file: " . $jpg_path);
                }
            }
        }
    }

    return $metadata;
}
