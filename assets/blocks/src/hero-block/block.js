(function(wpI18n, wpBlocks, wpElement, wpEditor, wpComponents) {
  const { __ } = wpI18n;
  const { registerBlockType } = wpBlocks;
  const { RichText, InspectorControls, MediaUpload, PanelColorSettings } = wpEditor;
  const { PanelBody, PanelRow, RangeControl, ColorPalette, Button, ToggleControl, TextControl, SelectControl } = wpComponents;

  const HeroBannerLeft = (
    <svg
      version="1.1"
      id="Layer_1"
      xmlns="http://www.w3.org/2000/svg"
      x="0px"
      y="0px"
      width="230px"
      height="100px"
      viewBox="0 0 470 203"
      enable-background="new 0 0 470 203"
    >
      <rect
        x="1.5"
        y="1.5"
        fill="#FFFFFF"
        stroke="#7B8080"
        stroke-width="3"
        stroke-miterlimit="10"
        width="467"
        height="200"
      />
      <rect
        x="34.509"
        y="32.925"
        fill="#7B8080"
        width="54.924"
        height="9.828"
      />
      <rect
        x="34.509"
        y="56.144"
        fill="#7B8080"
        width="398.27"
        height="13.022"
      />
      <rect
        x="34.509"
        y="94.707"
        fill="#7B8080"
        width="398.27"
        height="7.617"
      />
      <rect
        x="34.509"
        y="114.361"
        fill="#7B8080"
        width="325.491"
        height="7.617"
      />
      <rect x="34.509" y="147" fill="#7B8080" width="72.491" height="26.333" />
      <text
        transform="matrix(1 0 0 1 52.7544 163.5)"
        fill="#FFFFFF"
        font-family="'MyriadPro-Regular'"
        font-size="12"
      >
        Button
      </text>
    </svg>
  );


  const areaHeroFulIsImage = sourceURL => {
    const imageExtension = ['jpg', 'jpeg', 'png', 'gif'];
    const fileExtension = sourceURL.split('.').pop();
    if (-1 < imageExtension.indexOf(fileExtension)) {
      return true;
    } else {
      return false;
    }
  };

  registerBlockType('gbblock/hero-block', {
    title: 'Hero Banner',
    icon: 'grid-view',
    category: 'area2071',

    attributes: {
      headlines: {
        type: 'string'
      },
      sublines: {
        type: 'string'
      },
      headlinesRight: {
        type: 'string'
      },
      sublinesRight: {
        type: 'string'
      },
      backgroundImage: {
        type: 'string'
      },
      alignBox: {
        type: 'string',
        default: 'hero-left'
      },
      subHeadSize: {
        type: 'number',
        default: '20'
      },
      headingSize: {
        type: 'number',
        default: '30'
      },
      introSize: {
        type: 'number'
      },
      hero_button_status: {
        type: 'boolean',
        default: false
      },
      hero_button_label: {
        type: 'string',
        default: 'Button'
      },
      hero_button_link: {
        type: 'string',
        default: '#'
      },
      hero_button_link_new_tab: {
        type: 'boolean',
        default: false
      },
      introColor: {
        type: 'boolean',
        default: true
      },
      hero_is_sticky: {
        type: 'string'  
      },
      language: {
        type: 'string',
        default: 'arealang_en'
      },         
    },

    edit(props) {
      const { setAttributes, attributes, className } = props;
      const {
        backgroundImage,
        headlines,
        sublines,
        headlinesRight,
        sublinesRight,
        hero_button_status,
        hero_button_label,
        hero_button_link,
        hero_button_link_new_tab,
        alignBox,
        subHeadSize,
        headingSize,
        introSize,
        introColor,
        hero_is_sticky,
        language,
      } = attributes;

      const subHeadStyle = {};
      subHeadSize && (subHeadStyle.fontSize = subHeadSize + 'px');

      const headingStyle = {};
      headingSize && (headingStyle.fontSize = headingSize + 'px');

      const bannerStyle = {};
      backgroundImage ?
        areaHeroFulIsImage(backgroundImage) &&
          (bannerStyle.backgroundImage = `url(${backgroundImage})`) :
        '';
     

      return [
        <InspectorControls>
          <PanelBody title={__('Background Setting', 'area2071-block')} initialOpen={false}>
            <PanelBody title={__('Background Image')} initialOpen={false}>
              <MediaUpload
                onSelect={backgroundImage =>
                  setAttributes({
                    backgroundImage: backgroundImage.url ?
                      backgroundImage.url :
                      ''
                  })
                }
                type="image"
                value={backgroundImage}
                render={({ open }) => (
                  <button className="button" onClick={open}>
                    {! backgroundImage ? (
                      __('Upload Banner')
                    ) : areaHeroFulIsImage(backgroundImage) ? (
                      <img src={backgroundImage} alt="Background Image" />
                    ) : ''}
                  </button>
                )}
              />
              {backgroundImage ? (
                <Button
                  className="button remove_hero_img_btn"
                  onClick={() => setAttributes({ backgroundImage: ''})}
                >
                  {__('Remove Background Image')}
                </Button>
              ) : null}
            </PanelBody>
          
          </PanelBody>          
          <PanelBody title={__('Layout Setting', 'area2071-block')} initialOpen={false}>
            <PanelRow>
              <ul className="layout-option full">
                
                <li className={'hero-center active'}>
                  {HeroBannerLeft}
                </li>
                
              </ul>
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label="Is Transparent Nevigation ?"
                help={hero_is_sticky ? true : false}
                checked={hero_is_sticky}
                onChange={(value) => setAttributes({hero_is_sticky: value})}
              />
            </PanelRow>
          </PanelBody>
          <PanelBody title={__('Button Setting', 'area2071-block')} initialOpen={false}>
            <ToggleControl
              label="Button Visibility"
              help={hero_button_status ? true : false}
              checked={hero_button_status}
              onChange={(value) => setAttributes({hero_button_status: value})}
            />
            {hero_button_status && (
                <PanelRow>
                  <label className="label">Label</label>
                  <TextControl
                      type="string"
                      value={hero_button_label}
                      onChange={(value) => setAttributes({hero_button_label: value})}
                  />
                </PanelRow>
              )
            }
            {hero_button_status && (
                <PanelRow>
                  <label className="label">Link</label>
                  <TextControl
                      type="string"
                      value={hero_button_link}
                      onChange={(value) => setAttributes({hero_button_link: value})}
                  />
                </PanelRow>
              )
            }  
            {hero_button_status && (
                <PanelRow>
                  <ToggleControl
                    label="Open in new tab"
                    help={hero_button_link_new_tab ? true : false}
                    checked={hero_button_link_new_tab}
                    onChange={(value) => setAttributes({hero_button_link_new_tab: value})}
                  />
                </PanelRow>
              )
            }
          </PanelBody>
          
        </InspectorControls>,
        <div className={className}>
          <div
            className={`hero-section content-block ${
              hero_is_sticky ? 'hero_is_sticky' : ''} ${language}`}
            style={bannerStyle}
          >
            
            <div className={`hero-content`} >
                
                <div className="container">
                  <div className="row">
                    <div className="col-md-10 left">
                      <div className="h3-wrap">
                        <RichText
                          tagName="h3"
                          keepPlaceholderOnFocus="true"
                          className="highlight--wrapping"
                          value={headlines}
                          style={headingStyle}
                          onChange={headlines =>
                            setAttributes({ headlines: headlines })
                          }
                          placeholder="Headline"
                        />
                      </div>
                      <div className="p-wrap">
                        <RichText
                          tagName="p"
                          keepPlaceholderOnFocus="true"
                          className="highlight--wrapping"
                          value={sublines}
                          style={headingStyle}
                          onChange={sublines =>
                            setAttributes({ sublines: sublines })
                          }
                          placeholder="Description Here"
                        />
                      </div>

                      {hero_button_status && (
                        <div className="button-wrap">
                          <a rel="noopener noreferrer" href={hero_button_link}>{hero_button_label}</a>
                        </div>
                      )}
                    </div>
                  </div>
                </div>
                
            </div>
          </div>
        </div>
      ];
    },

    save(props) {
      const { attributes, className } = props;
      const {
        headlines,
        sublines,
        headlinesRight,
        sublinesRight,
        hero_button_status,
        hero_button_label,
        hero_button_link,
        hero_button_link_new_tab,
        backgroundImage,
        alignBox,
        subHeadSize,
        headingSize,
        introSize,
        introColor,
        hero_is_sticky,
        language
      } = attributes;

      const subHeadStyle = {};
      subHeadSize && (subHeadStyle.fontSize = subHeadSize + 'px');

      const headingStyle = {};
      headingSize && (headingStyle.fontSize = headingSize + 'px');

      const bannerStyle = {};
      backgroundImage ?
        areaHeroFulIsImage(backgroundImage) &&
          (bannerStyle.backgroundImage = `url(${backgroundImage})`) :
        '';
      return (
        <div className={className}>
          <div
            className={`hero-section content-block`}
            style={bannerStyle}
          >
            <div
              className={`hero-content`}
            >
              <div className="container">
                <div className="row">
                       
                  <div className="col-md-10 left">
                    <div className="h1-wrap">
                      <RichText.Content
                        tagName="h3"
                        style={headingStyle}
                        className="highlight--wrapping"
                        value={headlines}
                      />
                    </div>
                    <div className="p-wrap">
                        <RichText.Content
                          tagName="p"
                          style={headingStyle}
                          className="highlight--wrapping"
                          value={sublines}
                        />
                      </div>
                    
                      <div className="button-wrap">
                        <a rel="noopener noreferrer" href={hero_button_link}>{hero_button_label}</a>
                      </div>
                  </div>

                
                </div>
              </div>
            </div>
          </div>
        </div>
      );
    }
  });
})(wp.i18n, wp.blocks, wp.element, wp.editor, wp.components);
