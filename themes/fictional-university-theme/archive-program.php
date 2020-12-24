<?php
  get_header();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg');?>) "></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
            <?php the_archive_title(); ?>
            </h1>
            <div class="page-banner__intro">
            <p> These are our programs here <?php the_archive_description(); ?> </p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <ul class="link-list min-list">
            <?php
                while(have_posts()):
                the_post();
            ?>
            <li class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            
            <?php
                endwhile;
                wp_reset_postdata();
            ?>
        </ul>
        <div>
            <?php echo paginate_links(); ?>
        </div>
    </div>
<?php get_footer();
?>
