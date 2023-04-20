
wp.domReady(() => {

  wp.blocks.registerBlockStyle( 
    'core/button', 
    {
      name: "outline-button",
      label: "Outline"
    }
  )

  wp.blocks.registerBlockStyle( 
    'core/button', 
    {
      name: "small-button",
      label: "Small"
    }
  )

  wp.blocks.registerBlockStyle( 
    'core/button', 
    {
      name: "small-outline-button",
      label: "Small Outline"
    }
  )

  wp.blocks.registerBlockStyle( 
    'core/button', 
    {
      name: "large-button",
      label: "Large"
    }
  )

  wp.blocks.registerBlockStyle( 
    'core/button', 
    {
      name: "large-outline-button",
      label: "Large Outline"
    }
  )

  // default outline button has styles applied
  wp.blocks.unregisterBlockStyle( 'core/button', 'outline' )

  wp.richText.unregisterFormatType( 'core/link' ); // Disables link button in the toolbar
  wp.richText.unregisterFormatType( 'core/bold' );
  wp.richText.unregisterFormatType( 'core/italic' );
  wp.richText.unregisterFormatType( 'core/image' );
  wp.richText.unregisterFormatType( 'core/strikethrough' );
  wp.richText.unregisterFormatType( 'core/underline' );
  wp.richText.unregisterFormatType( 'core/text-color' );
  wp.richText.unregisterFormatType( 'core/subscript' );
  wp.richText.unregisterFormatType( 'core/superscript' );
  wp.richText.unregisterFormatType( 'core/keyboard' );
  wp.richText.unregisterFormatType( 'core/unknown' );
  wp.richText.unregisterFormatType( 'core/code' );
})




