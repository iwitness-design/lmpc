import React from "react";
import Select from "react-select";

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;
const {
	PanelBody,
	RangeControl,
	ToggleControl,
	SelectControl,
	Button,
	TextControl,
} = wp.components;
const { InspectorControls, RichText, MediaUpload } = wp.blockEditor;

import { postIcon } from "../icons";

class GbBlockedit extends Component {
	constructor() {
		super(...arguments);
		this.state = {
			categoryLists: false,
			postLists: false,
			callPostApi: false,
		};
	}

	componentDidMount() {
		// Fetch all category.
		wp.apiFetch({ path: "/gbblock_api/request/get_terms" }).then((terms) => {
			let categoryOption = [];
			categoryOption.push({
				label: __("Select Category"),
				value: __(0),
			});
			terms.forEach(function (item, index) {
				categoryOption.push({
					label: __(item.title),
					value: __(item.id),
				});
			});

			setTimeout(
				function () {
					this.setState({
						categoryLists: categoryOption,
					});
				}.bind(this),
				600
			);
		});

		this.callAPI();
	}

	componentDidUpdate() {
		const { callPostApi } = this.state;
		if (callPostApi === true) {
			this.setState({ callPostApi: false });
			this.callAPI();
		}
	}

	callAPI() {
		const { switcher } = this.state;

		const { attributes, setAttributes } = this.props;
		const { categoryItem } = attributes;
		// Fetch post by category.
		wp.apiFetch({
			path: `/gbblock_api/request/get_posts/?cat_id=${categoryItem}`,
		}).then((terms) => {
			let postOption = [];
			postOption.push({
				label: __("Select Post"),
				value: __(0),
			});
			terms.forEach(function (item, index) {
				postOption.push({
					label: __(item.title),
					value: __(item.id),
				});
			});

			setTimeout(
				function () {
					this.setState({
						postLists: postOption,
					});
				}.bind(this),
				600
			);
		});
	}

