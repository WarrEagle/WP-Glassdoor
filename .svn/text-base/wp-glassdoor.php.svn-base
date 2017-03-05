<?php
/*
Plugin Name: WP Glassdoor
Description: WP Glassdoor is a plugin that adds Glassdoor "Job Title Salary Widget" to WordPress widgets.
Plugin URI:  http://bradaric.com
Version:     1.0-dev1
Author:      Predrag Bradaric
Author URI:  http://bradaric.com
*/

if (!defined('__WPGD_NAME__'))
    define('__WPGD_NAME__', plugin_basename(__FILE__));
if (!defined('__WPGD_PATH__'))
    define('__WPGD_PATH__', dirname(__FILE__));
if (!defined('__WPGD_LIB_PATH__'))
    define('__WPGD_LIB_PATH__', __WPGD_PATH__ . '/lib');
if (!defined('__WPGD_PLUGIN_URL__'))
    define('__WPGD_PLUGIN_URL__', plugin_dir_url(__FILE__));

if (!class_exists('WPGlassdoor')) {
    /**
     * WPGlassdoor plugin "main" class.
     *
     * @package  WPGlassdoor
     * @since  1.0
     */
    class WPGlassdoor
    {
        /** Plugin name (used in WP backend). */
        const NAME                    = __WPGD_NAME__;
        /** Plugin product name. */
        const PRODUCT                 = 'wpgd';
        /** Plugin title (used in UI frontend). */
        const TITLE                   = 'WP Glassdoor';
        /** Plugin filesystem path. */
        const PATH                    = __WPGD_PATH__;
        /** Plugin libraries path. */
        const LIB_PATH                = __WPGD_LIB_PATH__;
        /** Plugin dir URL. */
        const PLUGIN_URL              = __WPGD_PLUGIN_URL__;
        /** Plugin version number (most of the plugin UI scripts use this as attribute) */
        const VERSION                 = '1.0-dev1';
        /** Database log table name (used for various messages/status logging). */
        const LOG_TABLE               = 'wpgd_log';
        /** Database cache table name (used for shortcodes caching). */
        const CACHE_TABLE             = 'wpgd_cache';
        /** Feeds global enable/disable. */
        const FEED_INDEED             = true;
        const FEED_SIMPLYHIRED        = true;
        const FEED_JUJU               = true;
        const FEED_TWITJOBSEARCH      = true;
        /** Debugging options. */
        const DEBUG                   = true;
        const DEBUG_OUTPUT            = 'file';
        const DEBUG_LOG_PATH          = '/tmp/wpgd-debug.log';
        /** Flash messages types constants (error, warning, success, etc.). */
        const MSG_ERROR               = 1;
        const MSG_WARNING             = 2;
        const MSG_SUCCESS             = 3;
        const MSG_INFO                = 4;

        /** @var array List of wp_options items used in the plugin. */
        static public $options = array(
            'wpgd_version' => false,
        );

        /** @var array Array of various plugin tooltips. */
        static public $tooltips = array(
            "tooltip_name" => "<p>Tooltip text.</p>",
        );

        /** @var array Array of various plugin messages. */
        static public $faqs = array(
        );

        static public $default_settings = array(
            'title'         => "Glassdoor Salary Widget",
            'tracking_id'   => false,
            'widget_height' => 400,
            'widget_width'  => 260,
            'widget_chart'  => false,
        );

        /**
         * Messages array. These messages will be displayed
         * at the top of the plugin page.
         * Example:
         *     Array(
         *         1 => Array(
         *             'type' => WPGlassdoor::MSG_ERROR,
         *             'text' => 'Some error occurred!',
         *             'display' => 'global'
         *         ),
         *         2 => Array(
         *             'type' => WPGlassdoor::MSG_SUCCESS,
         *             'text' => 'Some success message.',
         *             'display' => 'local'
         *         ),
         *     )
         * @var array
         */
        static public $flash_messages = array();


        /**
         * Register various actions and filters.
         * Init various globals.
         * @return void
         */
        static public function init()
        {
            /**
             * Log initialization.
             */
            // WPGlassdoor::$log = new MDLog(WPGlassdoor::PRODUCT, WPGlassdoor::LOG_TABLE);

            /**
             * Hooks and filters
             */
            register_activation_hook(
                __FILE__,
                array('WPGlassdoor', 'activate')
            );
            register_deactivation_hook(
                __FILE__,
                array('WPGlassdoor', 'deactivate')
            );

            add_action(
                'admin_menu',
                array('WPGlassdoor', 'add_admin_menu'),
                2
            );
            add_action(
                'admin_enqueue_scripts',
                array('WPGlassdoor', 'add_admin_scripts'),
                10, 1
            );
            add_action(
                'admin_notices',
                array('WPGlassdoor', 'show_notices')
            );

            add_filter(
                'plugin_row_meta',
                array('WPGlassdoor', 'set_plugin_meta'),
                10, 2
            );
            add_filter(
                'http_request_args',
                array('WPGlassdoor', 'no_updates'),
                5, 2
            );

            add_action(
                'wp_enqueue_scripts',
                array('WPGlassdoor', 'add_scripts'),
                10, 1
            );

            add_action(
                'admin_head',
                array('WPGlassdoor', 'add_to_admin_head'),
                10, 1
            );

            /**
             * Handle mortal AJAX requests
             */
            add_action(
                'wp',
                array('WPGlassdoor', 'ajax_handler'),
                1
            );

            /**
             * Add WP widget
             */
            add_action(
                'widgets_init',
                function() {
                    register_widget('WPGDWidget');
                }
            );

            if (isset($_REQUEST['action'])) {
                switch ($_REQUEST['action']) {
                    case '_reinit_':
                        WPGlassdoor::activate();
                        break;
                    default:
                        break;
                }
            }
            
        }


        /**
         * Run on WPGlassdoor plugin activation.
         * @return void
         */
        static public function activate()
        {
        }

        
        /**
         * Run on WPGlassdoor plugin deactivation.
         * @return void
         */
        static public function deactivate()
        {
        }


        /**
         * Add WPGlassdoor menu to admin sidebar menu.
         */
        static public function add_admin_menu()
        {
            add_options_page(WPGlassdoor::TITLE . ' Settings', WPGlassdoor::TITLE, 'manage_options', WPGlassdoor::PRODUCT, array('WPGlassdoor', 'page_settings'));
        }


        /**
         * Settings page.
         * @return void
         */
        static public function page_settings()
        {
            global $wpdb, $wp_version;
            $page = 'Settings';

            if ($_REQUEST['save_settings']) {
                if (isset($_REQUEST['settings']) and is_array($_REQUEST['settings'])) {
                    self::update_settings($_REQUEST['settings']);
                }
            }

            $settings = self::get_settings();

            include(WPGlassdoor::PATH . "/view/settings.php");
        }


        /**
         * Include additional (admin) WP scripts and styles.
         * @param  string $hook_suffix ???
         * @return void
         */
        static public function add_admin_scripts($hook_suffix)
        {
            // Only load when WPGlassdoor plugin page is displayed
            if (preg_match('@' . WPGlassdoor::PRODUCT . '@', $hook_suffix)) {
                wp_enqueue_script(array('hoverIntent', 'postbox', 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'editor', 'thickbox', 'media-upload'));
                wp_enqueue_script('wpgd-jquery-tools', plugins_url('/view/resources/jquery-tools/js/jquery.tools.min.js', __FILE__));
                wp_enqueue_script('wpgd-utils', plugins_url('/view/js/utils.js?v=' . WPGlassdoor::VERSION, __FILE__));
                wp_enqueue_script('wpgd', plugins_url('/view/js/_common.js?v=' . WPGlassdoor::VERSION, __FILE__));
                // wp_enqueue_script('wpgd-page', plugins_url('/view/js/' . WPGlassdoor::$page . '.js?v=' . WPGlassdoor::VERSION, __FILE__));
                
                // wp_enqueue_style('jquery_ui_loc', plugins_url('/css/jquery-ui-custom.css', __FILE__));
                wp_enqueue_style(array('thickbox'));
                wp_enqueue_style('wpgd-jquery-ui', plugins_url('/view/resources/jquery-ui/css/jquery-ui-custom.css', __FILE__));
                wp_enqueue_style('wpgd', plugins_url('/view/css/_common.css?v=' . WPGlassdoor::VERSION, __FILE__));
                // wp_enqueue_style('wpgd-page', plugins_url('/view/css/' . WPGlassdoor::$page . '.css?v=' . WPGlassdoor::VERSION, __FILE__));
                wp_enqueue_style('wpgd-bootstrap-icons', plugins_url('/view/resources/bootstrap/css/bootstrap-icons-only.css?v=' . WPGlassdoor::VERSION, __FILE__));
                wp_enqueue_style('wpgd-bootstrap-labels', plugins_url('/view/resources/bootstrap/css/bootstrap-labels-only.css?v=' . WPGlassdoor::VERSION, __FILE__));
                wp_enqueue_style('wpgd-bootstrap-nav', plugins_url('/view/resources/bootstrap/css/bootstrap-nav-only.css?v=' . WPGlassdoor::VERSION, __FILE__));

                wp_enqueue_style('google-fonts', 'http://fonts.googleapis.com/css?family=Libre+Baskerville');

                preg_match('@/([^/]+)@', $hook_suffix, $matches);
                if (is_array($matches) and !empty($matches)) {
                    wp_enqueue_style('wpgd-page', plugins_url('/view/css/' . $matches[1] . '.css?v=' . WPGlassdoor::VERSION, __FILE__));
                    wp_enqueue_script('wpgd-page', plugins_url('/view/js/' . $matches[1] . '.js?v=' . WPGlassdoor::VERSION, __FILE__));
                } else {
                    wp_enqueue_style('wpgd-page', plugins_url('/view/css/settings.css?v=' . WPGlassdoor::VERSION, __FILE__));
                    wp_enqueue_script('wpgd-page', plugins_url('/view/js/settings.js?v=' . WPGlassdoor::VERSION, __FILE__));
                }

                wp_localize_script(
                    'wpgd',
                    'WPGlassdoor',
                    array(
                        'ajaxurl'     => admin_url('admin-ajax.php'),
                        'product'     => WPGlassdoor::PRODUCT,
                        'MSG_ERROR'   => WPGlassdoor::MSG_ERROR,
                        'MSG_WARNING' => WPGlassdoor::MSG_WARNING,
                        'MSG_SUCCESS' => WPGlassdoor::MSG_SUCCESS,
                        'MSG_INFO'    => WPGlassdoor::MSG_INFO,
                    )
                );
            }

        }

        /**
         * Include additional WP scripts and styles.
         * @param  string $hook_suffix ???
         * @return void
         */
        static public function add_scripts($hook_suffix)
        {
            if (current_user_can('manage_options')) {
                // wp_enqueue_script(array('jquery', 'jquery-ui-core'));
                // wp_enqueue_script('jquery-tools', plugins_url('/js/jquery.tools.min.js', __FILE__));
                // wp_enqueue_script('wpgd-utils', plugins_url('/js/utils.js?v=' . WPGlassdoor::VERSION, __FILE__));
                wp_localize_script(
                    'wpgd-utils',
                    'WPGlassdoor',
                    array(
                        'ajaxurl' => admin_url('admin-ajax.php'),
                    )
                );
            }
        }


        static public function add_to_admin_head()
        {
            echo "<!--[if lt IE 9]>\n";
            echo "<script src=\"http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js\"></script>\n";
            echo "<![endif]-->\n";
        }


        /**
         * Add a plugin meta link
         * 
         * @param array $links ???
         * @param string $file ???
         * @return array ???
         */
        static public function set_plugin_meta($links, $file)
        {
            $plugin = plugin_basename(__FILE__);
            if ( $file == $plugin ) {
                return array_merge(
                    $links,
                    array(sprintf('<a href="admin.php?page=%s">%s</a>', WPGlassdoor::PRODUCT . '/settings', __('Settings')))
                );
            }
            return $links;
        }


        /*
         * Remove this plugin from the update list...
         */
        static public function no_updates($r, $url)
        {
            if (0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check')) {
                return $r; // Not a plugin update request. Bail immediately.
            }
            
            $plugins = unserialize($r['body']['plugins']);
            $file = WPGlassdoor::PATH . '/wpgd.php';
            unset($plugins->plugins[plugin_basename($file)]);
            unset($plugins->active[array_search(plugin_basename($file), $plugins->active)]);
            $r['body']['plugins'] = serialize($plugins);
            
            return $r;
        }


        static public function remove_wp_options() 
        {
            if(!empty(self::$plugin_options) && is_array(self::$plugin_options)) {
                foreach(self::$plugin_options as $option_name) {
                    delete_option($option_name);                    
                }
            }

            return true;
        }


        /**
         * Show any notices related to WPGlassdoor (WP header).
         * This function name should be added to 'admin_notices' action.
         * @return boolean False always.
         */
        static public function show_notices()
        {
            $has_dismiss = false;

            if (isset(WPGlassdoor::$flash_messages) and is_array(WPGlassdoor::$flash_messages)) {
                foreach(WPGlassdoor::$flash_messages as $idx=>$message) {
                    if ($message['display'] == 'global') {
                        switch ($message['type']) {
                            case WPGlassdoor::MSG_ERROR:
                                ?>
                                <div class="error wpgd-msg wpgd-msg-error">
                                    <?php if (isset($message['dismiss']) and $message['dismiss']): ?>
                                        <?php wp_enqueue_style('wpgd-jquery-ui', plugins_url('/view/resources/jquery-ui/css/jquery-ui-custom.css', __FILE__)); ?>
                                        <?php $has_dismiss = true; ?>
                                        <span class="ui-icon ui-icon-closethick close-message" style="float:right; margin-top: 8px; cursor: pointer;" title="Close message" <?php echo isset($message['slug'])? "data-msg_slug='" . $message['slug'] . "'" : ""; ?>></span>
                                    <?php endif; ?>
                                    <p><span class="ui-icon ui-icon-alert" style="float:left"></span>
                                    &nbsp;<strong>[<?php echo WPGlassdoor::TITLE; ?>] </strong>
                                    <?php echo $message['text']; ?></p>
                                </div>
                                <?php
                                break;
                            case WPGlassdoor::MSG_WARNING:
                                ?>
                                <div class="updated wpgd-msg wpgd-msg-warning">
                                    <?php if (isset($message['dismiss']) and $message['dismiss']): ?>
                                        <?php wp_enqueue_style('wpgd-jquery-ui', plugins_url('/view/resources/jquery-ui/css/jquery-ui-custom.css', __FILE__)); ?>
                                        <?php $has_dismiss = true; ?>
                                        <span class="ui-icon ui-icon-closethick close-message" style="float:right; margin-top: 8px; cursor: pointer;" title="Close message" <?php echo isset($message['slug'])? "data-msg_slug='" . $message['slug'] . "'" : ""; ?>></span>
                                    <?php endif; ?>
                                    <p><span class="ui-icon ui-icon-alert" style="float:left"></span>
                                    &nbsp;<strong>[<?php echo WPGlassdoor::TITLE; ?>] </strong>
                                    <?php echo $message['text']; ?></p>
                                </div>
                                <?php
                                break;
                            case WPGlassdoor::MSG_SUCCESS:
                                ?>
                                <div class="updated wpgd-msg wpgd-msg-success">
                                    <?php if (isset($message['dismiss']) and $message['dismiss']): ?>
                                        <?php wp_enqueue_style('wpgd-jquery-ui', plugins_url('/view/resources/jquery-ui/css/jquery-ui-custom.css', __FILE__)); ?>
                                        <?php $has_dismiss = true; ?>
                                        <span class="ui-icon ui-icon-closethick close-message" style="float:right; margin-top: 8px; cursor: pointer;" title="Close message"></span>
                                    <?php endif; ?>
                                    <p><span class="ui-icon ui-icon-circle-check" style="float:left"></span>
                                    &nbsp;<strong>[<?php echo WPGlassdoor::TITLE; ?>] </strong>
                                    <?php echo $message['text']; ?></p>
                                </div>
                                <?php
                                break;
                            case WPGlassdoor::MSG_INFO:
                            default:
                                ?>
                                <div class="updated wpgd-msg wpgd-msg-info">
                                    <?php if (isset($message['dismiss']) and $message['dismiss']): ?>
                                        <?php wp_enqueue_style('wpgd-jquery-ui', plugins_url('/view/resources/jquery-ui/css/jquery-ui-custom.css', __FILE__)); ?>
                                        <?php $has_dismiss = true; ?>
                                        <span class="ui-icon ui-icon-closethick close-message" style="float:right; margin-top: 8px; cursor: pointer;" title="Close message"></span>
                                    <?php endif; ?>
                                    <p><span class="ui-icon ui-icon-info" style="float:left"></span>
                                    &nbsp;<strong>[<?php echo WPGlassdoor::TITLE; ?>] </strong>
                                    <?php echo $message['text']; ?></p>
                                </div>
                                <?php
                                break;
                        }
                    }
                }

                if ($has_dismiss) {
                    ?>
                    <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery(".wpgd-msg .close-message").click(function() {
                            var msg_slug = jQuery(this).data("msg_slug");
                            var that = this;
                            jQuery.ajax({
                                url: ajaxurl,
                                type: 'post',
                                data: "msg_slug=" + msg_slug + "&action=wpgd_dismiss_message",
                                dataType: 'json',
                                beforeSend: function() {
                                    jQuery(that).closest(".wpgd-msg").remove();
                                },
                                success: function(response) {
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    if (jqXHR.responseText) {
                                        alert(jqXHR.responseText);
                                    } else {
                                        alert("Some error occurred! Please try again.");
                                    }
                                },
                                complete: function(jqXHR, textStatus) {
                                }
                            });
                        });
                    });
                    </script>
                    <?php
                }
            }

            return false;
        }
    

        static public function create_tables()
        {
            global $wpdb;

        }


        /**
         * Add plugin flash message to the WPGlassdoor::$messages array.
         * This message will be displayed in plugin page messages section (at the top of each WPGlassdoor plugin page).
         * @param string $text Flash message text.
         * @param string $type Type of notice (WPGlassdoor::MSG_ERROR, WPGlassdoor::MSG_WARNING, WPGlassdoor::MSG_SUCCESS, WPGlassdoor::MSG_INFO).
         */
        static public function add_flash_message($text, $type = 'info')
        {
            WPGlassdoor::$flash_messages[] = array(
                'text' => $text,
                'type' => $type,
                'display' => 'local',
            );
        }


        /**
         * Add admin notice message to the WPGlassdoor::$messages array.
         * This message will be displayed in admin notices messages section (at the top of each admin page).
         * @param string $text Notice (message) text.
         * @param string $type Type of notice (WPGlassdoor::MSG_ERROR, WPGlassdoor::MSG_WARNING, WPGlassdoor::MSG_SUCCESS, WPGlassdoor::MSG_INFO).
         */
        static public function add_admin_notice($text, $type = 'info', $dismiss = false, $slug = '')
        {
            WPGlassdoor::$flash_messages[] = array(
                'text' => $text,
                'type' => $type,
                'dismiss' => $dismiss,
                'slug'    => $slug,
                'display' => 'global',
            );
        }


        static public function ajax_handler()
        {
            if (isset($_REQUEST['ajax']) and ($_REQUEST['ajax'] == 'wpgd-ajax')) {
                if (isset($_REQUEST['action'])) {
                    switch ($_REQUEST['action']) {

                        default:
                            break;

                    }
                    exit();
                }
            }
        }


        static public function get_settings()
        {
            $settings = get_option('wpgd_settings', array());
            $settings = WPGDUtils::parse_args_dbl($settings, self::$default_settings);
            return $settings;
        }


        static public function update_settings($settings)
        {
            $settings = WPGDUtils::parse_args_dbl($settings, self::$default_settings);
            update_option('wpgd_settings', $settings);
            return $settings;
        }

    }

}

/**
 * Include base classes.
 */
include_once(WPGlassdoor::LIB_PATH . '/wpgdajax.class.php');
include_once(WPGlassdoor::LIB_PATH . '/wpgdutils.class.php');
include_once(WPGlassdoor::LIB_PATH . '/wpgdwidget.class.php');

/**
 * Initialize plugin.
 */
WPGlassdoor::init();
WPGDAjax::init();

?>
