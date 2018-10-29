define(['stampit', 'assets/js/portfolio-grid-filter/generalView'], function (stampit, generalView) {
	return stampit().refs({
		rowHTML: '<div class="row"></div>',
	}).compose(generalView);
});
