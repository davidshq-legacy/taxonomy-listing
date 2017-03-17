<li>
    <?php
    $post = $this->get('post');
    ?>
    
    <a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title ?></a>
    
    <?php
        $show_post_data = $this->get('show_post_data');
        $show_post_meta_fields = $this->get('show_post_meta_fields');
    
        foreach ($show_post_data as $index => $item) {
            
            ?>
                <?php
            
                    if($item == 'post_excerpt') {
                        echo '<br/><small>' . ' - ' . scprtz_tdl_get_excerpt_by_id($post->ID) . '</small>';
                    }
                    
                    if($item == 'post_author') {
                        echo '<br/><small>' . ' - ' . get_the_author_meta('nickname', $post->post_author) . '</small>';
                    }
                    
                    if($item == 'post_date') {
                        echo '<br/><small>' . ' - ' . date('d F Y', strtotime($post->post_date)) . '</small>';
                    }
                    
                ?>
            <?php
        }
        
        if("on" == $this->get("show_description")) {
            ?>
            <small>
                <?php
                    echo ' - ' . $this->get('term')->description;
                ?>
            </small>
            <?php
        }
    ?>
</li>