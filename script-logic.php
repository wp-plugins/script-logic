<?php

/*
  Plugin Name: Script Logic
  Plugin URI: http://scriptbaker.com/script-logic
  Description: Control scripts & stylesheets with WP's conditional tags is_home etc
  Version: 0.3
  Author: Tahir Yasin
  Author URI: http://scriptbaker.com/
 */

if (!class_exists('sbScriptLogic')) :

    // DEFINE PLUGIN ID
    define('SLPLUGINOPTIONS_ID', 'sb_scriptlogic');
    // DEFINE PLUGIN NICK
    define('SLPLUGINOPTIONS_NAME', 'Script Logic');

    class sbScriptLogic
    {

        public function __construct()
        {
            if (is_admin())
            {
                add_action('admin_enqueue_scripts', array($this, 'load_custom_scripts'));
                add_action('switch_theme', array($this, 'switch_theme'));
                add_action('admin_init', array($this, 'register'));
                add_action('admin_menu', array($this, 'menu'));
            }
            add_action('wp_print_scripts', array($this, 'inspect_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'inspect_styles'));
        }

        /**
         * Return absolute file path
         * @param string $file
         * @return string 
         */
        public static function file_path($file)
        {
            return plugin_dir_path(__FILE__) . $file;
        }

        /** hooking the plugin options/settings
         */
        public static function register()
        {
            register_setting(SLPLUGINOPTIONS_ID . '_options', 'sb_scriptlogic', array('sbScriptLogic', 'load_scripts'));
        }

        /**
         * Manually Load Scripts
         * @param array $input
         * @return array $input 
         */
        function load_scripts($input)
        {
            if (!empty($_POST['loadscripts']))
            {
                @file_get_contents(get_home_url());
            }
            return $input;
        }

        /**
         * Usage: hooking (registering) the plugin menu
         */
        public static function menu()
        {
            // Create menu tab
            add_options_page(SLPLUGINOPTIONS_NAME . ' Plugin Options', SLPLUGINOPTIONS_NAME, 'manage_options', SLPLUGINOPTIONS_ID . '_options', array('sbScriptLogic', 'options_page'));
        }

        /**
         * Show options/settings form page
         */
        public static function options_page()
        {
            if (!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            if (!empty($_POST['loadscripts']))
            {
                @file_get_contents(get_home_url());
            }

            $plugin_id = SLPLUGINOPTIONS_ID;
            // display options page
            include(self::file_path('options.php'));
        }

        function inspect_styles()
        {
            if (!is_admin())
            {
                global $wp_styles;

                $option_name = 'sb_registered_styles';
                if ($sl_settings = get_option($option_name))
                {
                    $sl_settings = unserialize($sl_settings);
                    if (is_array($sl_settings->queue) && is_array($wp_styles->queue))
                        $merged_queue = $sl_settings->queue + $wp_styles->queue;
                    else
                        $merged_queue = $wp_styles->queue;
                    if (is_array($sl_settings->registered) && is_array($wp_styles->registered))
                        $merged_registered = $sl_settings->registered + $wp_styles->registered;
                    else
                        $merged_registered = $wp_styles->registered;
                    
                    if(is_array($merged_queue))
                        $wp_styles->queue = array_unique($merged_queue);
                    else
                        $wp_styles->queue = array();
                    
                    $wp_styles->registered = $merged_registered;
                    // The option already exists, so we just update it.
                    update_option($option_name, serialize($wp_styles));
                } else
                {
                    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                    add_option($option_name, serialize($wp_styles), null, 'no');
                }

                if (!empty($wp_styles->queue))
                {
                    foreach ($wp_styles->queue as $handle)
                    {
                        $sb_scriptlogic_val = get_option('sb_scriptlogic');
                        if (!empty($sb_scriptlogic_val['style'][$handle]))
                        {
                            $scriptLogic = stripslashes(trim($sb_scriptlogic_val['style'][$handle]));
                            $scriptLogic = apply_filters("script_logic_eval_override", $scriptLogic);

                            if ($scriptLogic === false)
                            {
                                wp_dequeue_style($handle);
                                continue;
                            }
                            if ($scriptLogic === true)
                                continue;

                            if (stristr($scriptLogic, "return") === false)
                                $scriptLogic = "return (" . $scriptLogic . ");";
                            if (!eval($scriptLogic))
                            {
                                wp_dequeue_style($handle);
                            }
                        }
                    }
                }
            }
        }

        function inspect_scripts()
        {
            if (!is_admin())
            {
                global $wp_scripts;

                $option_name = 'sb_registered_scripts';


                if ($sl_settings = get_option($option_name))
                {
                    $sl_settings = unserialize($sl_settings);
                    if (is_array($sl_settings->queue) && is_array($wp_scripts->queue))
                        $merged_queue = $sl_settings->queue + $wp_scripts->queue;
                    else
                        $merged_queue = $wp_scripts->queue;
                    if (is_array($sl_settings->registered) && is_array($wp_scripts->registered))
                        $merged_registered = $sl_settings->registered + $wp_scripts->registered;
                    else
                        $merged_registered = $wp_scripts->registered;
                    if(is_array($merged_queue))
                        $wp_scripts->queue = array_unique($merged_queue);
                    else
                        $wp_scripts->queue = array();
                    $wp_scripts->registered = $merged_registered;
                    // The option already exists, so we just update it.
                    update_option($option_name, serialize($wp_scripts));
                } else
                {
                    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                    add_option($option_name, serialize($wp_scripts), null, 'no');
                }


                if (!empty($wp_scripts->queue))
                {
                    foreach ($wp_scripts->queue as $handle)
                    {
                        $sb_scriptlogic_val = get_option('sb_scriptlogic');
                        if (!empty($sb_scriptlogic_val['script'][$handle]))
                        {
                            $scriptLogic = stripslashes(trim($sb_scriptlogic_val['script'][$handle]));
                            $scriptLogic = apply_filters("script_logic_eval_override", $scriptLogic);

                            if ($scriptLogic === false)
                            {
                                wp_dequeue_script($handle);
                                continue;
                            }
                            if ($scriptLogic === true)
                                continue;

                            if (stristr($scriptLogic, "return") === false)
                                $scriptLogic = "return (" . $scriptLogic . ");";
                            if (!eval($scriptLogic))
                            {
                                wp_dequeue_script($handle);
                            }
                        }
                    }
                }
            }
        }

        function load_custom_scripts()
        {
            wp_enqueue_script('jquery-ui-core'); // enqueue jQuery UI Core
            wp_enqueue_script('jquery-ui-tabs'); // enqueue jQuery UI Tabs
            wp_enqueue_style('script-logic-admin-ui-css', plugins_url('css/jquery-ui.css', __FILE__), false, '0.1', false);
        }

        /*
         * Reload scripts on theme switch
         */

        function switch_theme($theme)
        {
            delete_option('sb_registered_scripts');
            delete_option('sb_registered_styles');
            return;
        }

    }

    $sl = new sbScriptLogic();
endif;
?>