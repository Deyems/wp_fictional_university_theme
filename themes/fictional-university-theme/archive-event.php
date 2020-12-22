<?php
  get_header();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg');?>) "></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
                All Events
            </h1>
            <div class="page-banner__intro">
            <p> Our Events are listed below: </p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <?php
            while(have_posts()):
            the_post();
        ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month"> <?php 
                    $event_date = new DateTime(get_field('event_date'));
                    echo $event_date->format('M');
                    //the_time('M');  ?>
                </span>
                <span class="event-summary__day"> <?php echo $event_date->format('d');  ?></span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h5>
                <p> <?php echo wp_trim_words(get_the_content(), 13); ?> 
                    <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                </p>
            </div>
        </div>
        <?php
            endwhile;
            wp_reset_postdata();
        ?>
        <div>
            <?php echo paginate_links(); ?>
            <hr class="section-break">
        </div>
        <div>
            <a href="<?php echo site_url('/past-events') ?>">Past Events</a>
        </div>
    </div>
<?php get_footer();
?>
