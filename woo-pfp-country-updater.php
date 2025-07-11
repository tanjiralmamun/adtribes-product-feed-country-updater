<?php
/**
 * Plugin Name: Adtribes Product Feed Country Updater
 * Description: Update Product Feed Pro/Elite's Feedz Country just by one click
 * Version: 1.0.0
 * Author: Tanjir Al Mamun
 * Author URI: https://www.tanjirsdev.com
 * License: GPLv2 or later
 * Text Domain: woo-pfp-country-updater
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class ADT_Country_Updater {
    
    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_notices', array($this, 'check_woocommerce'));
    }
    
    /**
     * Check if WooCommerce is active
     */
    public function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            echo '<div class="notice notice-warning"><p><strong>ADT Country Updater:</strong> WooCommerce is required for this plugin to work properly.</p></div>';
        }
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_management_page(
            'Feed Country Updater',
            'Feed Country Updater',
            'manage_options',
            'pfp-feed-country-updater',
            array($this, 'pfp_feed_country_updater')
        );
    }
    
    /**
     * Admin page callback
     */
    public function pfp_feed_country_updater() {
        // Handle form submission
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'adt_update_country')) {
            $this->handle_form_submission();
        }
        
        $this->render_admin_page();
    }
    
    /**
     * Handle form submission
     */
    private function handle_form_submission() {
        $feed_name = sanitize_text_field($_POST['feed_name'] ?? '');
        $country = sanitize_text_field($_POST['country'] ?? '');
        
        if (empty($feed_name) || empty($country)) {
            echo '<div class="notice notice-error"><p>Please select both feed name and country.</p></div>';
            return;
        }
        
        $updated_count = $this->update_feed_country($feed_name, $country);
        
        if ($updated_count > 0) {
            echo '<div class="notice notice-success"><p>Successfully updated country to <strong>' . esc_html($country) . '</strong> for feed: <strong>' . esc_html($feed_name) . '</strong></p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to update country. Feed not found.</p></div>';
        }
    }
    
    /**
     * Render admin page
     */
    private function render_admin_page() {
        $feeds = $this->get_feeds();
        $countries = $this->get_countries();
        ?>
        <div class="wrap">
            <h1>Adtribes Product Feed Country Updater</h1>
            <p>Update the <code>adt_country</code> meta field for product feeds with WooCommerce country codes.</p>
            
            <form method="post" action="">
                <?php wp_nonce_field('adt_update_country'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="feed_name">Feed Name</label>
                        </th>
                        <td>
                            <select name="feed_name" id="feed_name" required class="regular-text">
                                <option value="">Select Feed Name</option>
                                <?php foreach ($feeds as $feed): ?>
                                    <option value="<?php echo esc_attr($feed->post_title); ?>">
                                        <?php echo esc_html($feed->post_title . ' (' . ucfirst($feed->post_status) . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Available feeds from <code>adt_product_feed</code> post type (all statuses).</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="country">Country</label>
                        </th>
                        <td>
                            <select name="country" id="country" required class="regular-text">
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $code => $name): ?>
                                    <option value="<?php echo esc_attr($code); ?>">
                                        <?php echo esc_html($name . ' (' . $code . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">WooCommerce country codes from <code>WooCommerce</code> i18n files.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Update Country'); ?>
            </form>
            
            <div class="card">
                <h3>How it works</h3>
                <ol>
                    <li>Select a feed from the <strong>Feed Name</strong> dropdown</li>
                    <li>Choose a country from the <strong>Country</strong> dropdown</li>
                    <li>Click <strong>Update Country</strong> buttonto set the country for the selected feed</li>
                </ol>
                <p><strong>Note:</strong> This action updates the <code>adt_country</code> meta_key value directly to the selected feed's post meta.</p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get all product feeds
     */
    private function get_feeds() {
        global $wpdb;
        
        return $wpdb->get_results("
            SELECT post_title, ID, post_status 
            FROM {$wpdb->posts} 
            WHERE post_type = 'adt_product_feed' 
            AND post_status IN ('publish', 'draft', 'private')
            ORDER BY post_title ASC
        ");
    }
    
    /**
     * Get WooCommerce countries
     */
    private function get_countries() {
        if (!class_exists('WC_Countries')) {
            return array();
        }
        
        $wc_countries = new WC_Countries();
        return $wc_countries->get_countries();
    }
    
    /**
     * Update feed country
     */
    private function update_feed_country($feed_name, $country) {
        global $wpdb;
        
        $feed_id = $wpdb->get_var($wpdb->prepare("
            SELECT ID 
            FROM {$wpdb->posts} 
            WHERE post_type = 'adt_product_feed' 
            AND post_title = %s
            AND post_status IN ('publish', 'draft', 'private')
            LIMIT 1
        ", $feed_name));
        
        if (!$feed_id) {
            return 0;
        }
        
        $result = update_post_meta($feed_id, 'adt_country', $country);
        return ($result !== false) ? 1 : 0;
    }
}

// Initialize the plugin
new ADT_Country_Updater(); 