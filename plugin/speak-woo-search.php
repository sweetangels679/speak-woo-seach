<?php
/*
Plugin Name: Speak Woo Search
Description: Floating voice + type search bar for WooCommerce with keyword logging and beautiful admin page.
Version: 1.0
Author: Sweet Angels
Author URI: https://sweetangels.in
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include front-end floating bar
add_action('wp_footer', 'sws_add_floating_search_bar');
function sws_add_floating_search_bar() {
    if (!is_admin()) {
        include plugin_dir_path(__FILE__) . 'sws-floating-bar.php';
    }
}

// Admin menu for viewing logs
add_action('admin_menu', 'sws_add_admin_menu');
function sws_add_admin_menu() {
    add_menu_page(
        'Speak Woo Search Logs',
        'Search Logs',
        'manage_options',
        'speak-woo-search-logs',
        'sws_display_logs_page',
        'dashicons-search',
        26
    );
}

function sws_display_logs_page() {
    $file_path = ABSPATH . 'search-log.txt';
    echo '<div class="wrap"><h1>Speak Woo Search - User Keywords Log</h1>';

    if (file_exists($file_path)) {
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines) {
            echo '<table class="widefat fixed" style="margin-top:20px;">';
            echo '<thead><tr><th width="20%">Timestamp</th><th width="50%">Query</th><th width="30%">Source</th></tr></thead><tbody>';

            foreach ($lines as $line) {
                list($timestamp, $query, $source) = explode(',', $line);
                echo '<tr>';
                echo '<td>' . esc_html($timestamp) . '</td>';
                echo '<td>' . esc_html($query) . '</td>';
                echo '<td>' . esc_html($source) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>No logs found yet.</p>';
        }
    } else {
        echo '<p>Log file not found. Please make sure "search-log.txt" exists in your root directory.</p>';
    }

    echo '</div>';
}
?>
