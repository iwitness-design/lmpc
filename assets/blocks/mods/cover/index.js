/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks'
import { createHigherOrderComponent } from '@wordpress/compose'
import {
  ToggleControl,
  TextControl,
  PanelBody
} from '@wordpress/components'
import {
  InspectorAdvancedControls, 
  InspectorControls
} from '@wordpress/block-editor'
import { select } from '@wordpress/data'
import { 
  getBlockType
} from '@wordpress/blocks'

/**
 * Internal dependencies
 */
import AspectRatioDropdown from './aspect-ratio-dropdown';
import getSaveElement from './save';
import addCoverControls from './edit';

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
        },
        aspectRatio: {
          type: 'string',
          default: '3 / 2'
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


addFilter(
  'editor.BlockEdit',
  'lmpc/cover-clickable-controls',
  addCoverControls
)

addFilter(
  'blocks.getSaveElement',
  'lmpc/cover-clickable-controls',
  getSaveElement
)