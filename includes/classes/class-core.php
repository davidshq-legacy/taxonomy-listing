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
                echo Template_View_Loader::get_template('template-parts/terms/list-item', $data);
            }
        }
        
        public static function get_term_posts()
        {
            global $wp_query;
            
            $args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => $wp_query->queried_object->taxonomy,
                        'field'    => 'slug',
                        'terms'    => $wp_query->queried_object->slug
                    )
                )
            );
            $term_posts = get_posts($args);
            
            if (!empty($term_posts)) {
                $data['term_posts'] = $term_posts;
                echo Template_View_Loader::get_template('template-parts/posts/list', $data);
            }
        }
        
        public static function get_term_posts_list($term_posts)
        {
            foreach ($term_posts as $index => $term_post) {
                $data['post'] = $term_post;
                echo Template_View_Loader::get_template('template-parts/posts/list-item', $data);
            }
        }
    }