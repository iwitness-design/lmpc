<div class='event'>
  <?php $this->template( 'widgets/widget-events-list/featured-image', [ 'event' => $event ] ); ?>

  <?php $this->template( 'widgets/widget-events-list/date-tag', [ 'event' => $event ] ); ?>

  <a href="<?php echo esc_url( $event->permalink) ?>">
    <h4 class='event-title'><?php echo esc_attr( $event->title ) ?></h4>
  </a>

  <?php $this->template( 'widgets/widget-events-list/details' ) ?>
</div>

