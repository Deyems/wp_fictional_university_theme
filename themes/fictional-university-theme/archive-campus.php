<?php
  get_header();
  pageBanner([
      'title' => 'Our Campuses',
      'subtitle' => get_the_archive_description(),
  ]);
?>
    <div class="container container--narrow page-section">
        <div class="acf-map">
            <?php
                while(have_posts()):
                the_post();
                $mapLocation = get_field('map_location');
            ?>
            <div class="marker" 
                data-lat="<?php echo $mapLocation['lat']; ?>" 
                data-lng="<?php echo $mapLocation['lng']; ?>">
                <h1>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h1>
                <p><?php echo $mapLocation['address']; ?></p>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            ?>
        </div>
        <div>
            <?php echo paginate_links(); ?>
        </div>
    </div>
<?php get_footer();
?>
