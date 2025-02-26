<?php

/**
 * Plugin Name: Keyword Insertion for WordPress
 * Plugin URI: https://github.com/gbechtold/keyword-insertion
 * Description: A lightweight plugin that allows keyword insertion via URL parameters with full Cornerstone Editor compatibility.
 * Version: 1.0.0
 * Author: Guntram Bechtold
 * Author URI: https://www.starsmedia.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: keyword-insertion
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('KEYINS_VERSION', '1.0.0');
define('KEYINS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KEYINS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Main plugin class
class Keyword_Insertion
{
    // Singleton instance
    private static $instance = null;

    // Get singleton instance
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    private function __construct()
    {
        // Load textdomain for translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Register activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activation'));
        register_deactivation_hook(__FILE__, array($this, 'deactivation'));

        // Include required files
        $this->includes();

        // Initialize the plugin
        $this->init();
    }

    // Load plugin textdomain
    public function load_textdomain()
    {
        load_plugin_textdomain('keyword-insertion', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    // Plugin activation
    public function activation()
    {
        // Set default options
        if (!get_option('keyins_options')) {
            update_option('keyins_options', array(
                'enable_plugin' => 1,
                'param_name' => 'k',
                'max_length' => 100
            ));
        }
    }

    // Plugin deactivation
    public function deactivation()
    {
        // We'll keep the options in case the user reactivates
    }

    // Include required files
    private function includes()
    {
        // Admin page
        require_once KEYINS_PLUGIN_DIR . 'includes/class-keyins-admin.php';

        // Frontend functionality
        require_once KEYINS_PLUGIN_DIR . 'includes/class-keyins-frontend.php';
    }

    // Initialize the plugin
    private function init()
    {
        // Initialize admin
        if (is_admin()) {
            new Keyins_Admin();
        }

        // Initialize frontend
        new Keyins_Frontend();
    }
}

// Initialize the plugin
function keyins_init()
{
    return Keyword_Insertion::get_instance();
}

// Start the plugin
keyins_init();
