<?php

function create_new_search_route(){
    //Namespace, route, Array of options for endpoint
    register_rest_route('university/v1', 'search', [
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'university_search_results',
    ]);
}

function university_search_results($data){
    $mainQuery = new WP_Query([
        'post_type' => ['professor', 'campus', 'page', 'post', 'program', 'event'],
        's' => sanitize_text_field($data['term']),
    ]);

    //generalInfo - house posts and pages
    $results = [
        'generalInfo' => [],
        'professors' => [],
        'programs' => [],
        'events' => [],
        'campuses' => []
    ];
    
    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        if(get_post_type() == 'page' || get_post_type() == 'post'){
            array_push($results['generalInfo'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
        if(get_post_type() == 'professor'){
            array_push($results['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
        if(get_post_type() == 'program'){
            array_push($results['programs'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
        if(get_post_type() == 'campus'){
            array_push($results['campuses'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
        if(get_post_type() == 'event'){
            array_push($results['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
    }

    return $results;
}

add_action('rest_api_init', 'create_new_search_route');