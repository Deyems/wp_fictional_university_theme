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
          // Show Upcoming Events related to this Program
          $today = date('Ymd');
          $args = [
            'post_type' => 'event',
            'posts_per_page' => '-1',
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
              get_template_part('template-parts/content-event');
            }
            wp_reset_postdata();
        ?>
            
        <?php
        // Show Professors taking this Program as selected in the Admin
          $args2 = [
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
          $relatedProfessor = new WP_Query($args2);

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

            $relatedCampuses = get_field('related_campus');
            var_dump($relatedCampuses);
            if($relatedCampuses){
              echo "<hr class='section-break'>";
              echo "<ul>";
              echo '<h2 class="headline headline--medium">'. get_the_title() .' is available at these campuses </h2>';
              foreach($relatedCampuses as $campus){
              ?>
                <li><a href="<?php echo get_the_permalink($campus) ?>"><?php echo get_the_title($campus); ?></a></li> 
              <?php
              }
              echo "</ul>";
              wp_reset_postdata();
            }
        ?>
    </div>
    

    
<?php
    get_footer();
?>