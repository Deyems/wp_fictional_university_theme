<?php
  get_header();
  pageBanner([
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past Events are listed below:',
    ]);
?>
    
    <div class="container container--narrow page-section">
        <?php
            $today = date('Ymd');
            $args = [
                'paged' => get_query_var('paged', 1),
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'meta_query' => [
                    [
                        'key' => 'event_date',
                        'value' => $today,
                        'compare' => '<',
                        'type' => 'numeric',
                    ]
                ],
            ];
            $loopPastEvents = new WP_Query($args);

            while($loopPastEvents->have_posts()):
                $loopPastEvents->the_post();
                get_template_part('template-parts/content-event');
        ?>

        <?php
            endwhile;
            wp_reset_postdata();
        ?>
        <div>
            <?php 
                echo paginate_links([
                'total' => $loopPastEvents->max_num_pages,
                ]);
            ?>
        </div>
    </div>
<?php
    get_footer();
?>
