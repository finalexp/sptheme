<?php

require get_theme_file_path('/inc/like-route.php');
require get_theme_file_path('/inc/search-route.php');

function sp_custom_rest() {

  register_rest_field('post', 'authorName', array(
    'get_callback' => function() {return get_the_author();;}
  ));

  register_rest_field('anteckning', 'userNoteCount', array(
    'get_callback' => function() {return count_user_posts(get_current_user_id(), 'anteckning'); }
  ));

}

add_action('rest_api_init', 'sp_custom_rest');

function pageBanner($args = NULL) {

    if (!$args['title']) {
      $args['title'] = get_the_title();
    }

    if (!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_rubrik');

    }

    if (!$args['photo']) {
      if (get_field('page_banner_bakgrundsbild')) {
        $args['photo'] = get_field('page_banner_bakgrundsbild')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }

    ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']?></p>
      </div>
    </div>  
  </div>
<?php }


function sp_files() {
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCaIr49ETqbKZoJLbaHPjHrd__ItmpZDs0', NULL, '1.0', true);
    wp_enqueue_script('main-sp-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('sp_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_localize_script('main-sp-js', 'spData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'sp_files');

function sp_features(){
    
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('skribentLandscape', 400, 260, true);
    add_image_size('skribentPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'sp_features');


function sp_adjust_queries($query) {
	if(!is_admin() AND is_post_type_archive('restaurang') AND $query->is_main_query()) {
    $query->set('posts_per_page', -1);
  }

  if(!is_admin() AND is_post_type_archive('kurs') AND $query->is_main_query()) {
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}

	if (!is_admin() AND is_post_type_archive('evenemang') AND $query->is_main_query()) {
		$today = date('Y/m/d');
		$query->set('meta_key', 'datum_for_evenemang');
		$query->set('orderby', 'datum_for_evenemang');
		$query->set('order', 'ASC');
		$query->set('meta_query', array(
              array(
                'key' => 'datum_for_evenemang',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
            ));
	}
}

add_action('pre_get_posts', 'sp_adjust_queries');

function spMapKey($api) {
  $api['key'] = 'AIzaSyCaIr49ETqbKZoJLbaHPjHrd__ItmpZDs0';
  return $api;
}

add_filter('acf/fields/google_map/api', 'spMapKey');


// Redirect subsrciber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
    wp_redirect(site_url('/'));
    exit;

  }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
  $ourCurrentUser = wp_get_current_user();

  if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      show_admin_bar(false);

  }
}

// Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
  return esc_url(site_url('/'));;
}

add_action('login_enqueue_scripts', 'ourLoginCSS' );

function ourLoginCSS() {
    wp_enqueue_style('sp_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

}

add_filter('login_headertitle', 'ourLoginTitle');

function OurLoginTitle() {
  return get_bloginfo('name'); 
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
  if($data['post_type'] == 'anteckning') {
    if(count_user_posts(get_current_user_id(), 'anteckning') > 99 AND !$postarr['ID']) {
      die("Du har nått max antal tillåtna anteckningar");
    }

    $data['post_content'] = sanitize_textarea_field($data['post_content']);
    $data['post_title'] = sanitize_text_field($data['post_title']);

  }

  if($data['post_type'] == 'anteckning' AND $data['post_status'] != 'trash') {
  $data['post_status'] = "private";

  }


  return $data;
}