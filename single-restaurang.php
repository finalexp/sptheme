<?php get_header();

while(have_posts()) {
    the_post();
    pageBanner(); ?>

  <div class="container container--narrow page-section">
<div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('restaurang'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Alla restauranger</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

      <div class="generic-content"><?php the_content(); ?></div>

      <?php 
        $karta = get_field('karta');
      ?>

      <div class="acf-map">

    <div class="marker" data-lat="<?php echo $karta['lat'] ?>" data-lng="<?php echo $karta['lng']; ?>">
      <h3><a><?php the_title();?></a></h3>
      <?php echo get_field('adress'); ?>
    </div>
  </div>


      <?php

            $relateradeFörfattare = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'forfattare',
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

          if ($relateradeFörfattare->have_posts()) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Författare inom ' . get_the_title() . ':</h2>';

          echo '<ul class="professor-cards">';
          while($relateradeFörfattare->have_posts()) {
            $relateradeFörfattare->the_post(); ?>
            <li class="professor-card__list-item">
              <a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('forfattareLandscape') ?>">
                <span class="professor-card__name"><?php the_title(); ?></span>
              </a>
            </li>

            <?php }
            echo '</ul>';
            }

            wp_reset_postdata();

          
          ?>

</div>
<?php
}

get_footer();

?>