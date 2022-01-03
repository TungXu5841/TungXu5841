/*!
 * Creative Elements - live PageBuilder
 * Copyright 2019-2021 WebshopWorks.com
 */

$(function onReady() {
	if (!$('#elementor').length && location.search.indexOf('&force=1&') < 0) {
		// redirect to preview page when content area doesn't exist
		location.href = cePreview + '&force=1&ver=' + Date.now();
	}
});
