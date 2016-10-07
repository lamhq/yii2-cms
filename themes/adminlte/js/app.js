/*
 * javascript for functions in backend
 */
var module ={
	setupRoleForm: function() {
		var updateFormVisibility = function () {
			if ( $('#role-access').val()==='custom' ) {	// custom
				$('.tree-checkbox').show();
			} else {
				$('.tree-checkbox').hide();
			}
		};
		$('#role-access').change(updateFormVisibility);
		updateFormVisibility();
	},
};

// merge module code to app object
if (typeof app==='undefined') app = {};
app = $.extend(app, module);