	render() {
		const { attributes, setAttributes } = this.props;
		const {
			latestArticle,
			displayCategory,
			displayDate,
			categoryItem,
			postItem,
			selectedOption,
			staticContent,
			singlePostCat,
			singlePostTitle,
			singlePostDesc,
			singlePostDate,
			singlePostImage,
			singlePostLink,
		} = attributes;
		const { categoryLists, postLists } = this.state;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={__("Block Setting")} initialOpen={true}>
						<ToggleControl
							label={__("Show category on post?")}
							checked={displayCategory}
							onChange={(displayCategory) => setAttributes({ displayCategory })}
						/>
						<ToggleControl
							label={__("Show date on post?")}
							checked={displayDate}
							onChange={(displayDate) => setAttributes({ displayDate })}
						/>
						<ToggleControl
							label={__("Display Latest Article?")}
							checked={latestArticle}
							onChange={(latestArticle) => {
								setAttributes({ latestArticle: latestArticle });
								setAttributes({ categoryItem: "0" });
								setAttributes({ staticContent: false });
								setAttributes({ selectedOption: null });
								setAttributes({ postItem: "" });
							}}
						/>
						{!latestArticle && (
							<Fragment>
								<ToggleControl
									label={__("Add static article?")}
									checked={staticContent}
									onChange={(staticContent) => {
										setAttributes({ staticContent: staticContent });
										setAttributes({ categoryItem: "0" });
										setAttributes({ selectedOption: null });
										setAttributes({ postItem: "" });
									}}
								/>
								{!staticContent && (
									<Fragment>
										<SelectControl
											//  label={__('Select Parent Page')}
											value={categoryItem}
											options={categoryLists}
											onChange={(value) => {
												setAttributes({ categoryItem: value });
												setAttributes({ selectedOption: null });
												setAttributes({ postItem: "" });
												setTimeout(
													function () {
														this.setState({
															callPostApi: true,
														});
													}.bind(this),
													600
												);
											}}
										/>
										<div class="gbblock-react-select">
											<Select
												name="select-two"
												isClearable={false}
												isSearchable={true}
												label={`Select Category`}
												value={JSON.parse(selectedOption)}
												onChange={(newValue) => {
													//console.log(newValue.value);
													setAttributes({
														selectedOption: JSON.stringify(newValue),
													});
													setAttributes({
														postItem:
															"" !== newValue.value
																? newValue.value.toString()
																: newValue.value,
													});
												}}
												// onChange={(newValue) => setAttributes({ postItem: newValue })}
												options={postLists}
											/>
										</div>
									</Fragment>
								)}
							</Fragment>
						)}
					</PanelBody>
				</InspectorControls>

				<Fragment>
					{(latestArticle ||
						("undefined" !== typeof categoryItem &&
							"" !== categoryItem &&
							"0" !== categoryItem &&
							"undefined" !== typeof postItem &&
							"" !== postItem)) && (
						<div className="single-article-block-wrap">
							<ServerSideRender
								block="gbblock/single-article"
								attributes={{
									displayCategory: displayCategory,
									displayDate: displayDate,
									categoryItem: categoryItem,
									latestArticle: latestArticle,
									postItem: postItem,
									selectedOption: selectedOption,
									staticContent: staticContent,
									singlePostCat: singlePostCat,
									singlePostTitle: singlePostTitle,
									singlePostDesc: singlePostDesc,
									singlePostDate: singlePostDate,
									singlePostImage: singlePostImage,
									singlePostLink: singlePostLink,
								}}
							/>
						</div>
					)}
					{staticContent && (
						<div class="single-post-wrapper">
							<div class="single-post-main" id="static-post">
								<div class="image-wrapper">
									<MediaUpload
										className="editor-image"
										onSelect={(editorImage) => {
											const ImagePath = editorImage.sizes.full.url
												? editorImage.sizes.full.url
												: "";
											setAttributes({
												singlePostImage: ImagePath,
											});
										}}
										type="image"
										value={singlePostImage}
										render={({ open }) => (
											<Button
												className={
													!singlePostImage
														? "image-btn button button-large upload-btn"
														: "image-btn button button-large edit-img-btn"
												}
												onClick={open}
											>
												{!singlePostImage
													? __("Upload Image", "dsf")
													: __("Edit Image", "dsf")}
											</Button>
										)}
									/>
									<div className="image-wrap">
										{singlePostImage && (
											<div className="editor-image-wrap">
												<img
													src={singlePostImage}
													alt="Thumbnail Image"
													width="815"
													height="458"
													loading="lazy"
												/>
												<Button
													className="image-btn button button-large remove-image"
													onClick={() => {
														setAttributes({
															singlePostImage: "",
														});
													}}
												>
													Remove Image
												</Button>
											</div>
										)}
									</div>
								</div>
								<div class="content-wrapper">
									{displayCategory && (
										<RichText
											className="section-category-name"
											value={singlePostCat}
											onChange={(singlePostCat) =>
												setAttributes({ singlePostCat: singlePostCat })
											}
											tagName="div"
											placeholder="Enter Category"
										/>
									)}
									<RichText
										className="post-title"
										value={singlePostTitle}
										onChange={(singlePostTitle) =>
											setAttributes({ singlePostTitle: singlePostTitle })
										}
										tagName="h3"
										placeholder="Enter Title"
									/>
									<RichText
										className="post-desc"
										value={singlePostDesc}
										onChange={(singlePostDesc) =>
											setAttributes({ singlePostDesc: singlePostDesc })
										}
										tagName="div"
										placeholder="Enter Description"
									/>
									{displayDate && (
										<RichText
											className="post-date"
											value={singlePostDate}
											onChange={(singlePostDate) =>
												setAttributes({ singlePostDate: singlePostDate })
											}
											tagName="p"
											placeholder="Enter Date"
										/>
									)}
									<TextControl
										type="text"
										value={singlePostLink}
										onChange={(singlePostLink) =>
											setAttributes({ singlePostLink: singlePostLink })
										}
										placeholder="Enter absolute link"
									/>
								</div>
							</div>
						</div>
					)}
				</Fragment>
			</Fragment>
		);
	}
}

const attr = {
	latestArticle: {
		type: "boolean",
		default: true,
	},
	displayCategory: {
		type: "boolean",
		default: true,
	},
	displayDate: {
		type: "boolean",
		default: false,
	},
	staticContent: {
		type: "boolean",
		default: false,
	},
	categoryItem: {
		type: "string",
		default: "0",
	},
	postItem: {
		type: "string",
		default: "",
	},
	singlePostCat: {
		type: "string",
		default: "",
	},
	singlePostTitle: {
		type: "string",
		default: "",
	},
	singlePostDesc: {
		type: "string",
		default: "",
	},
	singlePostDate: {
		type: "string",
		default: "",
	},
	singlePostImage: {
		type: "string",
		default: "",
	},
	selectedOption: {
		type: "string",
		default: null,
	},
	singlePostLink: {
		type: "string",
		default: null,
	},
};

registerBlockType("gbblock/latest-message", {
	title: __("Latest Message"),
	icon: postIcon,
	category: "gbblock-blocks",
	attributes: attr,
	edit: GbBlockedit,
	save() {
		return null;
	},
});
