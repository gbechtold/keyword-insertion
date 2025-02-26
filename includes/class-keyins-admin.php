<?php

/**
 * Admin functionality for Keyword Insertion
 *
 * @package Keyword_Insertion
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Keyins_Admin
{

    public function __construct()
    {
        // Add menu item
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Add settings link on plugins page
        add_filter(
            'plugin_action_links_' . plugin_basename(KEYINS_PLUGIN_DIR . 'keyword-insertion.php'),
            array($this, 'add_settings_link')
        );

        // Add admin styles
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));

        // Add admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    // Admin styles
    public function admin_styles($hook)
    {
        if ('settings_page_keyword-insertion' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'keyins-admin-style',
            KEYINS_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            KEYINS_VERSION
        );
    }

    // Admin scripts
    public function admin_scripts($hook)
    {
        if ('settings_page_keyword-insertion' !== $hook) {
            return;
        }

        wp_enqueue_script(
            'keyins-admin-script',
            KEYINS_PLUGIN_URL . 'assets/js/admin-script.js',
            array('jquery'),
            KEYINS_VERSION,
            true
        );

        // Pass some variables to the script
        wp_localize_script('keyins-admin-script', 'keyinsAdmin', array(
            'siteUrl' => site_url('/')
        ));
    }

    // Add settings menu
    public function add_admin_menu()
    {
        add_options_page(
            __('Keyword Insertion', 'keyword-insertion'),
            __('Keyword Insertion', 'keyword-insertion'),
            'manage_options',
            'keyword-insertion',
            array($this, 'display_admin_page')
        );
    }

    // Register settings
    public function register_settings()
    {
        register_setting('keyins_settings', 'keyins_options');

        add_settings_section(
            'keyins_main_section',
            __('Main Settings', 'keyword-insertion'),
            array($this, 'main_section_callback'),
            'keyword-insertion'
        );

        add_settings_field(
            'keyins_enable_plugin',
            __('Enable Plugin', 'keyword-insertion'),
            array($this, 'enable_plugin_callback'),
            'keyword-insertion',
            'keyins_main_section'
        );

        add_settings_field(
            'keyins_param_name',
            __('URL Parameter Name', 'keyword-insertion'),
            array($this, 'param_name_callback'),
            'keyword-insertion',
            'keyins_main_section'
        );

        add_settings_field(
            'keyins_max_length',
            __('Maximum Keyword Length', 'keyword-insertion'),
            array($this, 'max_length_callback'),
            'keyword-insertion',
            'keyins_main_section'
        );
    }

    // Main section description
    public function main_section_callback()
    {
        echo '<p>' . __('Configure how the keyword insertion functionality works.', 'keyword-insertion') . '</p>';
    }

    // Enable plugin setting
    public function enable_plugin_callback()
    {
        $options = get_option('keyins_options', array(
            'enable_plugin' => 1,
            'param_name' => 'k',
            'max_length' => 100
        ));

        echo '<input type="checkbox" id="keyins_enable_plugin" name="keyins_options[enable_plugin]" value="1" ' .
            checked(1, isset($options['enable_plugin']) ? $options['enable_plugin'] : 1, false) . '/>';
        echo '<label for="keyins_enable_plugin">' . __('Enable keyword insertion functionality', 'keyword-insertion') . '</label>';
    }

    // Parameter name setting
    public function param_name_callback()
    {
        $options = get_option('keyins_options', array(
            'enable_plugin' => 1,
            'param_name' => 'k',
            'max_length' => 100
        ));

        echo '<input type="text" id="keyins_param_name" name="keyins_options[param_name]" value="' .
            esc_attr(isset($options['param_name']) ? $options['param_name'] : 'k') . '" />';
        echo '<p class="description">' . __('The URL parameter name to use for keyword insertion (default: k)', 'keyword-insertion') . '</p>';
    }

    // Max length setting
    public function max_length_callback()
    {
        $options = get_option('keyins_options', array(
            'enable_plugin' => 1,
            'param_name' => 'k',
            'max_length' => 100
        ));

        echo '<input type="number" id="keyins_max_length" name="keyins_options[max_length]" value="' .
            esc_attr(isset($options['max_length']) ? $options['max_length'] : 100) . '" min="1" max="500" />';
        echo '<p class="description">' . __('Maximum length of keywords (for security, default: 100)', 'keyword-insertion') . '</p>';
    }

    // Add settings link to plugins page
    public function add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=keyword-insertion">' . __('Settings', 'keyword-insertion') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    // Display admin page
    public function display_admin_page()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Get the options
        $options = get_option('keyins_options', array(
            'enable_plugin' => 1,
            'param_name' => 'k',
            'max_length' => 100
        ));

        // Display the form
?>
        <div class="wrap keyins-admin-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="keyins-admin-content">
                <div class="keyins-settings-container">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('keyins_settings');
                        do_settings_sections('keyword-insertion');
                        submit_button();
                        ?>
                    </form>

                    <div class="keyins-url-preview">
                        <h3><?php _e('URL Preview Tool', 'keyword-insertion'); ?></h3>
                        <p><?php _e('Test how your keywords will appear in URLs', 'keyword-insertion'); ?></p>

                        <div class="keyins-preview-form">
                            <label for="keyins-preview-keyword"><?php _e('Enter a sample keyword:', 'keyword-insertion'); ?></label>
                            <input type="text" id="keyins-preview-keyword" class="regular-text" value="Steuerberatung in Dornbirn" />

                            <div class="keyins-preview-result">
                                <h4><?php _e('Generated URL:', 'keyword-insertion'); ?></h4>
                                <div class="keyins-preview-url" id="keyins-preview-url">
                                    <code><?php echo esc_html(site_url('/sample-page/?k=Steuerberatung+in+Dornbirn')); ?></code>
                                </div>
                                <div class="keyins-copy-button">
                                    <button type="button" class="button" id="keyins-copy-url"><?php _e('Copy URL', 'keyword-insertion'); ?></button>
                                    <span class="keyins-copy-message" id="keyins-copy-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inline JavaScript für sofortige URL-Aktualisierung -->
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            // Escape special characters for URL
                            function encodeURLParameter(str) {
                                return encodeURIComponent(str).replace(/%20/g, '+');
                            }

                            // Escape HTML for display
                            function escapeHTML(str) {
                                return str
                                    .replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/"/g, '&quot;')
                                    .replace(/'/g, '&#039;');
                            }

                            // URL preview functionality
                            function updateURLPreview() {
                                var keyword = $('#keyins-preview-keyword').val();
                                var paramName = $('#keyins_param_name').val() || 'k';
                                var baseUrl = '<?php echo esc_js(site_url('/')); ?>';

                                // Create the full URL
                                var fullUrl = baseUrl + 'sample-page/?' + paramName + '=' + encodeURLParameter(keyword);

                                // Update the preview
                                $('#keyins-preview-url').html('<code>' + escapeHTML(fullUrl) + '</code>');
                            }

                            // Initialize URL preview
                            updateURLPreview();

                            // Update preview when keyword changes
                            $('#keyins-preview-keyword').on('input', function() {
                                updateURLPreview();
                            });

                            // Update preview when param name changes
                            $('#keyins_param_name').on('input', function() {
                                updateURLPreview();
                            });

                            // Copy URL to clipboard
                            $('#keyins-copy-url').on('click', function() {
                                var urlText = $('#keyins-preview-url code').text();

                                // Modern approach with Clipboard API if available
                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(urlText).then(function() {
                                            $('#keyins-copy-message').text('URL copied!').fadeIn().delay(2000).fadeOut();
                                        })
                                        .catch(function() {
                                            // Fallback for older browsers
                                            legacyCopyToClipboard(urlText);
                                        });
                                } else {
                                    // Fallback for older browsers
                                    legacyCopyToClipboard(urlText);
                                }
                            });

                            // Legacy copy method
                            function legacyCopyToClipboard(text) {
                                var tempInput = $('<input>');
                                $('body').append(tempInput);
                                tempInput.val(text).select();

                                try {
                                    var successful = document.execCommand('copy');
                                    if (successful) {
                                        $('#keyins-copy-message').text('URL copied!').fadeIn().delay(2000).fadeOut();
                                    } else {
                                        $('#keyins-copy-message').text('Copy failed. Please try manually.').fadeIn().delay(2000).fadeOut();
                                    }
                                } catch (err) {
                                    $('#keyins-copy-message').text('Copy failed. Please try manually.').fadeIn().delay(2000).fadeOut();
                                }

                                tempInput.remove();
                            }
                        });
                    </script>
                </div>

                <div class="keyins-documentation">
                    <div class="keyins-usage-section">
                        <h2><?php _e('Usage Instructions', 'keyword-insertion'); ?></h2>

                        <div class="keyins-usage-example">
                            <h3><?php _e('Basic Usage', 'keyword-insertion'); ?></h3>
                            <p><?php _e('Add the keyword-insert class to any HTML element where you want the keyword to appear:', 'keyword-insertion'); ?></p>
                            <pre><code>&lt;h2&gt;Welcome to our &lt;span class="keyword-insert"&gt;default text&lt;/span&gt; service&lt;/h2&gt;</code></pre>

                            <p><?php printf(__('Then add the URL parameter %s to your URL:', 'keyword-insertion'), '<code>' . esc_html($options['param_name']) . '</code>'); ?></p>
                            <pre><code><?php echo esc_html(site_url('/?' . $options['param_name'] . '=professional')); ?></code></pre>

                            <p><?php _e('This will replace "default text" with "professional" in your content.', 'keyword-insertion'); ?></p>
                        </div>

                        <div class="keyins-usage-example">
                            <h3><?php _e('Marketing Campaigns', 'keyword-insertion'); ?></h3>
                            <p><?php _e('You can use this for marketing campaigns to customize content for different channels:', 'keyword-insertion'); ?></p>
                            <pre><code># Google Campaign
<?php echo esc_html(site_url('/?' . $options['param_name'] . '=tax+consultation')); ?>

# Facebook Campaign
<?php echo esc_html(site_url('/?' . $options['param_name'] . '=financial+advice')); ?>

# Email Campaign
<?php echo esc_html(site_url('/?' . $options['param_name'] . '=accounting+services')); ?></code></pre>
                        </div>

                        <div class="keyins-usage-example">
                            <h3><?php _e('Example Implementation', 'keyword-insertion'); ?></h3>
                            <p><?php _e('Here is a complete example of how to use the plugin in your content:', 'keyword-insertion'); ?></p>
                            <pre><code>&lt;!-- In your WordPress page/post content or theme template --&gt;
&lt;div class="hero-section"&gt;
    &lt;h1&gt;Expert &lt;span class="keyword-insert"&gt;Services&lt;/span&gt; for Your Business&lt;/h1&gt;
    &lt;p&gt;Our &lt;span class="keyword-insert"&gt;Services&lt;/span&gt; are designed to help you succeed.&lt;/p&gt;
    &lt;a href="#contact" class="button"&gt;Get &lt;span class="keyword-insert"&gt;Services&lt;/span&gt; Now&lt;/a&gt;
&lt;/div&gt;</code></pre>
                        </div>
                    </div>

                    <div class="keyins-usage-section">
                        <h3><?php _e('Editor Compatibility', 'keyword-insertion'); ?></h3>
                        <p><?php _e('This plugin is fully compatible with:', 'keyword-insertion'); ?></p>
                        <ul>
                            <li><?php _e('Cornerstone Editor (X Theme / Pro Theme)', 'keyword-insertion'); ?></li>
                            <li><?php _e('Elementor', 'keyword-insertion'); ?></li>
                            <li><?php _e('Gutenberg', 'keyword-insertion'); ?></li>
                            <li><?php _e('Beaver Builder', 'keyword-insertion'); ?></li>
                            <li><?php _e('Other page builders', 'keyword-insertion'); ?></li>
                        </ul>
                    </div>

                    <div class="keyins-usage-section">
                        <h3><?php _e('Security Features', 'keyword-insertion'); ?></h3>
                        <ul>
                            <li><?php _e('All keywords are sanitized to prevent XSS attacks', 'keyword-insertion'); ?></li>
                            <li><?php printf(__('Maximum keyword length is enforced (currently set to %d characters)', 'keyword-insertion'), esc_html($options['max_length'])); ?></li>
                            <li><?php _e('Empty keywords are ignored', 'keyword-insertion'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
