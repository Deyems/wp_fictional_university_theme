<?php
    get_header();
    while(have_posts()):
        the_post();
?>
    <?php
        pageBanner([
            'title' => 'This is my title',
            //'photo' => 'https://images.freeimages.com/images/large-previews/ac0/splash-1531069.jpg'
        ]);
    ?>

    <div class="container container--narrow page-section">
    <?php
        $parentId = wp_get_post_parent_id(get_the_ID());
        if($parentId):
    ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_permalink($parentId) ?>">
                <i class="fa fa-home" aria-hidden="true"></i> Back to 
                <?php echo get_the_title($parentId) ?>
            </a>
            <span class="metabox__main"><?php the_title(); ?></span>
        </p>
        </div>
    
    <?php 
        endif;
        $testArray = get_pages(
            [
            'child_of' => get_the_ID()
            ]
        );

    if($parentId || $testArray): ?>
    <div class="page-links">
      <h2 class="page-links__title">
        <a href="<?php echo get_permalink($parentId) ?>">
            <?php echo get_the_title($parentId); ?>
        </a>
      </h2>
      <ul class="min-list">
        <?php
            if($parentId){
                $childOf = $parentId;
            }else{
                $childOf = get_the_ID();
            }
            wp_list_pages([
                'title_li' => NULL,
                'child_of' => $childOf,
                'sort_column' => 'menu_order',
            ]);
        ?>
      </ul>
    </div>
    <?php endif; ?>

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>

<?php
    endwhile;
    get_footer();
?>