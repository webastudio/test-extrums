<?php
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Blog</h1>
            <?php if( have_posts() ) :
                while( have_posts() ) : the_post(); ?>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php if( has_excerpt() ) {
                        the_excerpt();
                    } ?>
                <?php
                endwhile;
            endif; ?>
        </div>
    </div>
</div>
<?php get_footer();
