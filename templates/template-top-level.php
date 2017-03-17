<style type="text/css"
       src="<?php echo scrptz_tdl_func()->url() . "templates/css/main.css" ?>"></style>
<?php
    echo "<ul>";
    foreach ($this->get('terms') as $index => $term) {
        
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
    