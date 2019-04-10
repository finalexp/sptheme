<!DOCTYPE html>
<html <?php language_attributes(); ?>></html>
 <head>
     <meta charset="<?php bloginfo('charset'); ?>">
     <meta name="viewport" content="width=device-width", initial-scale="1">
    <?php wp_head(); ?>

    </head>
    
 <body <?php body_class(); ?>>
       <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left"><a href="<?php echo esc_url(site_url())?>"><strong>Swedish</strong> Paleo</a></h1>
      <a href="<?php echo esc_url(site_url('/sok')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">
         <ul>
            <li <?php if (is_page('om-oss') or wp_get_post_parent_id(0) == 12) echo 'class="current-menu-item"' ?>><a href="<?php echo esc_url(site_url('/om-oss'))?>">Om oss</a></li>
            <li <?php if (get_post_type() == 'kurs') echo 'class="current-menu-item"'  ?>><a href="<?php echo get_post_type_archive_link('kurs') ?>">Kurser</a></li>

            <li <?php if (get_post_type() == 'evenemang' OR is_page('gamla-evenemang')) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('evenemang'); ?>">Evenemang</a></li>
            <li <?php if (get_post_type() == 'restaurang') echo 'class="current-menu-item"' ?>><a href="<?php echo get_post_type_archive_link('restaurang'); ?>">Restauranger</a></li>
            <li <?php if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>><a href="<?php echo esc_url(site_url('/blog')); ?>">Blogg</a></li>
          </ul> 
        </nav>
        <div class="site-header__util">
          <?php if(is_user_logged_in()) { ?>
            <a href="<?php echo esc_url(site_url('/mina-anteckningar')); ?>" class="btn btn--small btn--orange float-left push-right">Mina anteckningar</a>
            <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">
            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
            <span class="btn__text">Logga ut</span>
            </a>

          <?php } else { ?>
            <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Logga in</a>
          <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Bli medlem</a>
            <?php } ?>
          
          <a href="<?php echo esc_url(site_url('/sok')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </header>