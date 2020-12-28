<?php
  get_header();
  pageBanner([
      'title' => 'All Events',
      'subtitle' => 'Our Events are listed below:',
  ]);
?>
    
    <div class="container container--narrow page-section">
        <?php
            while(have_posts()):
            the_post();
            get_template_part('template-parts/content-event');
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
