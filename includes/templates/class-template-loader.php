<?php
    /**
     * Template Loader
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Template Loader Class.
     *
     * @since 1.0.0
     */
    class Template_Loader
    {
        
        /**
         * Template names array
         *
         * @var array
         * @since 1.0.0
         */
        public $templates = array();
        
        /**
         * Template name
         *
         * @var string
         * @since 1.0.0
         */
        public $template = '';
        
        /**
         * Template file extension
         *
         * @var string
         * @since 1.0.0
         */
        protected $extension = '.php';

	    /**
	     * Template_Loader constructor.
	     *
	     * @since 1.0.0
	     */
	    public function __construct()
        {
            add_filter('template_include', array($this, 'template_loader'));
        }
        
        /**
         * Load a template.
         *
         * Handles template usage so that we can use our own templates instead of the themes.
         *
         * Templates are in the 'templates' folder. plugin looks for theme
         * overrides in /theme/pctdl/ by default
         *
         * @param mixed $template
         * @return string
         * @since 1.0.0
         */
        public function template_loader($template)
        {
            $file = '';
            $base_template = "template-taxonomy{$this->extension}";
            
            $find[] = "templates/$base_template";
            
            global $wp_query;
            
            if (is_category() || is_tax()) {
                
                $taxonomy = $wp_query->queried_object->taxonomy;
                $page_template = $this->template = "template-taxonomy-{$taxonomy}{$this->extension}";
                $file = $this->template = "{$page_template}";
                
                $find[] = "templates/$file";
                $find[] = pctdl_func()->template_path() . $base_template;
                $find[] = pctdl_func()->template_path() . $file;
                
            }
            
            if ($file) {

                $template = locate_template(array_unique($find));
                if (!$template) {
                    $template = PCTDL_Functionality::$path . 'templates/' . $file;
                    if(!file_exists($template)) {
                        $template = PCTDL_Functionality::$path . 'templates/' . $base_template;
                    }
                }
            }
            
            return $template;
        }
        
    }
