<?php get_header();

while(have_posts()) {
    the_post();
    pageBanner(); ?>

  <div class="container container--narrow page-section">
<div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('evenemang'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Alla Evenemang</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>      
      <div class="generic-content"><?php the_content(); ?></div>

      <?php
        $relateradeKurser = get_field('relaterade_kurser');

        if ($relateradeKurser) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Relaterat till kurserna</h2>';
        echo '<ul class="link-list min-list">';
        foreach($relateradeKurser as $kurs) { ?>
          <li><a href="<?php echo get_the_permalink($kurs); ?>"><?php echo get_the_title($kurs); ?></a></li>
        <?php }
        echo '</ul>';
        }

        
      ?>

</div>
<?php
}

get_footer();

?>