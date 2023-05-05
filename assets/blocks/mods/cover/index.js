import { __ } from '@wordpress/i18n';
import { addFilter, applyFilters } from '@wordpress/hooks'
import {
  ToggleControl,
  TextControl
} from '@wordpress/components'
import {
  InspectorAdvancedControls
} from '@wordpress/block-editor'

function addCoverAttributes(settings, name) {
	if (typeof settings.attributes !== 'undefined') {
		if (name == 'core/cover') {
			settings.attributes = Object.assign(settings.attributes, {
				clickable: {
          type: 'boolean',
          default: false
        },
        clickableUrl: {
          type: 'string',
          default: ''
        }
			});
		}
	}
	return settings;
}
 
addFilter(
	'blocks.registerBlockType',
	'lmpc/cover-make-clickable',
	addCoverAttributes
);


function coverClickableControls( BlockEdit ) {
  return (props) => {
    const { attributes, setAttributes, isSelected } = props

    return (
      <>
        <BlockEdit { ...props } />
        {
          isSelected && props.name === 'core/cover' &&
          <>
            <InspectorAdvancedControls>
              <ToggleControl 
                label={ __("Make Cover Clickable", 'lmpc' ) }
                checked={ !!attributes.clickable }
                onChange={(clickable) => setAttributes({ clickable })}
              />

              {
                attributes.clickable &&
                <TextControl 
                  label={ __( "Url", 'lmpc' ) }
                  value={ attributes.clickableUrl }
                  onChange={(clickableUrl) => setAttributes({ clickableUrl })}
                />
              }
            </InspectorAdvancedControls>
          </>
        }
      </>
    )
  }
}

addFilter(
  'editor.BlockEdit',
  'lmpc/cover-clickable-controls',
  coverClickableControls
)

function getSaveElement( element, block, attributes ) {
  if(block.name === 'core/cover' && attributes.clickable && attributes.clickableUrl) {
    const { children, ...props } = element.props
    return (
      <div {...props}>
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

addFilter(
  'blocks.getSaveElement',
  'lmpc/cover-clickable-controls',
  getSaveElement
)