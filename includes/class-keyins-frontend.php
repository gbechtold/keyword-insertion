<?php

/**
 * Frontend functionality for Keyword Insertion
 *
 * @package Keyword_Insertion
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Keyins_Frontend
{

    // Plugin options
    private $options;

    public function __construct()
    {
        // Get plugin options
        $this->options = get_option('keyins_options', array(
            'enable_plugin' => 1,
            'param_name' => 'k',
            'max_length' => 100
        ));

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    // Enqueue scripts and styles
    public function enqueue_scripts()
    {
        // Only enqueue if enabled
        if (isset($this->options['enable_plugin']) && $this->options['enable_plugin']) {
            wp_enqueue_script(
                'keyins-frontend',
                KEYINS_PLUGIN_URL . 'assets/js/keyword-insertion.js',
                array('jquery'),
                KEYINS_VERSION,
                true
            );

            // Pass options to script
            wp_localize_script('keyins-frontend', 'keyinsOptions', array(
                'paramName' => sanitize_text_field($this->options['param_name']),
                'maxLength' => absint($this->options['max_length'])
            ));
        }
    }
}
