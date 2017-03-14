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
    
        private   $prefix = "scrptz_tdl_";
    
        private $exclude_taxonomy = array(
            "nav_menu", "link_category", "post_format"
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
    
            add_action('cmb2_render_text_number', array($this, 'meta_addtnl_type_text_number'), 10, 5);
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
                'name'    => __('Term Limit', 'scrptz-tdl'),
                'desc'    => __('Limit child terms listings', 'scrptz-tdl'),
                'id'      => 'limit_child_term',
                'type'    => 'text_number',
                'default' => 20
            );
            
            $fields[] = array(
                'name'    => __('Post Limit', 'scrptz-tdl'),
                'desc'    => __('Limit child posts listings', 'scrptz-tdl'),
                'id'      => 'limit_child_post',
                'type'    => 'text_number',
                'default' => 20,
            );
            
            return $fields;
        }
    
        /**
         * input type number for meta fields
         *
         * @param $field
         * @param $escaped_value
         * @param $object_id
         * @param $object_type
         * @param $field_type_object
         */
        function meta_addtnl_type_text_number($field, $escaped_value, $object_id, $object_type, $field_type_object)
        {
            echo $field_type_object->input(array('type' => 'number', 'min' => 0));
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
