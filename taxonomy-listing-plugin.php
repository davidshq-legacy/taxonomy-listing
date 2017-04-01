<?php
    
    /**
     * Plugin Name: Taxonomy Directory Listing
     * Plugin URI:  http://www.scripterz.in/
     * Description: Taxonomy Directory Listing
     * Version:     1.0.3
     * Author:      Suraj Gupta
     * Author URI:  https://twitter.com/surajprgupta
     * Donate link: https://www.paypal.me/surajprgupta
     * License:     GPLv2
     * Text Domain: scrptz-tdl
     * Domain Path: /languages
     *
     * @link    http://www.scripterz.in/
     *
     * @package Taxonomy Directory Listing
     * @version 1.0.0
     */
    
    //define SCRPTZ_TDL_ENV
    define('SCRPTZ_TDL_ENV', 'development');
    
    // User composer autoload.
    require __DIR__ . '/vendor/autoload.php';
    
    /**
     * Main initiation class
     *
     * @since  1.0.0
     */
    class SCRPTZ_TDL_Functionality
    {
        /**
         * Current version
         *
         * @var  string
         * @since  1.0.0
         */
        const VERSION = '1.0.3';
        
        /**
         * Path of plugin directory
         *
         * @var string
         * @since  1.0.0
         */
        public static $path = '';
        
        /**
         * Singleton instance of plugin
         *
         * @var SCRPTZ_TDL_Functionality
         * @since  1.0.0
         */
        protected static $single_instance = null;
        
        /**
         * URL of plugin directory
         *
         * @var string
         * @since  1.0.0
         */
        protected $url = '';
        
        /**
         * Plugin basename
         *
         * @var string
         * @since  1.0.0
         */
        protected $basename = '';
        
        /**
         * Sets up our plugin
         *
         * @since  1.0.0
         */
        protected function __construct()
        {
            $this->basename = plugin_basename(__FILE__);
            $this->url = plugin_dir_url(__FILE__);
            self::$path = plugin_dir_path(__FILE__);
        }
        
        /**
         * Creates or returns an instance of this class.
         *
         * @since  1.0.0
         * @return SCRPTZ_TDL_Functionality A single instance of this class.
         */
        public static function get_instance()
        {
            if (null === self::$single_instance) {
                self::$single_instance = new self();
            }
            
            return self::$single_instance;
        }
        
        /**
         * Include a file from the includes directory
         *
         * @since  1.0.0
         * @param  string $filename Name of the file to be included.
         * @return bool   Result of include call.
         */
        public static function include_file($filename)
        {
            $file = self::dir($filename . '.php');
            if (file_exists($file)) {
                return include_once($file);
            }
            
            return false;
        } // END OF PLUGIN CLASSES FUNCTION
        
        /**
         * This plugin's directory
         *
         * @since  1.0.0
         * @param  string $path (optional) appended path.
         * @return string       Directory and path
         */
        public static function dir($path = '')
        {
            static $dir;
            $dir = $dir ? $dir : trailingslashit(dirname(__FILE__));
            
            return $dir . $path;
        }
        
        /**
         * Add hooks and filters
         *
         * @since  1.0.0
         * @return void
         */
        public function hooks()
        {
            add_action('init', array($this, 'init'));
        }
        
        /**
         * Activate the plugin
         *
         * @since  1.0.0
         * @return void
         */
        public function _activate()
        {
            // Make sure any rewrite functionality has been loaded.
            flush_rewrite_rules();
        }
        
        /**
         * Deactivate the plugin
         * Uninstall routines should be in uninstall.php
         *
         * @since  1.0.0
         * @return void
         */
        public function _deactivate()
        {
        }
        
        /**
         * Init hooks
         *
         * @since  1.0.0
         * @return void
         */
        public function init()
        {
            if ($this->check_requirements()) {
                load_plugin_textdomain('scrptz-tdl', false,
                    dirname($this->basename) . '/languages/');
                
                $this->plugin_classes();
                $this->enqueue_script();
                $this->enqueue_style();
            }
        }
        
        /**
         * Check if the plugin meets requirements and
         * disable it if they are not present.
         *
         * @since  1.0.0
         * @return boolean result of meets_requirements
         */
        public function check_requirements()
        {
            if (!$this->meets_requirements()) {
                
                // Add a dashboard notice.
                add_action('all_admin_notices', array($this, 'requirements_not_met_notice'));
                
                // Deactivate our plugin.
                add_action('admin_init', array($this, 'deactivate_me'));
                
                return false;
            }
            
            return true;
        }
        
        /**
         * Check that all plugin requirements are met
         *
         * @since  1.0.0
         * @return boolean True if requirements are met.
         */
        public static function meets_requirements()
        {
            // Do checks for required classes / functions
            // function_exists('') & class_exists('').
            if (defined('WDS_SHORTCODES_LOADED') && defined('CMB2_LOADED')) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * Attach other plugin classes to the base plugin class.
         *
         * @since  1.0.0
         * @return void
         */
        public function plugin_classes()
        {
            $this->add_dev_classes();
            
            $this->shortcode = new SCRPTZ_TDL_Shortcodes($this);
            $this->template_load = new Template_Loader($this);
            
            // Only create the full metabox object if in the admin.
            /*if (is_admin()) {
            } else {
            }*/
            
        }
        
        public function add_dev_classes()
        {
            if (defined('SCRPTZ_TDL_ENV') && SCRPTZ_TDL_ENV == 'development') {
                if (file_exists(__DIR__ . '/dev/WP_Logging.php')) {
                    include __DIR__ . '/dev/WP_Logging.php';
                }
            }
            include __DIR__ . '/dev/Logging_Mods.php';
        }
        
        /**
         * enqueue js from plugin
         *
         * @since  1.0.3
         * @return void
         */
        protected function enqueue_script()
        {
            $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            
            if (is_admin()) {
                
                wp_enqueue_script(
                    'scrptz-tdl-admin-main-js',
                    SCRPTZ_TDL_Functionality::url("templates/admin/js/main{$min}.js"),
                    array('jquery'),
                    SCRPTZ_TDL_Functionality::VERSION
                );
                
                wp_enqueue_script(
                    'scrptz-tdl-admin-select2-js',
                    SCRPTZ_TDL_Functionality::url("bower_components/select2/dist/js/select2{$min}.js"),
                    array('jquery'),
                    SCRPTZ_TDL_Functionality::VERSION
                );
            } else {
    
                wp_enqueue_script(
                    'scrptz-tdl-main-js',
                    SCRPTZ_TDL_Functionality::url("templates/js/main{$min}.js"),
                    array('jquery'),
                    SCRPTZ_TDL_Functionality::VERSION
                );
            }
        }
        
        /**
         * This plugin's url
         *
         * @since  1.0.0
         * @param  string $path (optional) appended path.
         * @return string       URL and path
         */
        public static function url($path = '')
        {
            static $url;
            $url = $url ? $url : trailingslashit(plugin_dir_url(__FILE__));
            
            return $url . $path;
        }
        
        /**
         * enqueue css from plugin
         *
         * @since  1.0.3
         * @return void
         */
        protected function enqueue_style()
        {
            $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            
            if (is_admin()) {
                
                wp_enqueue_style(
                    'scrptz-tdl-admin-main',
                    SCRPTZ_TDL_Functionality::url("templates/admin/css/main{$min}.css"),
                    array(),
                    SCRPTZ_TDL_Functionality::VERSION
                );
                
                wp_enqueue_style(
                    'scrptz-tdl-admin-select2',
                    SCRPTZ_TDL_Functionality::url("bower_components/select2/dist/css/select2{$min}.css"),
                    array(),
                    SCRPTZ_TDL_Functionality::VERSION
                );
            } else {
    
                wp_enqueue_style(
                    'scrptz-tdl-main',
                    SCRPTZ_TDL_Functionality::url("templates/css/main{$min}.css"),
                    array(),
                    SCRPTZ_TDL_Functionality::VERSION
                );
            }
        }
        
        /**
         * Deactivates this plugin, hook this function on admin_init.
         *
         * @since  1.0.0
         * @return void
         */
        public function deactivate_me()
        {
            deactivate_plugins($this->basename);
        }
        
        /**
         * Adds a notice to the dashboard if the plugin requirements are not met
         *
         * @since  1.0.0
         * @return void
         */
        public function requirements_not_met_notice()
        {
            // Output our error.
            echo '<div id="message" class="error">';
            echo '<p>' .
                 sprintf(__('Taxonomy Directory Listing has been <a href="%s">deactivated</a>. ' .
                            'It requires CMB2 and WDS_Shortcode plugins, ' .
                            'please make sure that these plugins are installed.',
                     'scrptz-tdl'), admin_url('plugins.php')) . '</p>';
            echo '</div>';
        }
        
        /**
         * Magic getter for our object.
         *
         * @since  1.0.0
         * @param string $field Field to get.
         * @throws Exception Throws an exception if the field is invalid.
         * @return mixed
         */
        public function __get($field)
        {
            switch ($field) {
                case 'version':
                    return self::VERSION;
                case 'basename':
                case 'url':
                case 'path':
                default:
                    throw new Exception('Invalid ' . __CLASS__ . ' property: ' . $field);
            }
        }
        
        /**
         * Get the template path.
         *
         * @return string
         */
        public function template_path()
        {
            return apply_filters('scrptz_template_path', 'scrptz-tdl/');
        }
    }
    
    
    /**
     * Grab the SCRPTZ_TDL_Functionality object and return it.
     * Wrapper for SCRPTZ_TDL_Functionality::get_instance()
     *
     * @since  1.0.0
     * @return SCRPTZ_TDL_Functionality  Singleton instance of plugin class.
     */
    function scrptz_tdl_func()
    {
        return SCRPTZ_TDL_Functionality::get_instance();
    }
    
    // Kick it off.
    add_action('plugins_loaded', array(scrptz_tdl_func(), 'hooks'));
    
    register_activation_hook(__FILE__, array(scrptz_tdl_func(), '_activate'));
    register_deactivation_hook(__FILE__, array(scrptz_tdl_func(), '_deactivate'));
