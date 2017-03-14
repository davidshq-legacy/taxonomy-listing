<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing Shortcodes.
     *
     * @since 1.0.0
     */
    class SCRPTZ_TDL_Shortcodes
    {
        
        /**
         * Instance of SCRPTZ_TDL_Shortcodes
         *
         * @var SCRPTZ_TDL_Shortcodes
         */
        protected $resources;
        
        /**
         * Constructor
         *
         * @since  1.0.0
         * @param  object $plugin Main plugin object.
         * @return void
         */
        public function __construct($plugin)
        {
            $this->resources = new SCRPTZ_TDL_Shortcodes_Resources($plugin);
        }
        
        /**
         * Magic getter for our object. Allows getting but not setting.
         *
         * @param string $field
         * @throws Exception Throws an exception if the field is invalid.
         * @return mixed
         */
        public function __get($field)
        {
            return $this->{$field};
        }
        
    }
