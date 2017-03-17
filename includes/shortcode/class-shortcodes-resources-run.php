<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing Shortcodes Resources Run.
     *
     * @since 1.0.0
     */
    class SCRPTZ_TDL_Shortcodes_Resources_Run extends WDS_Shortcodes
    {
        
        /**
         * The Shortcode Tag
         *
         * @var string
         * @since 1.0.0
         */
        public $shortcode = 'scrptz_tdl';
        
        /**
         * Default attributes applied to the shortcode.
         *
         * @var array
         * @since 1.0.0
         */
        public $atts_defaults = array(
            'select_taxonomy'  => 'category', // taxonomy identifier
        );
        
        protected $plugin = null;
        private   $prefix = "scrptz_tdl_";
        
        public function __construct($plugin)
        {
            parent::__construct();
            $this->plugin = $plugin;
        }
        
        /**
         * Shortcode Output
         */
        public function shortcode()
        {
            $output = $this->_shortcode();
            
            return apply_filters($this->prefix . 'shortcode_output', $output, $this);
        }
        
        protected function _shortcode()
        {
            $taxonomy = $this->att('select_taxonomy');
    
            $args['terms'] = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
                'parent'   => 0,
            ));
            
            return Template_View_Loader::get_template('template-top-level', $args);
        }
        
    }
