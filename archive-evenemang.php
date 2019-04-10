<?php

get_header(); 
pageBanner(array(
  'title' => 'Alla evenemang',
  'subtitle' => 'Håll utkik efter nya evenemang!'
));
?>

<div class="container container--narrow page-section">    
   <?php
    while(have_posts()) {
        the_post();
        get_template_part('template-parts/content-evenemang');
     }
    echo paginate_links();
    ?>
<hr class="section-break">
    <p>Vill du se vilka evenmang vi haft under året? <a href="<?php echo site_url('/gamla-evenemang')?>">Kolla in våra gamla evenemang.</a></p>
    
    
</div>
    

<?php get_footer();

?>