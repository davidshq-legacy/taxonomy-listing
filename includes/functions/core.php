<?php
    
    /**
     * Get template part (for templates like the shop-loop).
     *
     * @access public
     * @param mixed $slug
     * @param string $name (default: '')
     * @return void
     */
    function scrptz_get_template_part( $slug, $name = '' ) {
        $template = '';
        
        // Look in yourtheme/slug-name.php and yourtheme/scrptz-tdl/slug-name.php
        if ( $name ) {
            $template = locate_template( array( "{$slug}-{$name}.php", scrptz_tdl_func()->template_path() . "{$slug}-{$name}.php" ) );
        }
        
        // Get default slug-name.php
        if ( ! $template && $name && file_exists( scrptz_tdl_func()->dir() . "/templates/{$slug}-{$name}.php" ) ) {
            $template = scrptz_tdl_func()->dir() . "/templates/{$slug}-{$name}.php";
        }
        
        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/scrptz-tdl/slug.php
        if ( ! $template ) {
            $template = locate_template( array( "{$slug}.php", scrptz_tdl_func()->template_path() . "{$slug}.php" ) );
        }
        
        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'scrptz_get_template_part', $template, $slug, $name );
        
        if ( $template ) {
            load_template( $template, false );
        }
    }
    
    /**
     * Helper function to get/return the SCRPTZ_TDL_plugin_option object
     * @since  0.1.0
     * @return SCRPTZ_TDL_plugin_option object
     */
    function scrptz_tdl_plugin_option() {
        return SCRPTZ_TDL_plugin_option::get_instance();
    }
    
    /**
     * Wrapper function around cmb2_get_option
     * @since  0.1.0
     * @param  string $key     Options array key
     * @param  mixed  $default Optional default value
     * @return mixed           Option value
     */
    function scprtz_tdl_get_option( $key = '', $default = null ) {
        if ( function_exists( 'cmb2_get_option' ) ) {
            // Use cmb2_get_option as it passes through some key filters.
            return cmb2_get_option( SCRPTZ_TDL_plugin_option()->key, $key, $default );
        }
        
        // Fallback to get_option if CMB2 is not loaded yet.
        $opts = get_option( SCRPTZ_TDL_plugin_option()->key, $key, $default );
        
        $val = $default;
        
        if ( 'all' == $key ) {
            $val = $opts;
        } elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
            $val = $opts[ $key ];
        }
        
        return $val;
    }