/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';
import { createHigherOrderComponent } from '@wordpress/compose'
import {
  InspectorControls,
  useBlockEditContext,
  InspectorAdvancedControls
} from '@wordpress/block-editor'
import { 
  getBlockType, 
  hasBlockSupport, 
  getBlockDefaultClassName 
} from '@wordpress/blocks'
import {
  ToggleControl,
  TextControl,
  PanelBody
} from '@wordpress/components'

import classnames from 'classnames'


import AspectRatioDropdown from './aspect-ratio-dropdown';

function CustomEdit( props ) {
  const { attributes = {}, name } = props;

  const {
    useFeaturedImage
  } = attributes

  const blockType = getBlockType( name )

  const Component = blockType.edit

  const generatedClassName = hasBlockSupport( blockType, 'className', true )
		? getBlockDefaultClassName( name )
		: null;

  const className = classnames( 
    generatedClassName, 
    attributes.className,
    props.className
  )

  return (
    <Component {...props} context={{}} className={className} />
  )
}

const addCoverControls = createHigherOrderComponent( ( BlockEdit ) => {
  return ( props, ...rest ) => {
    if( props.name === 'core/cover' ) {
      console.log("Cover props", props, rest)

      const { attributes, setAttributes } = props

      const { useFeaturedImage, aspectRatio } = attributes

      const Component = useFeaturedImage ? BlockEdit : CustomEdit

      // console.log( Component.render() )

      return (
        <>
          <Component { ...props } />
          <InspectorControls>
            <PanelBody
            title={ __( "Aspect Ratio", "lmpc" )}
            initialOpen={false}
            >
              <label>{ __( "Cover aspect ratio", "lmpc" ) }</label>
              <AspectRatioDropdown 
                label={ __( "Aspect Ratio", "lmpc" ) }
                value={aspectRatio}
                onChange={ aspectRatio => {
                  setAttributes( { aspectRatio } )
                } }
              />
            </PanelBody>
          </InspectorControls>
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
      )
    }
    return <BlockEdit { ...props } />
  }
} )

export default addCoverControls