<?php

defined('ABSPATH') or die('This page may not be accessed directly.');

/**
 * Plugin Name: SmartPush notifications
 * Plugin URI: https://smartpush.ai/
 * Description: Free web push notifications system that lets you send push notifications from your desktop or mobile website to your users.
 * Version: 1.0.3
 * Author: SmartPush
 * Author URI: https://smartpush.ai
 * License: MIT
 *
 * This relies on the actions being present in the themes header.php and footer.php
 * header.php code before the closing </head> tag
 *   wp_head();
 */
if (!class_exists('SmartPush')) {
    class SmartPushWebPushNotifications
    {
        private $version = '1.0.3';
        private $pluginPath;
        private $pluginUrl;

        public function __construct()
        {
            $this->pluginPath = plugin_dir_path(__FILE__);
            $this->pluginUrl = plugin_dir_url(__FILE__);

            register_activation_hook(__FILE__, array($this, 'flush'));

            add_action('init', array($this, 'init'));
            add_action('wp_head', array($this, 'wpHeader'), 1);
            add_action('wp_footer', array($this, 'wpFooter'), 1);
            add_filter('query_vars', array($this, 'queryVars'));
            add_action('parse_request', array($this, 'parseRequest'));

            $this->initAdmin();
        }

        public function flush()
        {
            $this->init();
            flush_rewrite_rules();

            add_option('sp_plugin_do_activation_redirect', true);
        }

        public function init()
        {
            add_rewrite_rule('^smart-worker.js/?', 'index.php?smartWorker=1', 'top');
        }

        public function queryVars($query_vars)
        {
            $query_vars[] = 'smartWorker';
            return $query_vars;
        }

        public function parseRequest(&$wp)
        {
            if (array_key_exists('smartWorker', $wp->query_vars)) {
                include $this->pluginPath . 'smart-worker.js.php';
                exit;
            }
        }

        public function wpHeader()
        {
            $uuid = get_option('sp_app');

            if ($uuid) {
	            wp_enqueue_script('smart-push-js', 'https://cdn-static3.com/cdn/push.min.js', array(), $this->version);
            }
        }

        public function wpFooter()
        {
            $uuid = get_option('sp_app');

            if ($uuid) {
	            add_action('wp_footer', array($this, 'embedScript'));
            }
        }

        public function pluginMenu()
        {
            add_options_page(
                'SmartPush',
                'SmartPush Ai',
                'create_users',
                'smart_push_options',
                array($this, 'pluginOptions')
            );
        }

        public function pluginOptions()
        {
            require_once $this->pluginPath . DIRECTORY_SEPARATOR . 'options-page.php';
        }

        public function registerSettings()
        {
            register_setting('smart_push_options', 'sp_app');

            if (get_option('sp_plugin_do_activation_redirect', false)) {
                delete_option('sp_plugin_do_activation_redirect');
                // Do not redirect if activated as part of a bulk activate
                if (!isset($_GET['activate-multi'])) {
                    wp_redirect('options-general.php?page=smart_push_options');
                    // wp_redirect() does not exit automatically and should almost always be followed by exit.
                    exit;
                }
            }
        }

        public function warnNoAppId()
        {
            if (!is_admin()) {
                return;
            }

            $appUuid = get_option('sp_app');

            if (!$appUuid) {
                // TODO: do something
            }
        }

        public function registerStyles()
        {
            $path = $this->pluginUrl . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'options-styles.css';
            wp_enqueue_style('options-page-styles', $path);
        }

        public function registerScripts()
        {
            $path = $this->pluginUrl . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'options-scripts.js';
	        wp_enqueue_script('options-page-scripts', $path);
        }

        public function registerSettingsLink($links)
        {
            $settingsLink = '<a href="options-general.php?page=smart_push_options">Settings</a>';
            array_unshift($links, $settingsLink);
            return $links;
        }

        private function initAdmin()
        {
            add_action('admin_menu', array($this, 'pluginMenu'));
            add_action('admin_init', array($this, 'registerSettings'));
            add_action('admin_notices', array($this, 'warnNoAppId'));
            add_action('admin_print_styles', array($this, 'registerStyles'));
            add_action('admin_print_scripts', array($this, 'registerScripts'));

            $plugin = plugin_basename(__FILE__);
            add_filter('plugin_action_links_' . $plugin, array($this, 'registerSettingsLink'));
        }

        public function embedScript()
        {
        	?>
	        <script type="text/javascript">(function () {
                    (function () {
                        WPush.registerServiceWorker("<?php echo get_option('sp_app'); ?>");
                    })();
                })();
	        </script>
	        <?php
        }
    }
}

$smartPush = new SmartPushWebPushNotifications();
