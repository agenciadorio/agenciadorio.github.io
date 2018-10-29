define(['stampit', 'assets/js/portfolio-grid-filter/navSlider', 'assets/js/portfolio-grid-filter/items', 'assets/js/portfolio-grid-filter/sliderView', 'assets/js/portfolio-grid-filter/selectors'], function (stampit, navSlider, items, sliderView, selectors) {
	return stampit().compose(navSlider, items, sliderView, selectors);
});
