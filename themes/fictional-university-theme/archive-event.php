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
    <?php while(have_posts()): 
      the_post();
    ?>
      <!-- <div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php //the_permalink(); ?>"><?php //the_title(); ?></a></h2>
        <div class="metabox">
          <p>Posted by <?php //the_author_posts_link(); ?> on <?php //the_time('n-j-Y') ?> in the <?php //echo get_the_category_list(', ') ?></p>
        </div>
        <div class="generic-content">
          <?php //the_excerpt(); ?>
          <a class="btn btn--blue" href="<?php //the_permalink(); ?>">Continue Reading &raquo</a>
        </div>
      </div> -->
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <span class="event-summary__month"> <?php the_time('M');  ?></span>
            <span class="event-summary__day"> <?php the_time('d');  ?></span>
            </a>
            <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(), 13); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
            </div>
        </div>
    <?php endwhile; ?>
    <div>
      <?php echo paginate_links(); ?>
    </div>
  </div>
  <?php get_footer();
  ?>
