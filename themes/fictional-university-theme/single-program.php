
<?php
    get_header();
?>

<?php
    while(have_posts()):
    the_post();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(
          <?php echo get_theme_file_uri('images/ocean.jpg');
          ?>) "></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p>Dont forget this SUBTITLE later</p>
            </div>
        </div>
    </div>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
          <p>
              <a class="metabox__blog-home-link" href="
                  <?php echo get_post_type_archive_link('program'); ?>">
                  <i class="fa fa-home" aria-hidden="true"></i>
                  All Programs
              </a>
              <span class="metabox__main">
                  <?php the_title(); ?> 
              </span>
          </p>
        </div>
        <div class="generic-content">
          <?php the_content(); ?>
        </div>
        <?php
        endwhile;
    ?>

        <?php
            $today = date('Ymd');
            $args = [
              'post_type' => 'event',
              'posts_per_page' => '2',
              'orderby' => 'meta_value_num',
              'meta_key' => 'event_date',
              'order' =>  'ASC',
              'meta_query' => [
                [
                  'key' => 'event_date',
                  'value' => $today,
                  'compare' => '>=',
                  'type' => 'numeric',
                ],
                [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"'. get_the_ID() .'"',
                ]
              ]
            ];
            $programEvents = new WP_Query($args);

            if($programEvents->have_posts()){
                echo '<hr class="section-break">
                <h1 class="headline headline--medium">Upcoming '. get_the_title() .' Events</h1>';

            }
            while ( $programEvents->have_posts() ) {
                $programEvents->the_post();
                ?>
                
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                    <span class="event-summary__month"> 
                      <?php $event_date = new DateTime(get_field('event_date')); 
                        echo $event_date->format('M');
                      ?>
                    </span>
                    <span class="event-summary__day"> <?php echo $event_date->format('d'); ?></span>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p>
                      <?php
                        if(has_excerpt()):
                        echo get_the_excerpt();
                        else:
                        echo wp_trim_words(get_the_content(), 13);
                        endif;
                      ?>
                      <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
                    </p>
                  </div>
                </div>

          <?php
            }
            wp_reset_postdata();
            ?>


            
            <?php
            $args = [
              'post_type' => 'professor',
              'posts_per_page' => '-1',
              'orderby' => 'title',
              'order' =>  'ASC',
              'meta_query' => [
                [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"'. get_the_ID() .'"',
                ],
              ]
            ];
            $relatedProfessor = new WP_Query($args);

            if($relatedProfessor->have_posts()){
                echo '<hr class="section-break">
                <h1 class="headline headline--medium">'. get_the_title() .' Lecturers</h1>';
                echo "<ul class='professor-cards'>";
            }
            while ( $relatedProfessor->have_posts() ) {
              $relatedProfessor->the_post();
              ?>
              
              <li class="professor-card__listitem">
                <a class="professor-card" href="<?php the_permalink(); ?>">
                  <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
                  <span class="professor-card__name"><?php the_title(); ?></span>
                </a>
              </li>
            <?php
              }
              echo "</ul>";
              wp_reset_postdata();
            ?>
    </div>

    
<?php
    get_footer();
?>