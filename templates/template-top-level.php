<style type="text/css"
       src="<?php echo scrptz_tdl_func()->url() . "templates/css/main.css" ?>"></style>

<?php
    
    $terms = $this->get('terms');
    $show_description = $this->get('show_description');
    
    if (is_wp_error($terms)) {
        
        echo "<ul>";
        
        $error_string = $terms->get_error_message();
        echo "<li class='scrptz-top-terms'>" .
             "(" . $this->get('taxonomy') . ") $error_string" .
             "</li>";
        
        echo "</ul>";
        
    } else {
        
        echo "<ul>";
        foreach ($terms as $index => $term) {
            
            echo "<li class='scrptz-top-terms'>" .
                 "<a href='" . get_term_link($term) . "'>$term->name</a>" .
                 "</li>";
            
            if ("on" == $this->get("show_description")) {
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