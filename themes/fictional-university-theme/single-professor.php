
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