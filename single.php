<?php
get_header();
?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if( have_posts() ) :
                    while( have_posts() ) : the_post(); ?>
                        <h1><?php the_title(); ?></h1>
                        <?php the_content(); ?>
                        <?php if(is_user_logged_in()) {
                            $last_viewed = get_user_meta(get_current_user_id(), 'last_viewed', true);
                            if(isset($last_viewed)) {
                                $test_extrums_field = get_post_meta( $last_viewed, 'test_extrums_field', true );
                            ?>
                            <div class="last-viewed p-3 mb-3">
                                <?php
                                echo '<strong class="text-center">I am is last viewed post!</strong>';
                                echo '<h3> Latest viewed post is '. get_the_title($last_viewed) .'</h3>';
                                if( has_excerpt( $last_viewed ) ) {
                                    echo '<p>'. get_the_excerpt( $last_viewed ) .'</p>';
                                }

                                if($test_extrums_field) {
                                    echo $test_extrums_field;
                                }?>
                            </div>
                        <?php }} ?>
                    <?php
                    endwhile;
                endif; ?>
            </div>
        </div>
    </div>
<?php get_footer();
