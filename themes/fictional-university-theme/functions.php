<?php
require get_theme_file_path('inc/search-route.php');

$filepath = dirname(dirname(dirname(__DIR__))).'/wp-config.php';

if(!file_exists($filepath)){
    echo "You need to add a key to this $filepath file";
}else{
    require "$filepath";
}

function university_files(){
    wp_enqueue_style('google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome_style', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key='.GOOGLE_MAP_API_KEY, NULL,'1.0', true);

    //Replacement for above files is implemented below - Use of Webpack to load all static files
    if(strstr($_SERVER['SERVER_NAME'], 'localhost')){
        wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL,'1.0', true);
    }else{
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL,'1.0', true);
        wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.ed9770639ccc474bda4e.js'), NULL,'1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.ed9770639ccc474bda4e.css'));
    }
    wp_localize_script('main-university-js', 'universityData', [
        'root_url' => get_site_url(),
    ]);
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

function add_author_name_to_json(){
    return get_the_author();
}

function university_custom_rest(){
    register_rest_field('post', 'author_name', [
        'get_callback' => 'add_author_name_to_json',
        'update_callback' => 'update_author_name_in_json',
    ]);
}

add_action('rest_api_init', 'university_custom_rest');
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
    $api['key'] = GOOGLE_MAP_API_KEY;
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');