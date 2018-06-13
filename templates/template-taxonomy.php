<?php
    /**
     * The main template file
     *
     * This is the most generic template file for Taxonomy Listing.
     * It is used to display a page when nothing more specific matches a query.
     * E.g., it puts together the home page when no home.php file exists.
     *
     * @link       https://codex.wordpress.org/Template_Hierarchy
     *
     * @package    WordPress
     * @subpackage Taxonomy_Listing
     * @since      1.0
     * @version    1.0
     */
    
    global $wp_query;
    
    get_header(); ?>
    
    <div class="wrap">
        <header class="page-header">
            <h2 class="page-title"><?php echo $wp_query->queried_object->name; ?></h2>
        </header>
        
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
                
                <div class="child-terms">
                    <?php
                    try {
	                    SCRPTZ_TDL_Core::get_term_children();
                    } catch ( Exception $e ) {
                    }
                    ?>
                </div>
                
                <div class="child-posts">
                    <?php
                    try {
	                    SCRPTZ_TDL_Core::get_term_posts();
                    } catch ( Exception $e ) {
                    }
                    ?>
                </div>
                
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php get_footer();
