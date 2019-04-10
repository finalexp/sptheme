<div class="post-item">
    <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
        
        <div class="metabox">
        <p>Skrivet av <?php the_author_posts_link(); ?> <?php the_time('n/j/Y'); ?> under kategori <?php echo get_the_category_list(', ')?></p>
        </div>
        
        <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Fortsätt läsa &raquo;</a></p>
        </div>
    </div>