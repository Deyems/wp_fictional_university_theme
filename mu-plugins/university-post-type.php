<?php

function university_post_types(){
    //Event Post type
    register_post_type('event', [
        'capability_type' => 'event',
        'map_meta_cap' => true,
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

    //Campus Post type
    register_post_type('campus', [
        'public' => true,
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => [
            'title',
            'editor',
            'excerpt',
        ],
        'rewrite' => [
            'slug' => 'campuses',
        ],
        'labels' => [
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus',
        ],
        'menu_icon' => 'dashicons-location-alt',
    ]);
    
    //Program post type
    register_post_type('program', [
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title'],
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
    
    //Register Professor Post type
    register_post_type('professor', [
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['editor', 'title', 'thumbnail'],
        'labels' => [
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor',
        ],
        'menu_icon' => 'dashicons-welcome-learn-more',
    ]);
    
    //Register NOTES Post type
    register_post_type('note', [
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'public' => false,
        'show_in_rest' => true,
        'supports' => ['editor', 'title'],
        'show_ui' => true,
        'labels' => [
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note',
        ],
        'menu_icon' => 'dashicons-welcome-write-blog',
    ]);

    //Register LIKE Post type
    register_post_type('like', [
        'public' => false,
        'supports' => ['title'],
        'show_ui' => true,
        'labels' => [
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like',
        ],
        'menu_icon' => 'dashicons-heart',
    ]);
}

add_action('init', 'university_post_types');
