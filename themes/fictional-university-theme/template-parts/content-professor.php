<div class="post-item">
    <ul class='professor-cards'>
        <li class="professor-card__listitem">
            <a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="<?php the_title(); ?>">
                <span class="professor-card__name"><?php the_title(); ?></span>
            </a>
        </li>
    </ul>
</div>