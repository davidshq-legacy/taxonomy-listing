<li>
    <a href="<?php echo get_term_link($this->get('term')->term_id) ?>"><?php echo $this->get('term')->name ?></a>
    <?php
    if("on" == $this->get("show_description")) {
        ?>
        <br/>
        <small>
            <?php
            echo ' - ' . $this->get('term')->description;
            ?>
        </small>
    <?php
    }
    ?>
</li>