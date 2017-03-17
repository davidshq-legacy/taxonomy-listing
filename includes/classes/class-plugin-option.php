<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing plugin option page Class.
     *
     * @since 1.0.0
     */
    class SCRPTZ_TDL_plugin_option
    {
        /**
         * Holds an instance of the object
         *
         * @var SCRPTZ_TDL_plugin_option
         */
        protected static $instance = null;
        /**
         * Options Page title
         *
         * @var string
         */
        protected $title = '';
        /**
         * Options Page hook
         *
         * @var string
         */
        protected $options_page = '';
        /**
         * Option key, and option page slug
         *
         * @var string
         */
        private $key = 'scrptz_tdl_options';
        /**
         * Options page metabox id
         *
         * @var string
         */
        private $metabox_id = 'scrptz_tdl_option_metabox';
        
        /**
         * Constructor
         *
         * @since 0.1.0
         */
        protected function __construct()
        {
            // Set our title
            $this->title = __('Taxonomy Listing Options', 'scrptz-tdl');
            
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
         * Returns the running object
         *
         * @return SCRPTZ_TDL_plugin_option
         */
        public static function get_instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
                self::$instance->hooks();
            }
            
            return self::$instance;
        }
        
        /**
         * Initiate our hooks
         *
         * @since 0.1.0
         */
        public function hooks()
        {
            add_action('admin_init', array($this, 'init'));
            add_action('admin_menu', array($this, 'add_options_page'));
            add_action('cmb2_admin_init', array($this, 'add_options_page_metabox'));
        }
        
        
        /**
         * Register our setting to WP
         *
         * @since  0.1.0
         */
        public function init()
        {
            register_setting($this->key, $this->key);
        }
        
        /**
         * Add menu options page
         *
         * @since 0.1.0
         */
        public function add_options_page()
        {
            $this->options_page = add_menu_page($this->title, $this->title, 'manage_options',
                $this->key, array($this, 'admin_page_display'));
            
            // Include CMB CSS in the head to avoid FOUC
            add_action("admin_print_styles-{$this->options_page}",
                array('CMB2_hookup', 'enqueue_cmb_css'));
        }
        
        /**
         * Admin page markup. Mostly handled by CMB2
         *
         * @since  0.1.0
         */
        public function admin_page_display()
        {
            ?>
            <div class="wrap cmb2-options-page <?php echo $this->key; ?>">
                <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
                <?php cmb2_metabox_form($this->metabox_id, $this->key); ?>
            </div>
            <?php
        }
        
        /**
         * Add the options metabox to the array of metaboxes
         *
         * @since  0.1.0
         */
        function add_options_page_metabox()
        {
            
            // hook in our save notices
            add_action("cmb2_save_options-page_fields_{$this->metabox_id}",
                array($this, 'settings_notices'), 10, 2);
            
            $cmb = new_cmb2_box(array(
                'id'         => $this->metabox_id,
                'hookup'     => false,
                'cmb_styles' => false,
                'show_on'    => array(
                    // These are important, don't remove
                    'key'   => 'options-page',
                    'value' => array($this->key,)
                ),
            ));
            
            // Set our CMB2 fields
            /*$cmb->add_field(array(
                'name'    => __('Term Limit', 'scrptz-tdl'),
                'desc'    => __('Limit child terms listings', 'scrptz-tdl'),
                'id'      => 'limit_child_term',
                'type'    => 'text_number',
                'default' => 20
            ));*/
            
            $cmb->add_field(array(
                'name'    => __('Term Description', 'scrptz-tdl'),
                'desc'    => __('Show term description', 'scrptz-tdl'),
                'id'      => 'term_description',
                'type'    => 'checkbox',
                'default' => false
            ));
            
            /*$cmb->add_field(array(
                'name'    => __('Post Limit', 'scrptz-tdl'),
                'desc'    => __('Limit child posts listings', 'scrptz-tdl'),
                'id'      => 'limit_child_post',
                'type'    => 'text_number',
                'default' => 20,
            ));*/
            
            $cmb->add_field(array(
                'name'             => esc_html__('Extra Post Data', 'scrptz-tdl'),
                'desc'             => esc_html__('Show extra post data below the item listing',
                    'scrptz-tdl'),
                'id'               => 'post_data',
                'type'             => 'select_multiple',
                'show_option_none' => true,
                'options'          => array(
                    'post_author'  => esc_html__('Author', 'scrptz-tdl'),
                    'post_date'    => esc_html__('Post Date', 'scrptz-tdl'),
                    'post_content' => esc_html__('Content', 'scrptz-tdl'),
                    'post_excerpt' => esc_html__('Excerpt', 'scrptz-tdl'),
                ),
            ));
            
            $cmb->add_field(array(
                'name'    => __('Post Meta Fields', 'scrptz-tdl'),
                'desc'    => __('Show post meta fields values below the item listing (for multiple option input comma separated links, ' .
                                'enter exact meta_key value)', 'scrptz-tdl'),
                'id'      => 'post_meta_fields',
                'type'    => 'text',
                'default' => '',
            ));
            
        }
        
        /**
         * Register settings notices for display
         *
         * @since  0.1.0
         * @param  int   $object_id Option key
         * @param  array $updated   Array of updated fields
         * @return void
         */
        public function settings_notices($object_id, $updated)
        {
            if ($object_id !== $this->key || empty($updated)) {
                return;
            }
            
            add_settings_error($this->key . '-notices', '', __('Settings updated.', 'scrptz-tdl'),
                'updated');
            settings_errors($this->key . '-notices');
        }
        
        /**
         * Public getter method for retrieving protected/private variables
         *
         * @since  0.1.0
         * @param  string $field Field to retrieve
         * @return mixed          Field value or exception is thrown
         */
        public function __get($field)
        {
            // Allowed fields to retrieve
            if (in_array($field, array('key', 'metabox_id', 'title', 'options_page'), true)) {
                return $this->{$field};
            }
            
            throw new Exception('Invalid property: ' . $field);
        }
        
        /*public function get_post_types()
        {
            $post_types = get_post_type(array(
                'public' => true
            ));
            $post_types = array_filter(array_map(function (&$i) {
                if (!in_array($i, ['page', 'attachment'])) {
                    return $i = ucwords($i);
                }
            }, $post_types));
            
            return $post_types;
        }*/
        
        /*public function get_meta_values($key = '', $type = 'post', $status = 'publish')
        {
            global $wpdb;
            
            if (empty($key)) {
                return;
            }
            
            $r = $wpdb->get_col($wpdb->prepare("
                                SELECT DISTINCT pm.meta_key FROM {$wpdb->postmeta} pm
                                LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                                AND p.post_status = '%s' 
                                AND p.post_type = '%s'
                            ", $status, $type));
            
            return $r;
        }*/
        
        /**
         * input type number for meta fields
         *
         * @param $field
         * @param $escaped_value
         * @param $object_id
         * @param $object_type
         * @param $field_type_object
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
         * @param  object $field             The CMB2_Field type object.
         * @param  string $value             The saved (and escaped) value.
         * @param  int    $object_id         The current post ID.
         * @param  string $object_type       The current object type.
         * @param  object $field_type_object The CMB2_Types object.
         * @return void
         */
        function cmb2_render_select_multiple_field_type(
            $field,
            $escaped_value,
            $object_id,
            $object_type,
            $field_type_object
        ) {
            $select_multiple = '<select class="widefat" multiple name="' . $field->args['_name'] .
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
        
    }