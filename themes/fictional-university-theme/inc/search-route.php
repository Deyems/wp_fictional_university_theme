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
                'type' => get_post_type(),
                'author_name' => get_author_name(),
            ]);
        }
        if(get_post_type() == 'professor'){
            array_push($results['professors'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0,'professorLandscape'),
            ]);
        }
        if(get_post_type() == 'program'){
            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses){
                foreach($relatedCampuses as $campus){
                    array_push($results['campuses'], [
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus),
                    ]);
                }
            }

            array_push($results['programs'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_ID(),
            ]);
        }
        if(get_post_type() == 'campus'){
            array_push($results['campuses'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ]);
        }
        if(get_post_type() == 'event'){
            $event_date = new DateTime(get_field('event_date'));
            if(has_excerpt()):
                $description =  get_the_excerpt();
                else:
                $description = wp_trim_words(get_the_content(), 13);
            endif;

            array_push($results['events'], [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $event_date->format('M'),
                'day' => $event_date->format('d'),
                'description' => $description,
            ]);
        }
    }

    if($results['programs']){
        $programsMetaQuery = ['relation' => 'OR'];
        foreach($results['programs'] as $item){
            array_push($programsMetaQuery, [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'. $item['id'] . '"',
            ]);
        }

        $programRelationshipQuery = new WP_Query([
            'post_type' => ['professor', 'event'],
            'meta_query' => $programsMetaQuery,
        ]);
    
        while($programRelationshipQuery->have_posts()){
            $programRelationshipQuery->the_post();
            
            if(get_post_type() == 'professor'){
                array_push($results['professors'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0,'professorLandscape'),
                ]);
            }

            if(get_post_type() == 'event'){
                $event_date = new DateTime(get_field('event_date'));
                if(has_excerpt()):
                    $description =  get_the_excerpt();
                    else:
                    $description = wp_trim_words(get_the_content(), 13);
                endif;
    
                array_push($results['events'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $event_date->format('M'),
                    'day' => $event_date->format('d'),
                    'description' => $description,
                ]);
            }

        }
    
        $results['professors'] = array_unique($results['professors'], SORT_REGULAR);
        $results['events'] = array_unique($results['events'], SORT_REGULAR);
    }

    return $results;
}

add_action('rest_api_init', 'create_new_search_route');