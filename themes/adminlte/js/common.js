/*
 * javascript for common functions in backend
 */
var module ={
	/*
	 * show loading animation when executing ajax load
	 */
	showLoading: function() {
		$('.content-wrapper').addClass('loading');
	},

	hideLoading: function() {
		$('.content-wrapper').removeClass('loading');
	},

	/*
	 * show popup for multiple purposes
	 */
	showModal: function(options) {
		var setting = $.extend({
			header: '',
			content : '',
			onOk: function () {},
			onCancel: function () {}
		}, typeof options !== 'undefined' ?  options : {});

		var modal = $('#app-modal');
		modal.find('.modal-title').html(setting.header);
		modal.find('.modal-body').html(setting.content);
		modal.find('.btn-cancel').unbind('click').on('click', setting.onCancel);
		modal.modal('show');
		modal.find('.btn-ok').unbind('click').on('click', setting.onOk).focus();
	},

	showError: function(options) {
		var setting = $.extend({
			header: '<i class="icon fa fa-warning"></i> Opps! Something went wrong.',
			content : 'Please try again later.',
		}, typeof options !== 'undefined' ?  options : {});
		app.showModal(setting);
	},

	/*
	 * load page by ajax
	 */
	loadPage: function(url, data) {
		var url = typeof url !== 'undefined' ? url : window.location.href;
		var data = typeof data !== 'undefined' ? data : '';
		app.showLoading();
		$('.main-content').load(url, data,
			function(response, status, xhr) {
				app.hideLoading();
				if ( status == "error" ) {
					app.showError({ content: xhr.status + " " + xhr.statusText });
 				}
		});
	},

	setupAjaxLink: function(link) {
		$(link).on('click', function(e){
			e.preventDefault();
			app.loadPage(this.href);
		});
	}		
};

// merge module code to app object
if (typeof app==='undefined') app = {};
app = $.extend(app, module);