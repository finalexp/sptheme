<?php

add_action('rest_api_init', 'spRegisterSearch');

function spRegisterSearch() {
	register_rest_route('sp/v1', 'search', array(
		'methods' => WP_REST_SERVER::READABLE,
		'callback' => 'spSearchResults',
	));

}

function spSearchResults($data) {
	$mainQuery = new WP_Query(array(
		'post_type' => array('post', 'page', 'skribent', 'kurs', 'evenemang', 'restaurang'),
		's' => sanitize_text_field($data['term'])
	));

	$results = array(
		'information' => array(),
		'skribent' => array(),
		'kurser' => array(),
		'evenemang' => array(),
		'restauranger' => array()
	);

	while($mainQuery->have_posts()) {
		$mainQuery->the_post();

		if (get_post_type() == 'post' OR get_post_type() == 'page') {
			array_push($results['information'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'postType' => get_post_type(),
				'authorName' => get_the_author()
		));

		}

		if (get_post_type() == 'skribent') {
			array_push($results['skribent'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'image' => get_the_post_thumbnail_url(0, 'skribentLandscape')
		));

		}

		if (get_post_type() == 'kurs') {
			array_push($results['kurser'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'id' => get_the_id()
		));

		}

		if (get_post_type() == 'evenemang') {
			$evenemangDatum = new DateTime(get_field('datum_for_evenemang'));
			$description = null;
			if (has_excerpt()) {
           		$description = get_the_excerpt();
          	} else {
            	$description = wp_trim_words(get_the_content(), 18);
          	}

			array_push($results['evenemang'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $evenemangDatum->format('M'),
				'day' => $evenemangDatum->format('d'),
				'description' => $description
		));

		}

		if (get_post_type() == 'restaurang') {
			array_push($results['restauranger'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink()
		));

		}
		
	}

	if ($results['kurser']) {

	$kurserMetaQuery = array('relation' => 'OR');

	foreach($results['kurser'] as $item) {
		array_push($kurserMetaQuery, array(
				'key' => 'relaterade_kurser',
				'compare' => 'LIKE',
				'value' => '"' . $item['id'] . '"'
			));

	}

	$kursRelationshipQuery = new WP_Query(array(
		'post_type' => array('skribent', 'evenemang'),
		'meta_query' => $kurserMetaQuery
	));

	while($kursRelationshipQuery->have_posts()) {
		$kursRelationshipQuery->the_post();

		if (get_post_type() == 'evenemang') {
			$evenemangDatum = new DateTime(get_field('datum_for_evenemang'));
			$description = null;
			if (has_excerpt()) {
           		$description = get_the_excerpt();
          	} else {
            	$description = wp_trim_words(get_the_content(), 18);
          	}

			array_push($results['evenemang'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'month' => $evenemangDatum->format('M'),
				'day' => $evenemangDatum->format('d'),
				'description' => $description
		));
		}


		if (get_post_type() == 'skribent') {
			array_push($results['skribent'], array(
				'title' => get_the_title(),
				'permalink' => get_the_permalink(),
				'image' => get_the_post_thumbnail_url(0, 'skribentLandscape')
		));

		}
	}

	$results['skribent'] = array_values(array_unique($results['skribent'], SORT_REGULAR));
	$results['evenemang'] = array_values(array_unique($results['evenemang'], SORT_REGULAR));



	}

	


	return $results;
}