export default function getSaveElement( element, block, attributes ) {
  if(block.name === 'core/cover' && attributes.clickable && attributes.clickableUrl) {
    const { children, ...props } = element.props

    const { aspectRatio } = attributes

    const style = aspectRatio ? {
      aspectRatio: aspectRatio,
      ...(props.style || {})
    } : (props.style || {})

    return (
      <div {...props} style={style}>
        { children }
        {
          <a 
            href={attributes.clickableUrl}
            style={{
              position: 'absolute',
              inset: '0',
              zIndex: 1
            }}
          ></a>
        }
      </div>
    )
  }

  return element;
}