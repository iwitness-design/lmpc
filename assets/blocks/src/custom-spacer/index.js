import metadata from './block.json'
import { registerBlockType } from "@wordpress/blocks"


import Edit from './edit'
import Save from './save'

registerBlockType(metadata.name, {
  ...metadata,
  edit: Edit,
  save: Save
})