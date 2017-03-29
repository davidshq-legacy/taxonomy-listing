<?php
    /**
     * Taxonomy Directory Listing
     *
     * @since   1.0.0
     * @package Taxonomy Directory Listing
     */
    
    /**
     * Taxonomy Directory Listing Core Class.
     *
     * @since 1.0.0
     */
    class SCRPTZ_TDL_Core
    {
        public static function get_term_children()
        {
            global $wp_query;
            $term_children = get_terms(array(
                    'parent'   => $wp_query->queried_object->term_id,
                    'taxonomy' => $wp_query->queried_object->taxonomy,
                    'parent' => 0
                )
            );
            
            if (!empty($term_children)) {
                $data['term_children'] = $term_children;
                echo Template_View_Loader::get_template('template-parts/terms/list', $data);
            }
        }
        
        public static function get_term_children_list($term_children)
        {
            foreach ($term_children as $index => $term_child) {
                $data['term'] = $term_child;
                $data['show_description'] = scprtz_tdl_get_option('term_description', "off");
                echo Template_View_Loader::get_template('template-parts/terms/list-item', $data);
            }
        }
        
        public static function get_term_posts()
        {
            global $wp_query;
            $wp_query->query_vars['posts_per_page'] = 0;
            
            /*$args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => $wp_query->queried_object->taxonomy,
                        'field'    => 'slug',
                        'terms'    => $wp_query->queried_object->slug
                    )
                )
            );*/
            
            $term_posts = get_posts($wp_query->query_vars);
            
            if (!empty($term_posts)) {
                $data['term_posts'] = $term_posts;
                echo Template_View_Loader::get_template('template-parts/posts/list', $data);
            }
        }
        
        public static function get_term_posts_list($term_posts)
        {
            foreach ($term_posts as $index => $term_post) {
                $data['post'] = $term_post;
                $data['show_post_data'] = array_filter(scprtz_tdl_get_option('post_data', []));
                $post_meta_fields = array_map('trim', explode(',', scprtz_tdl_get_option('post_meta_fields', "")));
                foreach ($post_meta_fields as $index => $post_meta_field) {
                    $data['show_post_meta_fields'][$post_meta_field] = get_post_meta($term_post->ID, $post_meta_field, true);
                }
                $data['show_post_meta_fields'] = array_filter($data['show_post_meta_fields']);
                
                echo Template_View_Loader::get_template('template-parts/posts/list-item', $data);
            }
        }
        
    }