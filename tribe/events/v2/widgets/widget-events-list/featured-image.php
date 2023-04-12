
<?php
  $image_url = null;
  if( $event->thumbnail->exists ) {
    $image_url = esc_url( $event->thumbnail->full->url );
  }
  else {
    $image_url = get_stylesheet_directory_uri() . '/assets/img/default-logo.png';
  }
?>

<img class="event-image" src="<?php echo $image_url ?>" >

