<?php
    $term_posts = $this->get('term_posts');
    if (!empty($term_posts)) {
        ?>
        <div class="sub-terms-container">
            <h2>Posts</h2>
            <ul>
                <?php
                    SCRPTZ_TDL_Core::get_term_posts_list($term_posts);
                ?>
            </ul>
        </div>
        <?php
    }