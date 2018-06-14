<?php
    
    /**
     * Get template part
     *
     * Searches several areas for the appropriate template part in the following order:
     *  - yourtheme/slug-name.php and yourtheme/pctdl/slug-name.php
     *  - slug-name.php
     *  - yourtheme/slug.php and yourtheme/pctdl/slug
     *
     * @access public
     * @param mixed $slug
     * @param string $name (default: '')
     * @return void
     * @since 1.0.0
     */
    function pctdl_get_template_part( $slug, $name = '' ) {
        $template = '';
        
        // Look in yourtheme/slug-name.php and yourtheme/pctdl/slug-name.php
        if ( $name ) {
            $template = locate_template( array( "{$slug}-{$name}.php", pctdl_func()->template_path() . "{$slug}-{$name}.php" ) );
        }
        
        // Get default slug-name.php
        if ( ! $template && $name && file_exists( pctdl_func()->dir() . "/templates/{$slug}-{$name}.php" ) ) {
            $template = pctdl_func()->dir() . "/templates/{$slug}-{$name}.php";
        }
        
        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/pctdl/slug.php
        if ( ! $template ) {
            $template = locate_template( array( "{$slug}.php", pctdl_func()->template_path() . "{$slug}.php" ) );
        }
        
        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'pctdl_get_template_part', $template, $slug, $name );
        
        if ( $template ) {
            load_template( $template, false );
        }
    }

/**
 * Get Excerpt by ID
 *
 * Returns an excerpt for a specified post ID.
 *
 * @param $post_id
 *
 * @return string
 * @since 1.0.0
 */
function pctdl_get_excerpt_by_id($post_id){
        $the_post = get_post($post_id); //Gets post ID
        $the_excerpt = $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
        $excerpt_length = 25; //Sets excerpt length by word count
        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);
        
        if(count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '…');
            $the_excerpt = implode(' ', $words);
        endif;
        
        return $the_excerpt;
    }