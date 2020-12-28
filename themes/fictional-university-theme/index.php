<?php
  get_header();
  pageBanner([
    'title' => 'Welcome to our Blog!',
    'subtitle' => 'Keep up with our latest news.',
]);
?>
  <div class="container container--narrow page-section">
    <?php while(have_posts()): 
      the_post();
    ?>
      <div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="metabox">
          <p>Posted by <?php the_author_posts_link() ?> on <?php the_time('n-j-Y') ?> in the <?php echo get_the_category_list(', ') ?></p>
        </div>
        <div class="generic-content">
          <?php the_excerpt(); ?>
          <a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading &raquo</a>
        </div>
      </div>
    <?php endwhile; ?>
    <div>
      <?php echo paginate_links(); ?>
    </div>
  </div>
  <?php get_footer();
  ?>
