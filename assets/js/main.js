import 'selectize';

(function($) {
	const feather = require('feather-icons');
	
	feather.replace();
	
	$(document).ready(() => {
		feather.replace();

		$('.button-selectize').selectize(
//			{
//			onInitialize: function () {
//				this.$control_input.attr('readonly', true);
//			}
//			}
		);
		
		document.body.style.setProperty('--cp-header-height', document.getElementById('masthead').offsetHeight + 'px');
	
		// Dropdowns
		var $dropdowns = getAll('.dropdown:not(.is-hoverable)');

		if ($dropdowns.length > 0) {
			$dropdowns.forEach(function ($el) {
				$el = $($el);
				$el.on('click', '.dropdown-trigger', function (event) {
					event.preventDefault();
					event.stopPropagation();
					$el.toggleClass('is-active');
				});
			});

			document.addEventListener('click', function (event) {
				closeDropdowns();
			});
		}

		function closeDropdowns () {
			$dropdowns.forEach(function ($el) {
				$el.classList.remove('is-active');
			});
		}
		
	} );

	function getAll (selector) {
		var parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document;

		return Array.prototype.slice.call(parent.querySelectorAll(selector), 0);
	}	


	$(window).on('load', () => {
		const urlParams = new URLSearchParams(window.location.search);
		const tab = urlParams.get('tab');
	
		if(!tab) return
	
		const tabsElem = document.querySelector('.wp-block-ub-tabbed-content')
	
		if(!tabsElem) return

		const tabItems = [
			...tabsElem.querySelectorAll('.wp-block-ub-tabbed-content-accordion-toggle'),
			...tabsElem.querySelectorAll('[role="tab"]')
		]
	
		setTimeout(() => {
			tabsElem.scrollIntoView({ block: "center" })
			tabItems.forEach(elem => {
				if(elem.innerHTML === tab) {
					elem.click();
				}
			})
		}, 10 )
	});

})(jQuery);
