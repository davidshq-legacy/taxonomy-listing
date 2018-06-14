<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing Shortcodes Resources.
     *
     * @since 1.0.0
     */
    class PCTDL_Shortcodes_Resources
    {
        
        /**
         * Instance of PCTDL_Shortcodes_Resources_Run
         *
         * @var PCTDL_Shortcodes_Resources_Run
         */
        protected $run;
        
        /**
         * Instance of PCTDL_Shortcodes_Resources_Admin
         *
         * @var PCTDL_Shortcodes_Resources_Admin
         */
        protected $admin;
        
        /**
         * Constructor
         *
         * @since  1.0.0
         * @param  object $plugin Main plugin object.
         * @return void
         */
        public function __construct($plugin)
        {
            $this->run = new PCTDL_Shortcodes_Resources_Run($plugin);
            $this->run->hooks();
            
            if (is_admin()) {
                $this->admin = new PCTDL_Shortcodes_Resources_Admin($this->run);
                $this->admin->hooks();
            }
        }
        
    }
