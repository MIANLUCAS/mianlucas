(function( $ ) {

	'use strict';

	function gbt_cn_onElementInserted(containerSelector, selector, childSelector, callback) {
		if ("MutationObserver" in window) {
			var onMutationsObserved = function (mutations) {
				mutations.forEach(function (mutation) {
					if (mutation.addedNodes.length) {
						if ($(mutation.addedNodes).length) {
							var finalSelector = selector;
							var ownElement = $(mutation.addedNodes).filter(selector);
							if (childSelector != '') {
								ownElement = ownElement.find(childSelector);
								finalSelector = selector + ' ' + childSelector;
							}
							ownElement.each(function (index) {
								callback($(this), index + 1, ownElement.length, finalSelector,true);
							});
							if (!ownElement.length) {
								var childElements = $(mutation.addedNodes).find(finalSelector);
								childElements.each(function (index) {
									callback($(this), index + 1, childElements.length, finalSelector,true);
								});
							}
						}
					}
				});
			};

			var target = $(containerSelector)[0];
			var config = {childList: true, subtree: true};
			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
			var observer = new MutationObserver(onMutationsObserved);
			observer.observe(target, config);
		}
	}

	var gbt_cn = {
		messages: [],
		open: false,
		init: function () {
			gbt_cn_onElementInserted('body', '.woocommerce-error', 		'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-message', 	'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-info', 		'', gbt_cn.checkForButtons);
			gbt_cn_onElementInserted('body', '.woocommerce-notice', 	'', gbt_cn.checkForButtons);

			gbt_cn.checkExistingElements('.woocommerce-error');
			gbt_cn.checkExistingElements('.woocommerce-message');
			gbt_cn.checkExistingElements('.woocommerce-info');
			gbt_cn.checkExistingElements('.woocommerce-notice');
		},
		checkExistingElements: function (selector) {
			var element = $(selector);
			if (element.length) {
				element.each(function (index) {
					gbt_cn.checkForButtons($(this), index + 1, element.length, selector,false);
				});
			}
		},
		checkForButtons: function (element, index, total, selector, dynamic) {
            if( ( element.find('a').length == 0 ) && ( element.find('button').length == 0 ) ) {
        		element.addClass('no-button');
        	}
		}
	};

	document.addEventListener('DOMContentLoaded', function () {
		gbt_cn.init();
		$('body').trigger({
			type: 'gbt_cn',
			obj: gbt_cn
		});
	});

} )( jQuery );
