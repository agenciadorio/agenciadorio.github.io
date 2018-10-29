define(['stampit', 'assets/js/portfolio-grid-filter/nav', 'assets/js/portfolio-grid-filter/items', 'assets/js/portfolio-grid-filter/gridView', 'assets/js/portfolio-grid-filter/selectors'], function (stampit, nav, items, gridView, selectors) {
	return stampit().compose(nav, items, gridView, selectors);
});
