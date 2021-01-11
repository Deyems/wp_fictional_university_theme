
<?php
    get_header();
?>

<?php
    while(have_posts()):
    the_post();
    pageBanner();
?>
    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                <?php the_post_thumbnail('professorPortrait'); ?>
                </div>
                <div class="two-thirds">
                    <?php 
                        $likeCount = new WP_Query([
                            'post_type' => 'like',
                            'meta_query' => [
                                [
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID(),
                                ]
                            ]
                        ]);
                        $existStatus = 'no';
                        $existQuery = new WP_Query([
                            'author' => get_current_user_id(),
                            'post_type' => 'like',
                            'meta_query' => [
                                [
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID(),
                                ]
                            ]
                        ]);
                        if($existQuery->found_posts){
                            $existStatus = 'yes';
                        }
                    ?>
                        <span class="like-box" data-exists="<?php echo $existStatus; ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
                        </span>
                <?php the_content(); ?>
                </div>
            </div>
        </div>

        
        <?php 
            //Pick Up Related Programs Linked with Events
            $relatedPrograms = get_field('related_programs');
        ?>
        <?php if($relatedPrograms): ?>
            <hr class="section-break">
            <h1 class="headline headline--medium">Subject(s) Taught</h1>
            <ul class="link-list min-list">
        <?php
            foreach($relatedPrograms as $program){
        ?>
            <li>
                <a href="<?php echo get_the_permalink($program); ?>">
                    <?php echo get_the_title($program); ?>
                </a>
            </li> 
        <?php
            }
        ?>
        
        </ul>
        <?php endif; ?>
    </div>
<?php
    endwhile;
?>
    
<?php
    get_footer();
?>