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
            
            if ($item == 'post_excerpt') {
                $excerpt = pctdl_get_excerpt_by_id($post->ID);
                if (!empty($excerpt)) {
                    echo ' - ' . pctdl_get_excerpt_by_id($post->ID);
                }
            }
            
            if ($item == 'post_author') {
                echo ' - ' . get_the_author_meta('nickname', $post->post_author);
            }
            
            if ($item == 'post_date') {
                echo ' - ' . date('d F Y', strtotime($post->post_date));
            }
            
            ?>
            <?php
        }
        
        foreach ($show_post_meta_fields as $index => $item) {
            
            if (!empty($item)) {
                echo ' - ' . $item;
            }
        }
    
    ?>
</li>