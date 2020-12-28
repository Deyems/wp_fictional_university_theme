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
                    <?php echo get_post_type_archive_link('event'); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    Event Home
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
            //Pick Up Related Programs Linked with Events
            $relatedPrograms = get_field('related_programs');
        ?>
        <?php if($relatedPrograms): ?>
            <hr class="section-break">
            <h1 class="headline headline--medium">Related Program(s)</h1>
            <ul class="link-list min-list">
                <?php
                    foreach($relatedPrograms as $program){
                ?>
                    <li>
                        <a href="<?php echo get_the_permalink($program); ?>">
                        <?php echo get_the_title($program); ?></a>
                    </li>
                <?php
                    }
                ?>
            </ul>
        <?php endif; ?>
    </div>
<?php
    endwhile;
    get_footer();
?>