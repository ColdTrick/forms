import 'jquery';
import 'input/file'; // make sure core file init is registered first
import i18n from 'elgg/i18n';

function updateFileLimits() {
	var $form = $(this).parents('.elgg-form-forms-submit').eq(0);
	var $all_files = $form.find('input[type=file]');
	var form_max_size = $form.data().maxFileSize;
	var running_total = 0;

	$all_files.each(function(index, file) {
		if (file.files.length === 0) {
			return;
		}

		running_total += file.files[0].size;
	});

	var total_bytes_left = form_max_size - running_total;

	$all_files.each(function(index, file) {
		var $file = $(file);
		var max_size = $file.data().originalMaxSize;

		var bytes_left = total_bytes_left;
		if (file.files.length > 0) {
			bytes_left += file.files[0].size;
		}

		max_size = Math.min(max_size, bytes_left);

		var readable_max_size = formatBytes(max_size);
		$file.data().maxSize = max_size;
		$file.data().maxSizeMessage = i18n.echo('upload:error:ini_size') + ' ' + i18n.echo('input:file:upload_limit', [readable_max_size]);
		$file.parent().next('.elgg-field-help').find('.elgg-input-file-size-helper').text(i18n.echo('input:file:upload_limit', [readable_max_size]))
	});
}

function formatBytes(size) {
	if (size < 0) {
		return size;
	}

	if (size === 0) {
		return '0 B';
	}

	var base = Math.log(size) / Math.log(1024);
	var suffixes = ['B', 'kB', 'MB', 'GB', 'TB'];

	return Math.round(Math.pow(1024, base - Math.floor(base)), 2) + ' ' + suffixes[Math.floor(base)];
}

$(document).on('change', '.elgg-form-forms-submit input[type=file]', updateFileLimits);
