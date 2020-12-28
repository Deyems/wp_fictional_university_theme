<?php
  get_header();
  pageBanner([
      'title' => get_the_archive_title(),
      'subtitle' => get_the_archive_description(),
  ]);
?>
    <div class="container container--narrow page-section">
        <ul class="link-list min-list">
            <?php
                while(have_posts()):
                the_post();
            ?>
            <li class="headline headline--medium headline--post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
            
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
