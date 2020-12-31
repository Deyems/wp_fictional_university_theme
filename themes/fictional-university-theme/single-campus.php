<?php
    get_header();
    while(have_posts()):
      the_post();
      pageBanner();
?>
    <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="
            <?php echo get_post_type_archive_link('campus'); ?>">
            <i class="fa fa-home" aria-hidden="true"></i>
            All Campuses
          </a>
          <span class="metabox__main">
            <?php the_title(); ?>
          </span>
        </p>
      </div>
        <div class="generic-content">
            <?php the_content(); 
                $mapLocation = get_field('map_location');
            ?>

            <div class="acf-map">
                <div class="marker" 
                    data-lat="<?php echo $mapLocation['lat']; ?>"
                    data-lng="<?php echo $mapLocation['lng']; ?>">
                    <h1>
                        <?php the_title(); ?>
                    </h1>
                    <p>
                        <?php echo $mapLocation['address']; ?>
                    </p>
                </div>
            </div>

        </div>
            
        <?php
            endwhile;

          $args = [
            'post_type' => 'program',
            'posts_per_page' => '-1',
            'orderby' => 'title',
            'order' =>  'ASC',
            'meta_query' => [
              [
                'key' => 'related_campus',
                'compare' => 'LIKE',
                'value' => '"'. get_the_ID() .'"',
              ],
            ]
          ];
          $relatedPrograms = new WP_Query($args);
          if($relatedPrograms->have_posts()){
            echo '<hr class="section-break">
            <h1 class="headline headline--medium">'.'Programs Available at this Campus</h1>';
            echo "<ul class='min-list list-link'>";
          }
          while ( $relatedPrograms->have_posts() ) {
            $relatedPrograms->the_post();
        ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                    <?php echo get_the_title(); ?>
                </a>
            </li>
        <?php
            }
            echo "</ul>";
            wp_reset_postdata();
            //Make a query to Pull out the related Events
            $today = date('Ymd');
            $args3 = [
                'post_type' => 'event',
                'posts_per_page' => '-1',
                'orderby' => 'title',
                'order' =>  'ASC',
                'meta_key' => 'event_date',
                'meta_query' => [
                    [
                        'key' => 'related_campus',
                        'compare' => 'LIKE',
                        'value' => '"'. get_the_ID() .'"',
                    ],
                    [
                        'key' => 'event_date',
                        'value' => $today,
                        'compare' => '>=',
                        'type' => 'numeric',
                      ],
                ],
            ];
            $relatedEvents = new WP_Query($args3);
            if($relatedEvents->have_posts()){
                echo '<hr class="section-break">
                <h1 class="headline headline--medium">'.'Events holding on this Campus</h1>';
                echo "<ul class='min-list list-link'>";
                while($relatedEvents->have_posts()){
                    $relatedEvents->the_post();
                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </li>
                <?php
                }
            }
        ?>
    </div>

<?php
    get_footer();
?>