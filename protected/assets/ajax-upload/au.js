String.prototype.replaceAll = function(search, replacement) {
	var target = this;
	return target.replace(new RegExp(search, 'g'), replacement);
};

(function( $ ) {
$.fn.ajaxUpload = function($options) {
	var options = $options;
	
	var createEmptyItem = function () {
		var inputName = options.name;
		if (options.multiple) {
			inputName += '['+$.fn.ajaxUpload.itemCount+']';
			$.fn.ajaxUpload.itemCount++;
		}
		var itemTemplate = '<div class="item">'
				+'<div class="progress hide">'
					+'<div class="progress-bar progress-bar-info"></div>'
				+'</div>'
				+'<i class="fa fa-file-o fa-5x"></i>'
				+'<img alt="" class="img-responsive"/>'
				+'<div class="label"></div>'
				+'<a class="remove fa fa-trash" href="javascript:void(0)"></a>'
				+'<input type="hidden" name="{{name}}[value]" value="" class="value"/>'
				+'<input type="hidden" name="{{name}}[url]" value="" class="url" />'
				+'<input type="hidden" name="{{name}}[label]" value="" class="label" />'
			+'</div>';
		var item = $(itemTemplate.replaceAll('{{name}}', inputName));
		return item;
	};

	var updateItem = function (item, data) {
		data = $.extend({ label:'', url:'', value:'' }, data);
		if ( (/\.(gif|jpg|jpeg|tiff|png|bmp)$/i).test(data.value) ) {
			item.find('img').prop('src', data.url);
		} else {
			item.addClass('not-image');
		}

		if ( data.value=='' && data.label!='' ) {
			item.addClass('uploading');
		}

		item.find('div.label').text(data.label);
		item.find('input.label').val(data.label);
		item.find('input.value').val(data.value);
		item.find('input.url').val(data.url);
		return item;
	};

	var addItem = function (widget, data) {
		var item = createEmptyItem();
		updateItem(item, data);
		widget.find('.files').append(item);
		return item;
	};

	var addPendingUploadItem = function (widget, file) {
		if (!validateFile) return;

		if (!options.multiple) {
			widget.find('.files .item').each(function () {
				removeItem(widget, $(this));
			});
		}

		var item = addItem(widget, { label: file.name });
		var xhr = uploadFile(file);
		xhr.onreadystatechange = function(){
			// handling when upload done
			if(xhr.readyState == 4){
				var response = $.parseJSON(xhr.response);
				if (response.status=='success') {
					item.find('.progress-bar').css('width', '100%');
					setTimeout(function () {
						item.find('.progress').addClass('hide');
						updateItem(item, response);
					}, 1000);
					widget.find('.holder').remove();
				} else {
					removeItem(widget, item);
					app.showError({content: response.message});
				}
			}
		};
		xhr.upload.addEventListener('progress', function  (e) {
			// show upload progress
		  	if (!e.lengthComputable) return;
			var percentComplete = Math.ceil(100 * e.loaded / e.total);
			item.find('.progress').removeClass('hide');
			item.find('.progress-bar').css('width', percentComplete+'%');
		}, false);
		item.data('xhr', xhr);
		return item;
	};

	var uploadFile = function (file) {
		var xhr = new XMLHttpRequest();
		var data = new FormData();
		data.append('ajax-file', file);
		xhr.open('POST', options.url);
		xhr.setRequestHeader("X-File-Name", encodeURIComponent(file.name));
		xhr.setRequestHeader("X-File-Size", file.size);
		xhr.send(data);
		return xhr;
	};

	var removeItem = function (widget, item) {
		if (item.data('xhr')) {
			item.data('xhr').abort();
		}
		item.remove();
		if (widget.find('.files .item').length<1) {
			// submit an empty value to server if no file upload
			var hidden = '<input type="hidden" name="'+options.name+'" value="" class="holder" />';
			widget.append(hidden);
		}
	};

	var validateFile = function (file) {
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
		return checkExtension(file) && !checkMaxSize(file);
	};

	return this.each(function() {
		var widget = $(this);
		// display items
		$.each(options.items, function() {
			addItem(widget, this);
		});

		// remove item
		widget.on('click', '.remove', function() {
			var item = $(this).closest('.item');
			removeItem(widget, item);
		});

		// upload file after choosing
		widget.on('change', 'input[type="file"]', function() {
			if ( this.files.length>0 ){
				$(this.files).each(function () {
					addPendingUploadItem(widget, this);
				});
			}
			this.value = '';
		});

		// make item sortable
		$('.ajax-upload-widget .files').sortable({
			opacity: 0.6,
			placeholder: 'item',
			cursor: 'move'
		});
	});
};

$.fn.ajaxUpload.itemCount = 0;
}( jQuery ));