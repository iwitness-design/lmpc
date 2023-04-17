<div class='event'>
  <?php $this->template( 'widgets/widget-events-list/featured-image', [ 'event' => $event ] ); ?>

  <?php $this->template( 'widgets/widget-events-list/date-tag', [ 'event' => $event ] ); ?>

  <a href="<?php echo esc_url( $event->permalink) ?>">
    <h6 class='event-title wp-block-heading'><strong><?php echo esc_attr( $event->title ) ?></strong></h6>
  </a>

  <?php $this->template( 'widgets/widget-events-list/details' ) ?>
</div>

