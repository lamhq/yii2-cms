(function( $ ) {
$.fn.treeCheckbox = function(items) {
	return this.each(function() {
		var tree = $(this);
		tree.on('changed.jstree', function (e, data) {
			var selecteds = tree.jstree('get_top_selected');
			tree.closest('.tree-checkbox')
				.find('input[type="hidden"]')
				.val(JSON.stringify(selecteds));
		}).jstree({
			'plugins':['checkbox'],
			'core' : {
				'data' : items
			}
		});
	});
};
}( jQuery ));
