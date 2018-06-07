<?php
    $term_posts = $this->get('term_posts');
    if (!empty($term_posts)) {
        ?>
        <div class="sub-terms-container">
            <ul>
                <?php
                try {
	                SCRPTZ_TDL_Core::get_term_posts_list( $term_posts );
                } catch ( Exception $e ) {
                }
                ?>
            </ul>
        </div>
        <?php
    }