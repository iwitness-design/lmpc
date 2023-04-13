
import * as test from "@wordpress/block-editor"

console.log(test)
/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

import { getSpacingPresetCssVar } from "./util";


export default function save( { attributes: { height, width } } ) {
	return (
		<div
			{ ...useBlockProps.save( {
				style: {
					height: getSpacingPresetCssVar( height ),
					width: getSpacingPresetCssVar( width ),
				},
				'aria-hidden': true,
			} ) }
		/>
	);
}