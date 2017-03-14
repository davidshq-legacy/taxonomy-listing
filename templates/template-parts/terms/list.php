<?php
    $term_children = $this->get('term_children');
    if (!empty($term_children)) {
        ?>
        <div class="sub-terms-container">
            <h2>Subcategories</h2>
            <ul>
                <?php
                    SCRPTZ_TDL_Core::get_term_children_list($term_children);
                ?>
            </ul>
        </div>
        <?php
    }