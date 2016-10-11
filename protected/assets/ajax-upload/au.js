String.prototype.replaceAll = function(search, replacement) {
	var target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};

(function( $ ) {
$.fn.ajaxUpload = function($options) {
	var checkExtension = function(file) {
		if (options.extensions.length < 1) return true;
		var fileExt = file.name.split('.').pop();
		if ( $.inArray(fileExt.toLowerCase(), options.extensions)<0 ) {
			alert('File type is not allowed');
			return false;
		}
		return true;
	};

	var checkMaxSize = function(file) {
		if (options.maxSize==0) return true;

		if ( file.size > options.maxSize*1000 ) {
			alert('File is too large');
			return false;
		}
		return true;
	};

	return this.each(function() {
		var widget = $(this);

		var uploadFile = function(file) {
			if (!checkExtension(file) || !checkMaxSize(file) ) {
				return false;
			}
			var data = new FormData();
			data.append('ajax-file', file);	// create a field to form

			var request = new XMLHttpRequest();
			request.onreadystatechange = function(){
				if(request.readyState == 4){	// done
					try {
						var resp = JSON.parse(request.response);
						widget.find('.files').append(createItem(resp));
						widget.find('.holder').remove();
						widget.find('.loader').addClass('hide');
					} catch (e){ }
				}
			};
			// request.upload.addEventListener('progress', function () {}, false);
			request.open('POST', options.uploadUrl);
			request.send(data);
			widget.find('.loader').removeClass('hide'); // show loading
			return true;
		};

		var createItem = function (data) {
			var inputName = options.name+ (options.multiple ? '[]' : '') +'[image]';
			var template = '<div class="col-md-3"><div class="inn">'
					+'<img src="{{LINK}}" alt="" class="img-responsive"/>'
					+'<p class="name">{{NAME}}</p>'
					+'&nbsp;<a class="remove fa fa-trash" href="javascript:void(0)"></a>'
					+'<input type="hidden" name="{{INPNAME}}" value="{{NAME}}" />'
				+'</div></div>';
			var html = template.replaceAll('{{NAME}}', data.value)
				.replaceAll('{{INPNAME}}', inputName)
				.replaceAll('{{LINK}}', data.link);
			return html;
		};

		widget.on('change', 'input[type="file"]', function() {
			if ( this.files.length === 0){
				this.value = '';
				return;
			}

			$(this.files).each(function () {
				uploadFile(this);
			});
			// reset file input
			this.value = '';
		});

		widget.on('click', '.remove', function() {
			var p = $(this).closest('.item');
			p.remove();
			if (widget.find('.files .item').size()<1) {
				var hidden = '<input type="hidden" name="' +options.name+ '" value="" class="holder" />';
				widget.append(hidden);
			}

		});

		$('.ajax-upload-widget .files').sortable({
			opacity: 0.6,
			revert: true,
			placeholder: 'sortable-placeholder col-md-3',
			cursor: 'move'
		});
	});
};
}( jQuery ));