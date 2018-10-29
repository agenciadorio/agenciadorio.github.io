/* global Modernizr */
define(['jquery', 'underscore', 'stampit', 'assets/js/portfolio-grid-filter/nav'], function ($, _, stampit, nav) {
	return nav.compose(stampit({
		methods: {
			toggleArrowsVisibility: function (ev, items) {
				this.$container.toggleClass('is-nav-arrows-hidden', this.arrowsHidden(items.items.length));

				return this;
			},

			arrowsHidden: function (currentlyVisible) {
				return (currentlyVisible <= this.itemsPerRow);
			},
		},

		init: function () {
			// event listeners
			this.$container.on(this.eventsNS + 'before_render', _.bind(this.toggleArrowsVisibility, this));

			return this;
		},
	}));
});
