/*
 * @package     Joomla - Reset media version
 * @version     1.1.0
 * @author      Artem Vasilev - webmasterskaya.xyz
 * @copyright   Copyright (c) 2018 - 2021 Webmasterskaya. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://webmasterskaya.xyz/
 */

document.addEventListener('DOMContentLoaded', function () {
	const options = Joomla.getOptions('js-reset-media-version'),
		j4_compatible = !!options?.j4_compatible,
		plg_quickicon_resetmediaversion_items = j4_compatible
			? document.querySelectorAll('a#plg_quickicon_resetmediaversion')
			: document.querySelectorAll('#plg_quickicon_resetmediaversion > a');

	for (let i = 0, l = plg_quickicon_resetmediaversion_items.length; i < l; i++) {
		plg_quickicon_resetmediaversion_items[i].addEventListener('click',
			function (e) {
				e.preventDefault();
				e.stopPropagation();
				const url = this.href;
				const spinner = j4_compatible
					? document.createElement('joomla-core-loader')
					: undefined;

				Joomla.request({
					url: url,
					onBefore: function (xhr) {
						if (!!spinner) {
							document.body.appendChild(spinner);
						} else {
							Joomla.loadingLayer('show');
						}
					},
					onSuccess: function (response, xhr) {
						resetMediaVersionSleep(600);
						if (!!spinner) {
							spinner.parentNode.removeChild(spinner);
						} else {
							Joomla.loadingLayer('hide');
						}

						try {
							var resp = JSON.parse(response);
							if (resp.success && !!resp.message) {
								Joomla.renderMessages(
									{'success': [resp.message]});
								window.scroll(0, 0);
							} else {
								if (!!resp.message) {
									Joomla.renderMessages(
										{'error': [resp.message]});
									window.scroll(0, 0);
								}
							}
						} catch (e) {
							alert('Error! Look in the browser console');
							console.log(e);
						}
					},
					onError: function (xhr) {
						resetMediaVersionSleep(600);
						if (!!spinner) {
							spinner.parentNode.removeChild(spinner);
						} else {
							Joomla.loadingLayer('hide');
						}
						alert('Error! Look in the browser console');
						console.log(xhr);
					},
				});
			});
	}
});

function resetMediaVersionSleep(milliseconds) {
	const date = Date.now();
	let currentDate = null;
	do {
		currentDate = Date.now();
	} while (currentDate - date < milliseconds);
}