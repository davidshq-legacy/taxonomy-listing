<style type="text/css" src="<?php echo pctdl_func()->url() . "templates/css/main.css" ?>"></style>

<?php
    
    $terms = $this->get('terms');
    $show_description = $this->get('show_description');
    $post_data = $this->get('post_data');
    $post_meta_fields = $this->get('post_meta_fields');
    
    if (is_wp_error($terms)) {
        
        echo "<ul>";
        
        $error_string = $terms->get_error_message();
        echo "<li class='pctdl-top-terms'>" .
             "(" . $this->get('taxonomy') . ") $error_string" .
             "</li>";
        
        echo "</ul>";
        
    } else {
        
        $query_str = '';
        if (!empty($show_description)) {
            $query_str .= 'show_description=' . $show_description . '&';
        }
        if (!empty($post_data)) {
            $query_str .= 'post_data=' . implode('|', $post_data) . '&';
        }
        if (!empty($post_meta_fields)) {
            $tmp = implode('|', $post_meta_fields);
            $query_str .= 'post_meta_fields=' . $tmp;
        }
        
        $query_str = rtrim($query_str, '&');
        
        echo "<ul>";
        foreach ($terms as $index => $term) {
            
            $term_link = get_term_link($term);
            if (!empty($query_str)) {
                $term_link .= '?' . $query_str;
            }
            
            echo "<li class='pctdl-top-terms'>" .
                 "<a href='" . $term_link . "'>$term->name</a>" .
                 "</li>";
            
            if ("on" == $show_description) {
                if (!empty($term->description)) {
                    ?>
                    <small>
                        <?php
                            echo ' - ' . $term->description;
                        ?>
                    </small>
                    <?php
                }
            }
        }
        echo "</ul>";
    }