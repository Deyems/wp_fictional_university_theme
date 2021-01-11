<?php

function add_my_like_route(){
    register_rest_route('university/v1','likecount', [
        'methods' => 'POST',
        'callback' => 'create_like_count_results',
    ]);
    
    register_rest_route('university/v1','likecount', [
        'methods' => 'DELETE',
        'callback' => 'delete_like_count_results',
    ]);
}

function create_like_count_results(){
    wp_insert_post([
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => 'Our PHP create post here here',
        'post-content' => 'Testing content'
    ]);
}

function delete_like_count_results(){
    return 'Good deletion of like';
}

add_action('rest_api_init', 'add_my_like_route');
