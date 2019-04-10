<?php

get_header();
pageBanner(array(
    'title' => 'Gamla evenemang',
    'subtitle' => 'Här kan du se alla evenemang som varit.'
)); ?>
    
<div class="container container--narrow page-section">    
   <?php

    $today = date('Y/m/d');
          $pastEvenemang = new WP_Query(array(
            'paged' => get_query_var('paged', 1),
            'post_type' => 'evenemang',
            'meta_key' => 'datum_for_evenemang',
            'orderby' => 'datum_for_evenemang',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'datum_for_evenemang',
                'compare' => '<',
                'value' => $today,
                'type' => 'numeric'
              )
            )
          ));

    while($pastEvenemang->have_posts()) {
        $pastEvenemang->the_post();
        get_template_part('template-parts/content-evenemang');
    
    
 }
    echo paginate_links(array(
      'total' => $pastEvenemang->max_num_pages
    ));
    ?>
    
    
</div>
    

<?php get_footer();

?>