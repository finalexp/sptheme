<?php

get_header(); 
pageBanner(array(
  'title' => 'Hitta restauranger',
  'subtitle' => 'Här hittar du rekommenderade restauranger'
));?>

<div class="container container--narrow page-section">    
   
<div class="acf-map">
   <?php
    while(have_posts()) {
        the_post();
        $karta = get_field('karta'); 
        ?>

    <div class="marker" data-lat="<?php echo $karta['lat'] ?>" data-lng="<?php echo $karta['lng']; ?>">
      <h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
      <?php echo get_field('adress'); ?>
    </div>

    
    <?php } ?>

</div>
    
    
</div>
    

<?php get_footer();

?>