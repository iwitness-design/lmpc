import { addFilter } from '@wordpress/hooks'
import { __ } from '@wordpress/i18n'
import {
  __experimentalToolsPanel as ToolsPanel,
  __experimentalToolsPanelItem as ToolsPanelItem
} from '@wordpress/components'
import {
  InspectorControls
} from '@wordpress/block-editor'



import { TaxonomyControls } from './taxonomy-controls'
import { getExcludedTaxonomies } from './utils'

addFilter(
  'editor.BlockEdit',
  'lmpc/add-taxonomy-controls',
  function( BlockEdit ) {

    return ( props ) => {
      const { attributes, setAttributes, isSelected } = props
      const { query } = attributes

      const updateQuery = ( newQuery ) =>
		    setAttributes( { query: { ...query, ...newQuery } } );

      const removeQueryParam = ( key ) => {
        const { [key]: _, ...newQuery } = query
        setAttributes( { query: newQuery })
      }
    
      return (
        <>
          <BlockEdit { ...props } />
          {
            isSelected && props.name === 'core/query' &&
            <InspectorControls>
              <ToolsPanel 
                label={ __( "Advanced Filters", "lmpc" ) }
                resetAll={ () => {
                  Object.keys(getExcludedTaxonomies( query )).forEach(removeQueryParam)
                } } 
              >
                <ToolsPanelItem 
                  label={ __( "Taxonomies", "lmpc" ) }
                  onDeselect={ () => {
                    Object.keys(getExcludedTaxonomies( query )).forEach(removeQueryParam)
                  } }
                  hasValue={ () => Object.keys( getExcludedTaxonomies( query ) ).length }
                >
                  <TaxonomyControls
                    query={ query }
                    onChange={ updateQuery }
                  />
                </ToolsPanelItem>
              </ToolsPanel>
            </InspectorControls>
          }
        </>
      )
    }
  }
)