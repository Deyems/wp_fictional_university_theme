<?php
require get_theme_file_path('inc/search-route.php');
require get_theme_file_path('inc/like-route.php');

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
        wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.b458fe660cd1bee592cd.js'), NULL,'1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.b458fe660cd1bee592cd.css'));
    }
    wp_localize_script('main-university-js', 'universityData', [
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest'),
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

function add_note_count_to_json(){
    return count_user_posts(get_current_user_id(), 'note');
}

function university_custom_rest(){
    register_rest_field('post', 'author_name', [
        'get_callback' => 'add_author_name_to_json',
    ]);
    register_rest_field('note', 'user_note_count', [
        'get_callback' => 'add_note_count_to_json',
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

add_action('admin_init', 'redirectSubsToFrontPage');
define('ROLE_TO_CHECK','subscriber');

//Redirect Subscribers to HomePage

function redirectSubsToFrontPage(){
    $current_user_sub = wp_get_current_user();
    if(count($current_user_sub->roles) == 1 &&
    $current_user_sub->roles[0] == ROLE_TO_CHECK
    ){
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'removeAdminBar');

//Remove The Admin Bar at the TOP
function removeAdminBar(){
    $current_user_sub = wp_get_current_user();
    if(count($current_user_sub->roles) == 1 &&
    $current_user_sub->roles[0] == ROLE_TO_CHECK
    ){
        show_admin_bar(false);
    }
}

add_filter('login_headerurl', 'my_header_url');

function my_header_url(){
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'our_login_css');

function our_login_css(){
    wp_enqueue_style('google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.b458fe660cd1bee592cd.css'));
}

function my_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

//Force Note Posts to Be Private
add_filter('wp_insert_post_data', 'make_note_private', 10, 2);

function make_note_private($data, $postarr){
    if($data['post_type'] == 'note'){
        if(count_user_posts(get_current_user_id(),'note') > 3 && !$postarr['ID']){
            die('You have reached your limit');
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
        $data['post_status'] = "private";
    }
    return $data;
}