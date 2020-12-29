<?php

function university_files(){
    wp_enqueue_style('google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome_style', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    //Replacement for above files is implemented below - Use of Webpack to load all static files
    if(strstr($_SERVER['SERVER_NAME'], 'localhost')){
        wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL,'1.0', true);
    }else{
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL,'1.0', true);
        wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL,'1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));
    }
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerTwoLocation', 'Foot Location Two');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme','university_features');

function university_adjust_queries($query){
    if(!is_admin() && is_post_type_archive('program') && is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() && is_post_type_archive('event') && is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', [
            [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric',
            ]
        ]);
    }
}

add_action('pre_get_posts', 'university_adjust_queries');
?>

<?php
    function pageBanner(array $args = NULL){
        if(!$args['title']){
            $args['title'] = get_the_title();
        }
        if(!$args['subtitle']){
            $args['subtitle'] = get_field('page_banner_subtitle');
        }
        if(!$args['photo']){
            if(get_field('page_banner_background_image') && !is_archive() && !is_home() ){
                $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
            }else{
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(
            <?php 
                echo $args['photo'];
            ?>) ">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?php 
}

function universityMapKey(array $api){
    $api['key'] = "AIzaSyCc6j8riL0L_1O_otOp3if6pxvQoq_EI78";
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');