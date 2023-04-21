
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
})




