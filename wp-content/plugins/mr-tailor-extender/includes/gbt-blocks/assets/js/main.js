( function( blocks ) {
	var blockCategories = blocks.getCategories();
	blockCategories.unshift({ 'slug': 'mrtailor', 'title': 'Mr. Tailor Blocks'});
	blocks.setCategories(blockCategories);
})(
	window.wp.blocks
);