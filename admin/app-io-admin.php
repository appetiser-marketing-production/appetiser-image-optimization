<?php

/**
 * Appetiser Image Optimization - Admin Settings Page
 *
 * Handles the admin settings for enabling/disabling image optimization.
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
 * Adds an admin submenu under "Tools" for the Image Optimization settings page.
 *
 * @since 1.0.0
 */
function appetiser_image_optimization_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Image Optimization',
        'Image Optimization',
        'manage_options',
        'appetiser-image-optimization',
        'appetiser_image_optimization_settings_page'
    );
}
add_action('admin_menu', 'appetiser_image_optimization_admin_menu');

/**
 * Enqueues the admin CSS for the settings page.
 *
 * @param string $hook The current admin page hook.
 * @since 1.0.0
 */
function appetiser_image_optimization_enqueue_admin_styles($hook) {
    if ($hook !== 'tools_page_appetiser-image-optimization') {
        return;
    }

    wp_enqueue_style(
        'appetiser-admin-style',
        plugin_dir_url(__FILE__) . 'css/app-io-admin-style.css',
        array(),
        '1.0'
    );
}
add_action('admin_enqueue_scripts', 'appetiser_image_optimization_enqueue_admin_styles');

/**
 * Registers the image optimization setting.
 *
 * @since 1.0.0
 */
function appetiser_image_optimization_register_settings() {
    register_setting('appetiser_image_optimization_settings', 'appetiser_enable_optimization');
}
add_action('admin_init', 'appetiser_image_optimization_register_settings');

/**
 * Renders the admin settings page.
 *
 * Displays an option to enable/disable image optimization via a toggle switch.
 *
 * @since 1.0.0
 */
function appetiser_image_optimization_settings_page() {
    $enabled = get_option('appetiser_enable_optimization', 1);
    ?>
    <div class="wrap">
        <h1>Image Optimization Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('appetiser_image_optimization_settings'); ?>
            <?php do_settings_sections('appetiser_image_optimization_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Enable Image Optimization on Upload</th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="appetiser_enable_optimization" value="1" <?php checked(1, $enabled); ?> />
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
