<?php 

if (!is_user_logged_in()) {
  wp_redirect(esc_url(site_url('/')));
  exit;

}

get_header();

while(have_posts()) {
    the_post();
    pageBanner(); ?>

  <div class="container container--narrow page-section">
    <div class="create-note">
      <h2 class="headline headline--medium">Skapa ny anteckning</h2>
      <input class="new-note-title" placeholder="Titel">
      <textarea class="new-note-body" placeholder="Din anteckning här..."></textarea>
        <span class="submit-note">Skapa anteckning</span>
        <span class="note-limit-message">Du har nått max antal anteckningar: Radera en anteckning för att göra rum för en ny.</span>
    </div>
    <ul class="min-list link-list" id="mina-anteckningar">
      <?php
      $userNotes = new WP_Query(array(
        'post_type' => 'anteckning',
        'posts_per_page' => -1,
        'author' => get_current_user_id()
      ));

      while($userNotes->have_posts()) {
        $userNotes->the_post(); ?>
        <li data-id="<?php the_ID(); ?>">
          <input readonly class="note-title-field" value="<?php echo str_replace('Privat: ', '', esc_attr(get_the_title())); ?>">
          <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Redigera</span>
          <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Ta bort</span>
         <textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
          <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Spara</span>
        </li>
      <?php }

       ?>
    </ul>

  </div>


<?php }

  get_footer();

?>