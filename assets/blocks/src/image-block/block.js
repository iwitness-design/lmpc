const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { InspectorControls, MediaUpload } = wp.blockEditor;
const { Button, PanelBody, PanelRow, ToggleControl, TextControl, Toolbar,} = wp.components;
const { serverSideRender: ServerSideRender } = wp;
import { imageIcon } from "../icons";
class gbblockImage extends Component {

  render() {
    const { attributes, setAttributes } = this.props;
    const { gbblockImage, caption } = attributes;

    return (
      <Fragment>
        <InspectorControls>
          <div className="gbblock-inspector-setings-wrapper">
            <PanelBody title={__("Block Setting", "gbblock")} initialOpen="true">
              <PanelRow>
                <div className="textarea-div">
                  <label>Caption</label>
                  <textarea
                    value={caption}
                    placeholder="Caption"
                    onChange={(event) => {
                      setAttributes({ caption: event.target.value });
                    }}
                  />
                </div>
              </PanelRow>
            </PanelBody>
          </div>
        </InspectorControls>
        <div className="gbblock-image-wrap editor-page-image">
          <MediaUpload
            onSelect={(value) =>
              setAttributes({ gbblockImage: value.sizes.full.url })
            }
            type="image"
            value={gbblockImage}
            render={({ open }) => (
              <Button
                className={
                  !gbblockImage
                    ? "image-btn button button-large upload-btn"
                    : "image-btn button button-large edit-img-btn"
                }
                onClick={open}
              >
                {!gbblockImage
                  ? __("Upload Image", "gbblock")
                  : __("Edit Image", "gbblock")}
              </Button>
            )}
          />
           {
            gbblockImage && (
              <Button
                className="remove-image"
                onClick={() => setAttributes({ gbblockImage: "" })}
              >
                <span className="dashicons dashicons-no-alt"></span>
              </Button>
            )
          }
          <div className="gbblock-image-wrap">

          </div>
          <ServerSideRender
            block="gbblock/image"
            attributes={attributes}
          />
         
        </div>
      </Fragment>
    );
  }
}

const allAttr = {
  gbblockImage: {
    type: 'string',
  },
  caption: {
    type: 'string',
  },
};


registerBlockType("gbblock/image", {
	title: __("AMG - Image"),
	icon: imageIcon,
	category: "gbblock-blocks",
	keywords: [__("Image"), __("gbblock Image"), __("media")],
	attributes: allAttr,
	edit: gbblockImage,
	save() {
		return null;
	},
});

