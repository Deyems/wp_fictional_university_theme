<?php
  get_header();
  pageBanner([
    'title' => 'Search Results!',
    'subtitle' => 'You searched for &ldquo;'. esc_html(get_search_query(false)) .'&rdquo;',
]);
?>
  <div class="container container--narrow page-section">
    <?php
        if(have_posts()){
            while(have_posts()): 
            the_post();
            get_template_part('template-parts/content', get_post_type());
            endwhile;
        }else {?>
            <h2 class="min-list link-list">No results found.</h2>
            <hr class="section-break">
        <?php }
    ?>
    <div>
    <?php get_search_form(); ?>
      <?php echo paginate_links(); ?>
    </div>
  </div>
  <?php 
    get_footer();
  ?>
