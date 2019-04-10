<?php get_header();

while(have_posts()) {
    the_post();
    pageBanner(); ?>

  <div class="container container--narrow page-section">
<div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('kurs'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Alla kurser</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>     

      <div class="generic-content"><?php the_field('main_body_content'); ?></div>

      <?php

            $relateradeSkribent = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'skribent',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'relaterade_kurser',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
              )
            )
          ));

          if ($relateradeSkribent->have_posts()) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--small">Skribenter inom ' . get_the_title() . ':</h2>';

          echo '<ul class="professor-cards">';
          while($relateradeSkribent->have_posts()) {
            $relateradeSkribent->the_post(); ?>
            <li class="professor-card__list-item">
              <a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('skribentLandscape') ?>">
                <span class="professor-card__name"><?php the_title(); ?></span>
              </a>
            </li>

            <?php }
            echo '</ul>';
            }

            wp_reset_postdata();


          $today = date('Y/m/d');
          $homepageEvenemang = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'evenemang',
            'meta_key' => 'datum_for_evenemang',
            'orderby' => 'datum_for_evenemang',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'datum_for_evenemang',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ),
              array(
                'key' => 'relaterade_kurser',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
              )
            )
          ));

          if ($homepageEvenemang->have_posts()) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Kommande evenemang inom ' . get_the_title() . ':</h2>';

          while($homepageEvenemang->have_posts()) {
            $homepageEvenemang->the_post();
              get_template_part('template-parts/content-event');
             }
            }

          
          ?>

</div>
<?php
}

get_footer();

?>