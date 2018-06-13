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
            
            add_action('cmb2_render_text_number', array($this, 'meta_addtnl_type_text_number'), 10,
                5);
            
            add_action('cmb2_render_select_multiple',
                array($this, 'cmb2_render_select_multiple_field_type'), 10,
                5);
            
            add_filter('cmb2_sanitize_select_multiple',
                array($this, 'cmb2_sanitize_select_multiple_callback'),
                10, 2);
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
                    'post_excerpt' => esc_html__('Excerpt', 'scrptz-tdl'),
                    'post_author'  => esc_html__('Author', 'scrptz-tdl'),
                    'post_date'    => esc_html__('Post Date', 'scrptz-tdl'),
                ),
            );
    
            $post_meta_key = [];
            foreach ($this->get_all_meta_keys() as $index => $all_meta_key) {
                $post_meta_key[$all_meta_key] = $all_meta_key;
            }
            
            $fields[] = array(
                'name'             => esc_html__('Post Meta Fields', 'scrptz-tdl'),
                'desc'             => esc_html__('Show post meta fields values below the item listing',
                    'scrptz-tdl'),
                'id'               => 'post_meta_fields',
                'type'             => 'select_multiple',
                'show_option_none' => true,
                'options'          => $post_meta_key,
            );
    
            return $fields;
        }

	    /**
	     * Get Taxonomies
	     *
	     * @return array
	     * @since 1.0.0
	     */
	    public function get_taxonomies()
        {
            $taxonomies = get_taxonomies();
            $hierarchy_taxonomy_list = array();
            foreach ($taxonomies as $index => $taxonomy) {
                if (!in_array($taxonomy, $this->exclude_taxonomy)) {
                    if (is_taxonomy_hierarchical($taxonomy)) {
                        $hierarchy_taxonomy_list[$taxonomy] = $taxonomy;
                    }
                }
            }
            
            return $hierarchy_taxonomy_list;
        }
        
        /**
         * input type number for meta fields
         *
         * @param $field
         * @param $escaped_value
         * @param $object_id
         * @param $object_type
         * @param $field_type_object
         * @since 1.0.0
         */
        function meta_addtnl_type_text_number(
            $field,
            $escaped_value,
            $object_id,
            $object_type,
            $field_type_object
        ) {
            echo $field_type_object->input(array('type' => 'number', 'min' => 0));
        }

	    /**
	     * Adds a custom field type for select multiples.
	     *
	     * @param  object $field The CMB2_Field type object.
	     * @param  $escaped_value
	     * @param  int $object_id The current post ID.
	     * @param  string $object_type The current object type.
	     * @param  object $field_type_object The CMB2_Types object.
	     *
	     * @return void
	     * @since 1.0.0
	     */
        function cmb2_render_select_multiple_field_type(
            $field,
            $escaped_value,
            $object_id,
            $object_type,
            $field_type_object
        ) {
            $select_multiple = '<select class="widefat scrptz-tdl-select2" multiple="multiple" name="' . $field->args['_name'] .
                               '[]" id="' . $field->args['_id'] . '"';
            foreach ($field->args['attributes'] as $attribute => $value) {
                $select_multiple .= " $attribute=\"$value\"";
            }
            $select_multiple .= ' />';
            foreach ($field->options() as $value => $name) {
                $selected = ($escaped_value &&
                             in_array($value, $escaped_value)) ? 'selected="selected"' : '';
                $select_multiple .= '<option class="cmb2-option" value="' . esc_attr($value) .
                                    '" ' . $selected . '>' . esc_html($name) . '</option>';
            }
            $select_multiple .= '</select>';
            $select_multiple .= $field_type_object->_desc(true);
            echo $select_multiple; // WPCS: XSS ok.
        }

	    /**
	     * Sanitize the selected value.
	     *
	     * @param $override_value
	     * @param $value
	     *
	     * @return array|void
	     * @since 1.0.0
	     */
        function cmb2_sanitize_select_multiple_callback($override_value, $value)
        {
            if (is_array($value)) {
                foreach ($value as $key => $saved_value) {
                    $value[$key] = sanitize_text_field($saved_value);
                }
                
                return $value;
            }
            
            return;
        }

	    /**
	     * Generate All Meta Keys
	     *
	     * @return array
	     * @since 1.0.0
	     */
	    function generate_all_meta_keys()
        {
            global $wpdb;
            $query
                = "
                        SELECT DISTINCT($wpdb->postmeta.meta_key)
                        FROM $wpdb->postmeta
                        WHERE $wpdb->postmeta.meta_key != ''
                        AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)'
                        AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
                    ";
            $meta_keys = $wpdb->get_col($query);
            set_transient('scrptz_tdl_all_meta_keys', $meta_keys, 60 * 60 * 24); # create 1 Day Expiration
            
            return $meta_keys;
        }

	    /**
	     * Get All Meta Keys
	     *
	     * @return array|mixed
	     * @since 1.0.0
	     */
	    function get_all_meta_keys(){
            $cache = get_transient('scrptz_tdl_all_meta_keys');
            $meta_keys = $cache ? $cache : $this->generate_all_meta_keys();
            return $meta_keys;
        }
        
    }
