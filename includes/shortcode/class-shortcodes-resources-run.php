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
            'term_description'  => false,
            'post_data'  => [],
            'post_meta_fields'  => '',
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
         *
         * @since 1.0.0
         */
        public function shortcode()
        {
	        try {
		        $output = $this->_shortcode();
	        } catch ( Exception $e ) {
	        }

	        return apply_filters($this->prefix . 'shortcode_output', $output, $this);
        }

	    /**
	     * Shortcode
	     *
	     * @return string
	     * @throws Exception
	     * @since 1.0.0
	     */
	    protected function _shortcode()
        {
            $shortcode_settings_taxonomy = [
                'taxonomy' => $this->att('select_taxonomy'),
                'term_description' => (bool)$this->att('term_description'),
                'post_data' => $this->att('post_data'),
                'post_meta_fields' => $this->att('post_meta_fields')
            ];
            
            $args['terms'] = get_terms(array(
                'taxonomy' => $shortcode_settings_taxonomy['taxonomy'],
                'hide_empty' => true,
                'parent'   => 0,
            ));
            
            $args['taxonomy'] = $shortcode_settings_taxonomy['taxonomy'];
            $args['show_description'] = $shortcode_settings_taxonomy['term_description'];
            $args['post_data'] = $shortcode_settings_taxonomy['post_data'];
            $args['post_meta_fields'] = $shortcode_settings_taxonomy['post_meta_fields'];
            
            return Template_View_Loader::get_template('template-top-level', $args);
        }
        
    }
