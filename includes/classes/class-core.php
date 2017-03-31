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
                $data['show_description'] = isset($_GET['show_description']) ? (bool)$_GET['show_description'] : false;
                $data['post_data'] = array_filter(isset($_GET['post_data']) ? explode('|', $_GET['post_data']) : []);
                $data['post_meta_fields'] = array_filter(isset($_GET['post_meta_fields']) ? explode('|', $_GET['post_meta_fields']) : []);
                echo Template_View_Loader::get_template('template-parts/terms/list-item', $data);
            }
        }
        
        public static function get_term_posts()
        {
            global $wp_query;
            $wp_query->query_vars['posts_per_page'] = 0;
            
            $args = array(
                'post_type' => get_post_type(),
                'posts_per_page' => 0,
                'tax_query' => array(
                    array(
                        'taxonomy' => $wp_query->queried_object->taxonomy,
                        'field'    => 'slug',
                        'terms'    => $wp_query->queried_object->slug,
                        'include_children' => false
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
                $data['show_post_data'] = array_filter(isset($_GET['post_data']) ? explode('|', $_GET['post_data']) : []);
                $post_meta_fields = array_filter(isset($_GET['post_meta_fields']) ? explode('|', $_GET['post_meta_fields']) : []);
                $data['show_post_meta_fields'] = [];
                foreach ($post_meta_fields as $index => $post_meta_field) {
                    $data['show_post_meta_fields'][$post_meta_field] = get_post_meta($term_post->ID, $post_meta_field, true);
                }
                $data['show_post_meta_fields'] = array_filter($data['show_post_meta_fields']);
                
                echo Template_View_Loader::get_template('template-parts/posts/list-item', $data);
            }
        }
        
    }