<li>
    <?php
        $term = $this->get('term');
        $show_description = $this->get('show_description');
        $post_data = $this->get('post_data');
        $post_meta_fields = $this->get('post_meta_fields');
        
        $query_str = '';
        if (!empty($show_description)) {
            $query_str .= 'show_description=' . $show_description . '&';
        }
        if (!empty($post_data)) {
            $query_str .= 'post_data=' . implode('|', $post_data) . '&';
        }
        if (!empty($post_meta_fields)) {
            $tmp = implode('|', $post_meta_fields);
            $tmp = str_replace(' ', '', $tmp);
            $tmp = str_replace(',', '|', $tmp);
            $query_str .= 'post_meta_fields=' . $tmp;
        }
    
        $term_link = get_term_link($term);
        if (!empty($query_str)) {
            $term_link .= '?' . $query_str;
        }
    ?>
    <a href="<?php echo $term_link ?>"><?php echo $term->name ?></a>
    <?php
        if (true == $show_description) {
            if (!empty($term->description)) {
                ?>
                <br/>
                <small>
                    <?php
                        echo ' - ' . $term->description;
                    ?>
                </small>
                <?php
            }
        }
    ?>
</li>