<?php
/**
 * Plugin Name: Dokko Chat
 * Plugin URI: https://yourwebsite.com/dokko-chat
 * Description: Adds a configurable chatbot to your WordPress website
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dokko-chat
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('DOKKO_CHAT_VERSION', '1.0.0');
define('DOKKO_CHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DOKKO_CHAT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once DOKKO_CHAT_PLUGIN_DIR . 'includes/class-dokko-chat.php';

// Initialize the plugin
function run_dokko_chat() {
    $plugin = new Dokko_Chat();
    $plugin->run();
}
run_dokko_chat(); 