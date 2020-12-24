<?php

function university_post_types(){
    register_post_type('event', [
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => [
            'title',
            'editor',
            'excerpt',
        ],
        'rewrite' => [
            'slug' => 'events',
        ],
        'labels' => [
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event',
        ],
        'menu_icon' => 'dashicons-calendar-alt',
    ]);

    register_post_type('program', [
        'public' => true,
        'show_in_rest' => true,
        'support' => 'editor, title',
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'programs',
        ],
        'labels' => [
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program',
        ],
        'menu_icon' => 'dashicons-edit-large',
    ]);

}

add_action('init', 'university_post_types');
