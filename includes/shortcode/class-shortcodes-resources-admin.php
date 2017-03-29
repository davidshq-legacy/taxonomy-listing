<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing Shortcodes Resources Admin.
     *
     * @since 1.0.0
     */
    class SCRPTZ_TDL_Shortcodes_Resources_Admin extends WDS_Shortcode_Admin
    {
        /**
         * Shortcode Run object
         *
         * @var   SCRPTZ_TDL_Shortcodes_Resources_Run
         * @since 1.0.0
         */
        protected $run;
        
        private $prefix = "scrptz_tdl_";
        
        private $exclude_taxonomy
            = array(
                "nav_menu",
                "link_category",
                "post_format"
            );
        
        /**
         * Constructor
         *
         * @since  1.0.0
         * @param  object $run SCRPTZ_TDL_Shortcodes_Resources_Run object.
         * @return void
         */
        public function __construct(SCRPTZ_TDL_Shortcodes_Resources_Run $run)
        {
            $this->run = $run;
            
            parent::__construct(
                $this->run->shortcode,
                SCRPTZ_TDL_Functionality::VERSION,
                $this->run->atts_defaults
            );
    
        }
        
        /**
         * Sets up the button
         *
         * @return array
         */
        function js_button_data()
        {
            return array(
                'qt_button_text' => __('Taxonomy Directory Listing', 'scrptz-tdl'),
                'button_tooltip' => __('Insert Taxonomy Directory Listing', 'scrptz-tdl'),
                'icon'           => 'dashicons-media-interactive',
                'mceView'        => true, // The future
            );
        }
        
        /**
         * Adds fields to the button modal using CMB2
         *
         * @param $fields
         * @param $button_data
         *
         * @return array
         */
        function fields($fields, $button_data)
        {
            
            $fields[] = array(
                'name'             => __('Taxonomy', 'scrptz-tdl'),
                'desc'             => __('Select Taxonomy', 'scrptz-tdl'),
                'id'               => 'select_taxonomy',
                'type'             => 'select',
                'show_option_none' => false,
                'default'          => '',
                'options'          => $this->get_taxonomies(),
                
            );
            
            $fields[] = array(
                'name'    => __('Term Description', 'scrptz-tdl'),
                'desc'    => __('Show term description', 'scrptz-tdl'),
                'id'      => 'term_description',
                'type'    => 'checkbox',
                'default' => false
            );
            
            $fields[] = array(
                'name'             => esc_html__('Extra Post Data', 'scrptz-tdl'),
                'desc'             => esc_html__('Show extra post data below the item listing',
                    'scrptz-tdl'),
                'id'               => 'post_data',
                'type'             => 'select_multiple',
                'show_option_none' => true,
                'options'          => array(
                    ''  => esc_html__('None', 'scrptz-tdl'),
                    'post_excerpt' => esc_html__('Excerpt', 'scrptz-tdl'),
                    'post_author'  => esc_html__('Author', 'scrptz-tdl'),
                    'post_date'    => esc_html__('Post Date', 'scrptz-tdl'),
                ),
            );
            
            $fields[] = array(
                'name'    => __('Post Meta Fields', 'scrptz-tdl'),
                'desc'    => __('Show post meta fields values below the item listing (for multiple option input comma separated links, ' .
                                'enter exact meta_key value)', 'scrptz-tdl'),
                'id'      => 'post_meta_fields',
                'type'    => 'text',
                'default' => '',
            );
            
            return $fields;
        }
    
        public function get_taxonomies() {
            $taxonomies = get_taxonomies();
            $hRarchy_taxnmy_list = array();
            foreach ($taxonomies as $index => $taxonomy) {
                if(!in_array($taxonomy, $this->exclude_taxonomy)) {
                    if(is_taxonomy_hierarchical($taxonomy)) {
                        $hRarchy_taxnmy_list[$taxonomy] = $taxonomy;
                    }
                }
            }
            return $hRarchy_taxnmy_list;
        }
    }
