<?php
    while(have_posts()):
        the_post();
?>
    <h3>This is a page NOT post</h3>
    <h2><?php the_title(); ?></h2>
    <p><?php the_content(); ?></p>
<?php
    endwhile;
?>