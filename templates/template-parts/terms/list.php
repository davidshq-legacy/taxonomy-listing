<?php
    $term_children = $this->get('term_children');
    if (!empty($term_children)) {
        ?>
        <div class="sub-terms-container">
            <ul>
                <?php
                try {
	                SCRPTZ_TDL_Core::get_term_children_list( $term_children );
                } catch ( Exception $e ) {
                }
                ?>
            </ul>
        </div>
        <?php
    }