/* global Modernizr */
define(['jquery', 'underscore', 'stampit'], function ($, _, stampit) {
	return stampit({
		methods: {
			onCategoryClick: function (ev) {
				ev.preventDefault();

				if ($(ev.currentTarget).parent().hasClass('is-active')) { // do nothing if we click on already active
					return;
				}

				this.filterItems(ev);
				this.updateActiveBtn(ev);
				this.updateUrlHash(ev);

				if (! this.isDesktopLayout()) {
					this.toggleNavHolderState();
				}
			},

			toggleNavHolderState: function (ev) {
				if (ev && ev.preventDefault) {
					ev.preventDefault();
				}

				if (this.mobileNavOpened) {
					this.closeFilterMenu();
				}
				else {
					this.openFilterMenu();
				}

				this.toggleNavHolderStateProp();

				return this;
			},

			openFilterMenu: function () {
				var toHeight = this.heightOfAllElmChildren(this.$navHolder);

				this.animateNavHolderHeightTo(toHeight);

				return this;
			},

			closeFilterMenu: function () {
				var toHeight = this.heightOfActiveChild(this.$navHolder);

				this.animateNavHolderHeightTo(toHeight);

				return this;
			},

			animateNavHolderHeightTo: function (newHeight) {
				this.$navHolder.animate({
					height: newHeight,
				});

				return this;
			},

			heightOfAllElmChildren: function ($elm) {
				return _.reduce($elm.children().not('.is-disabled').get(), function (memo, childElm) {
					return memo + $(childElm).outerHeight(true);
				}, 0);
			},

			heightOfActiveChild: function ($elm) {
				return $elm.children('.is-active').first().outerHeight(true);
			},

			filterItems: function (ev) {
				if (ev) {
					ev.preventDefault();
				}

				var selectedCategory = $(ev.target).data('category');

				this.render(this.getItemsByCategoryName(selectedCategory));

				return this;
			},

			updateActiveBtn: function (ev) {
				$(ev.target)
					.parent().addClass('is-active')
					.siblings('.is-active').removeClass('is-active');
			},

			toggleNavHolderStateProp: function () {
				this.mobileNavOpened = (! this.mobileNavOpened);

				return this;
			},

			recalcNavHolderStyle: function () {
				if (this.isDesktopLayout()) {
					this.$navHolder.removeAttr('style');
					this.mobileNavOpened = false;
				}
				else {
					this.initNavHolderHeight();
				}

				return this;
			},

			initNavHolderHeight: function () {
				var height = this.heightOfActiveChild(this.$navHolder);

				this.$navHolder.outerHeight(height);
				this.$navHolder.css('padding-top', height);

				return this;
			},

			isDesktopLayout: function () {
				return Modernizr.mq('(min-width: ' + this.mobileBreakpoint + 'px)');
			},

			updateUrlHash: function (ev) {
				// setting on click
				window.location.hash = ev.currentTarget.hash.replace('#', '#' + this.hashPrefix);
			},
		},

		init: function () {
			this.$container.find(this.itemSel).each(_.bind(function (index, elm) {
				this.addItem($(elm));
			}, this));

			// cache jQuery selector
			this.$navHolder = this.$container.find(this.navHolder);

			// register event listeners
			this.$container.on('click.wpg', this.navElmSel, _.bind(this.onCategoryClick, this));
			this.$container.on('click.wpg', this.navMobileFilter, _.bind(this.toggleNavHolderState, this));

			$(window).on('resize', _.debounce(_.bind(this.recalcNavHolderStyle, this), 50));

			// init height if necessarry
			this.recalcNavHolderStyle();

			return this;
		},

		props: {
			mobileNavOpened:  false,
			mobileBreakpoint: 992,
		},
	});
});
