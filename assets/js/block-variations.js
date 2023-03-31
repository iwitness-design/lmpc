
console.log(wp, wp.blocks, wp.domReady)


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