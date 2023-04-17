
import * as test from "@wordpress/block-editor"

console.log(test)
/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

import { getSpacingPresetCssVar } from "./util";


export default function save( { attributes } ) {
  const { width, height } = attributes
  const blockProps = useBlockProps.save(
    { 
      style: {
        height: getSpacingPresetCssVar( height ),
        width: getSpacingPresetCssVar( width ),
      },
      'aria-hidden': true
    }
  )

  return (
		<div { ...blockProps } />
	);
}