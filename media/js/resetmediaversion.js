/*
 * @package     Joomla - Reset media version
 * @version     1.0.0
 * @author      Artem Vasilev - webmasterskaya.xyz
 * @copyright   Copyright (c) 2018 - 2020 Webmasterskaya. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://webmasterskaya.xyz/
 */

document.addEventListener('DOMContentLoaded', function() {
	var plg_quickicon_resetmediaversion_items = document.querySelectorAll(
		'#plg_quickicon_resetmediaversion > a');

	for (var i = 0, l = plg_quickicon_resetmediaversion_items.length; i <
	l; i++) {
		plg_quickicon_resetmediaversion_items[i].addEventListener('click',
			function(e) {
				e.preventDefault();
				e.stopPropagation();
				var url = this.href;

				new Joomla.request({
					url: url,
					onBefore: function(xhr) {
						Joomla.loadingLayer('show');
					},
					onSuccess: function(response, xhr) {
						Joomla.loadingLayer('hide');

						try {
							var resp = JSON.parse(response);
							if (resp.success && !!resp.message) {
								Joomla.renderMessages(
									{'success': [resp.message]});
								window.scroll(0, 0);
							}
							else {
								if (!!resp.message) {
									Joomla.renderMessages(
										{'error': [resp.message]});
									window.scroll(0, 0);
								}
							}
						}
						catch (e) {
							alert('Error! Look in the browser console');
							console.log(e);
						}
					},
					onError: function(xhr) {
						Joomla.loadingLayer('hide');
						alert('Error! Look in the browser console');
						console.log(xhr);
					},
				});
			});
	}
});