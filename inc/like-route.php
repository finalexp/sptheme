<?php

add_action('rest_api_init', 'spLikeRoutes');

function spLikeRoutes() {
	register_rest_route('sp/v1', 'manageLike', array(
		'methods' => 'POST',
		'callback' => 'createLike'
	));

	register_rest_route('sp/v1', 'manageLike', array(
		'methods' => 'DELETE',
		'callback' => 'deleteLike'
	));
}

function createLike($data) {
	if (is_user_logged_in()) {
	$skribent = sanitize_text_field($data['skribentId']);

	$existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'liked_skribent_id',
                'compare' => '=',
                'value' => $skribent
              )
            )
            ));


	if($existQuery->found_posts == 0 AND get_post_type($skribent) == 'skribent') {
		return wp_insert_post(array(
		'post_type' => 'like',
		'post_status' => 'publish',
		'post_title' => '2nd PHP test',
		'meta_input' => array(
			'liked_skribent_id' => $skribent
		)
	));
	} else {
		die("Ogiltigt skribent id.");
	}

	


	} else {
		die("Enbart inloggade användare kan gilla detta inlägg.");
	}

	
}

function deleteLike($data) {
	$likeId = sanitize_text_field($data['like']);
	if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
	wp_delete_post($likeId, true);
	return 'Grattis, gilla är borttaget.';
	} else {
	die("Du har inte tillåtelse att radera detta.");
}

}