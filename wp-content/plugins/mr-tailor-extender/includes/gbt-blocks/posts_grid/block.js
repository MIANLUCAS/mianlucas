( function( blocks, components, editor, i18n, element ) {

	const el = element.createElement;

	/* Blocks */
	const registerBlockType = blocks.registerBlockType;

	const {
		TextControl,
		RadioControl,
		SelectControl,
		ToggleControl,
		RangeControl,
		SVG,
		Path,
	} = wp.components;

	const {
		InspectorControls
	} = wp.blockEditor;

	const apiFetch = wp.apiFetch;

	/* Register Block */
	registerBlockType( 'getbowtied/mt-posts-grid', {
		title: i18n.__( 'Posts Grid', 'mrtailor-extender' ),
		icon: el( SVG, { xmlns:'http://www.w3.org/2000/svg', viewBox:'0 0 24 24' },
				el( Path, { d:'M4 5v13h17V5H4zm10 2v3.5h-3V7h3zM6 7h3v3.5H6V7zm0 9v-3.5h3V16H6zm5 0v-3.5h3V16h-3zm8 0h-3v-3.5h3V16zm-3-5.5V7h3v3.5h-3z' } )
			),
		category: 'mrtailor',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			/* posts source */
			result: {
				type: 'array',
				default: [],
			},
			queryPosts: {
				type: 'string',
				default: '',
			},
			queryPostsLast: {
				type: 'string',
				default: '',
			},
			/* loader */
			isLoading: {
				type: 'bool',
				default: false,
			},
			/* Display by category */
			categoriesIDs: {
				type: 'string',
				default: ',',
			},
			categoriesSavedIDs: {
				type: 'string',
				default: '',
			},
			/* First Load */
			firstLoad: {
				type: 'boolean',
				default: true
			},
			/* Number of Posts */
			number: {
				type: 'number',
				default: '12'
			},
			/* Columns */
			columns: {
				type: 'number',
				default: '3'
			},
			/* Orderby */
			orderby: {
				type: 'string',
				default: 'date_desc'
			},
		},

		edit: function( props ) {

			let attributes = props.attributes;
			let className  = props.className;

			attributes.doneFirstLoad 		= attributes.doneFirstLoad || false;
			attributes.categoryOptions 		= attributes.categoryOptions || [];
			attributes.doneFirstPostsLoad 	= attributes.doneFirstPostsLoad || false;
			attributes.result 				= attributes.result || [];

			//==============================================================================
			//	Helper functions
			//==============================================================================

			function _sortCategories( index, arr, newarr = [], level = 0) {
				for ( let i = 0; i < arr.length; i++ ) {
					if ( arr[i].parent == index) {
						arr[i].level = level;
						newarr.push(arr[i]);
						_sortCategories(arr[i].value, arr, newarr, level + 1 );
					}
				}

				return newarr;
			}

			function _verifyCatIDs( optionsIDs ) {

				let catArr = attributes.categoriesIDs;
				let categoriesIDs = attributes.categoriesIDs;

				if( catArr.substr(0,1) == ',' ) {
					catArr = catArr.substr(1);
				}
				if( catArr.substr(catArr.length - 1) == ',' ) {
					catArr = catArr.substring(0, catArr.length - 1);
				}

				if( catArr != ',' && catArr != '' ) {

					let newCatArr = catArr.split(',');
					let newArr = [];
					for (let i = 0; i < newCatArr.length; i++) {
						if( optionsIDs.indexOf(newCatArr[i]) == -1 ) {
							categoriesIDs = categoriesIDs.replace(',' + newCatArr[i].toString() + ',', ',');
						}
					}
				}

				if( attributes.categoriesIDs != categoriesIDs ) {
					props.setAttributes({ queryPosts: _buildQuery(categoriesIDs, attributes.number, attributes.orderby) });
					props.setAttributes({ queryPostsLast: _buildQuery(categoriesIDs, attributes.number, attributes.orderby) });
				}

				props.setAttributes({ categoriesIDs: categoriesIDs });
				props.setAttributes({ categoriesSavedIDs: categoriesIDs });
			}

			function _buildQuery( arr, nr, order ) {
				let query = '/wp/v2/posts?per_page=' + nr;

				if( arr.substr(0,1) == ',' ) {
					arr = arr.substr(1);
				}
				if( arr.substr(arr.length - 1) == ',' ) {
					arr = arr.substring(0, arr.length - 1);
				}

				if( arr != ',' && arr != '' ) {
					query = '/wp/v2/posts?categories=' + arr + '&per_page=' + nr;
				}

				switch (order) {
					case 'date_asc':
						query += '&orderby=date&order=asc';
						break;
					case 'date_desc':
						query += '&orderby=date&order=desc';
						break;
					case 'title_asc':
						query += '&orderby=title&order=asc';
						break;
					case 'title_desc':
						query += '&orderby=title&order=desc';
						break;
					default:
						break;
				}

				query += '&lang=' + posts_grid_vars.language;

				return query;
			}

			function _isChecked( needle, haystack ) {
				let idx = haystack.indexOf(needle.toString());
				if ( idx > - 1) {
					return true;
				}
				return false;
			}

			function _categoryClassName(parent, value) {
				if ( parent == 0) {
					return 'parent parent-' + value;
				} else {
					return 'child child-' + parent;
				}
			}

			function _isLoadingText(){
				if ( attributes.isLoading  === false ) {
					return i18n.__('Update');
				} else {
					return i18n.__('Updating');
				}
			}

			function _isDonePossible() {
				return ( (attributes.queryPosts.length == 0) || (attributes.queryPosts === attributes.queryPostsLast) );
			}

			function _isLoading() {
				if ( attributes.isLoading  === true ) {
					return 'is-busy';
				} else {
					return '';
				}
			}

			//==============================================================================
			//	Show posts functions
			//==============================================================================

			function getPosts() {
				let query = attributes.queryPosts;
				props.setAttributes({ queryPostsLast: query});

				if (query != '') {
					apiFetch({ path: query }).then(function (posts) {
						props.setAttributes({ result: posts});
						props.setAttributes({ isLoading: false});
						props.setAttributes({ doneFirstPostsLoad: true});
					});
				}
			}

			function renderResults() {
				if ( attributes.firstLoad === true ) {
					apiFetch({ path: '/wp/v2/posts?per_page=12&orderby=date&order=desc&lang=' + posts_grid_vars.language }).then(function (posts) {
						props.setAttributes({ result: posts });
						props.setAttributes({ firstLoad: false });
						let query = '/wp/v2/posts?per_page=12&orderby=date&order=desc&lang=' + posts_grid_vars.language;
						props.setAttributes({queryPosts: query});
						props.setAttributes({ queryPostsLast: query});
					});
				}

				let posts = attributes.result;
				let postElements = [];
				let wrapper = [];

				if( posts.length > 0) {

					for ( let i = 0; i < posts.length; i++ ) {

						var months = [
							i18n.__( 'January',  	'mrtailor-extender' ),
							i18n.__( 'February', 	'mrtailor-extender' ),
							i18n.__( 'March', 	 	'mrtailor-extender' ),
							i18n.__( 'April', 	 	'mrtailor-extender' ),
							i18n.__( 'May', 	 	'mrtailor-extender' ),
							i18n.__( 'June', 	 	'mrtailor-extender' ),
							i18n.__( 'July', 	 	'mrtailor-extender' ),
							i18n.__( 'August', 		'mrtailor-extender' ),
							i18n.__( 'September',	'mrtailor-extender' ),
							i18n.__( 'October', 	'mrtailor-extender' ),
							i18n.__( 'November', 	'mrtailor-extender' ),
							i18n.__( 'December', 	'mrtailor-extender' ),
						];

						let date = new Date(posts[i]['date']);
						day = date.getDate();
						date = months[date.getMonth()] + ' ' + date.getFullYear();

						let img = '';
						let img_class = 'gbt_18_mt_editor_posts_grid_noimg';
						if ( posts[i]['fimg_url'] ) { img = posts[i]['fimg_url']; img_class = 'gbt_18_mt_editor_posts_grid_with_img'; } else { img_class = 'gbt_18_mt_editor_posts_grid_noimg'; img = ''; };

						postElements.push(
							el( "div",
								{
									key: 		'gbt_18_mt_editor_posts_grid_item_' + posts[i].id,
									className: 	'gbt_18_mt_editor_posts_grid_item'
								},
								el( "a",
									{
										key: 		'gbt_18_mt_editor_posts_grid_item_link_' + i,
										className: 	'gbt_18_mt_editor_posts_grid_item_link'
									},
									el( "span",
										{
											key: 		'gbt_18_mt_editor_posts_grid_img_container_' + i,
											className: 	'gbt_18_mt_editor_posts_grid_img_container'
										},
										el( "span",
											{
												key: 'gbt_18_mt_editor_posts_grid_img_overlay_' + i,
												className: 'gbt_18_mt_editor_posts_grid_img_overlay'
											}
										),
										el( "span",
											{
												key: 		'gbt_18_mt_editor_posts_grid_img_' + i,
												className: 	'gbt_18_mt_editor_posts_grid_img ' + img_class,
												style: 		{ backgroundImage: 'url(' + img + ')' }
											}
										)
									),
									el( "div",
										{
											key: 		'gbt_18_mt_editor_posts_grid_content_' + i,
											className: 	'gbt_18_mt_editor_posts_grid_content'
										},
										el( "div",
											{
												key: 		'gbt_18_mt_editor_posts_grid_content_inner_' + i,
												className: 	'gbt_18_mt_editor_posts_grid_content_inner'
											},
												el( "div",
												{
													key: 		'gbt_18_mt_editor_posts_grid_title_content_' + i,
													className: 	'gbt_18_mt_editor_posts_grid_title_content'
												},
												el( "h4",
													{
														key: 		'gbt_18_mt_editor_posts_grid_title_' + i,
														className:  'gbt_18_mt_editor_posts_grid_title',
														dangerouslySetInnerHTML: { __html: posts[i]['title']['rendered'] }
													}
												)
											)
										)
									)
								)
							)
						);
					}
				}

				return postElements;
			}

			//==============================================================================
			//	Display Categories
			//==============================================================================

			function getCategories() {

				let categories_list = [];
				let options = [];
				let optionsIDs = [];
				let sorted = [];

				apiFetch({ path: '/wp/v2/categories?per_page=-1&lang=' + posts_grid_vars.language }).then(function (categories) {

				 	for( let i = 0; i < categories.length; i++) {
	        			options[i] = {'label': categories[i].name.replace(/&amp;/g, '&'), 'value': categories[i].id, 'parent': categories[i].parent, 'count': categories[i].count };
				 		optionsIDs[i] = categories[i].id.toString();
				 	}

				 	sorted = _sortCategories(0, options);
		        	props.setAttributes({categoryOptions: sorted });
		        	_verifyCatIDs(optionsIDs);
		        	props.setAttributes({ doneFirstLoad: true});
				});
			}

			function renderCategories( parent = 0, level = 0 ) {
				let categoryElements = [];
				let catArr = attributes.categoryOptions;
				if ( catArr.length > 0 )
				{
					for ( let i = 0; i < catArr.length; i++ ) {
						 if ( catArr[i].parent !=  parent ) { continue; };
						categoryElements.push(
							el(
								'li',
								{
									key: 'category-' + i,
									className: 'level-' + catArr[i].level,
								},
								el(
								'label',
									{
										key: 'category-label-' + i,
										className: _categoryClassName( catArr[i].parent, catArr[i].value ) + ' ' + catArr[i].level,
									},
									el(
									'input',
										{
											type:  'checkbox',
											key:   'category-checkbox-' + catArr[i].value,
											value: catArr[i].value,
											'data-index': i,
											'data-parent': catArr[i].parent,
											checked: _isChecked(','+catArr[i].value+',', attributes.categoriesIDs),
											onChange: function onChange(evt){
												let newCategoriesSelected = attributes.categoriesIDs;
												let index = newCategoriesSelected.indexOf(',' + evt.target.value + ',');
												if (evt.target.checked === true) {
													if (index == -1) {
														newCategoriesSelected += evt.target.value + ',';
													}
												} else {
													if (index > -1) {
														newCategoriesSelected = newCategoriesSelected.replace(',' + evt.target.value + ',', ',');
													}
												}
												props.setAttributes({ categoriesIDs: newCategoriesSelected });
												props.setAttributes({ queryPosts: _buildQuery(newCategoriesSelected, attributes.number, attributes.orderby) });
											},
										},
									),
									catArr[i].label,
									el(
										'sup',
										{},
										catArr[i].count,
									),
								),
								renderCategories( catArr[i].value, level+1)
							),
						);
					}
				}
				if (categoryElements.length > 0 ) {
					let wrapper = el('ul', {className: 'level-' + level}, categoryElements);
					return wrapper;
				} else {
					return;
				}
			}

			return [
				el(
					InspectorControls,
					{
						key: 'mt-posts-grid-inspector'
					},
					el(
						'div',
						{
							className: 'main-inspector-wrapper',
						},
						el( 'label', { className: 'components-base-control__label' }, i18n.__('Categories:') ),
						el(
							'div',
							{
								className: 'category-result-wrapper',
							},
							attributes.categoryOptions.length < 1 && attributes.doneFirstLoad === false && getCategories(),
							renderCategories(),
						),
						el(
							SelectControl,
							{
								key: 'mt-posts-grid-order-by',
								options:
									[
										{ value: 'title_asc',   label: 'Alphabetical Ascending' },
										{ value: 'title_desc',  label: 'Alphabetical Descending' },
										{ value: 'date_asc',   	label: 'Date Ascending' },
										{ value: 'date_desc',  	label: 'Date Descending' },
									],
	              				label: i18n.__( 'Order By', 'mrtailor-extender' ),
	              				value: attributes.orderby,
	              				onChange: function( value ) {
	              					props.setAttributes( { orderby: value } );
	              					let newCategoriesSelected = attributes.categoriesIDs;
									props.setAttributes({ queryPosts: _buildQuery(newCategoriesSelected, attributes.number, value) });
								},
							}
						),
						el(
							RangeControl,
							{
								key: "mt-posts-grid-number",
								className: 'range-wrapper',
								value: attributes.number,
								allowReset: false,
								initialPosition: 12,
								min: 1,
								max: 20,
								label: i18n.__( 'Number of Posts', 'mrtailor-extender' ),
								onChange: function onChange(newNumber){
									props.setAttributes( { number: newNumber } );
									let newCategoriesSelected = attributes.categoriesIDs;
									props.setAttributes({ queryPosts: _buildQuery(newCategoriesSelected, newNumber, attributes.orderby) });
								},
							}
						),
						el(
							'button',
							{
								className: 'render-results components-button is-button is-default is-primary is-large ' + _isLoading(),
								disabled: _isDonePossible(),
								onClick: function onChange(e) {
									props.setAttributes({ isLoading: true });
									props.setAttributes({ categoriesSavedIDs: attributes.categoriesIDs });
									getPosts();
								},
							},
							_isLoadingText(),
						),
						el( 'hr', {} ),
						el(
							RangeControl,
							{
								key: "mt-posts-grid-columns",
								value: attributes.columns,
								allowReset: false,
								initialPosition: 3,
								min: 2,
								max: 4,
								label: i18n.__( 'Columns', 'mrtailor-extender' ),
								onChange: function( newColumns ) {
									props.setAttributes( { columns: newColumns } );
								},
							}
						),
					),
				),
				el( 'div',
					{
						key: 		'gbt_18_mt_posts_grid',
						className: 	'gbt_18_mt_posts_grid ' + className
					},
					el(
						'div',
						{
							key: 		'gbt_18_mt_editor_posts_grid_wrapper',
							className: 	'gbt_18_mt_editor_posts_grid_wrapper columns-' + attributes.columns,
						},
						attributes.result.length < 1 && attributes.doneFirstPostsLoad === false && getPosts(),
						renderResults()
					),
				),
			];
		},

		save: function(props) {
			return null;
		},
	} );

} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element
);
