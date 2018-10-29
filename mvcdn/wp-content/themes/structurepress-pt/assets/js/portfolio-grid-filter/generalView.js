define(['jquery', 'underscore', 'stampit'], function ($, _, stampit) {
	return stampit({
		init: function () {
			this.$itemsContainer = this.$container.find(this.itemsContainerSel);
			return this;
		},

		methods: {
			groupArrayItems: function (groupSize, array) {
				return _.chain(array)
					.groupBy(function (item, index) {
						return Math.floor(index/groupSize);
					})
					.values()
					.value();
			},

			render: function ($items) {
				this.$container.trigger(this.eventsNS + 'before_render', {items: $items});

				var $toRemove = this.$itemsContainer.children();

				_.forEach(this.getItems(), function ($el) {
					$el.find(this.cardSel).addClass('is-fadeout');
				}, this);

				var groupedItems = this.groupArrayItems(this.itemsPerRow, $items);

				setTimeout(_.bind(function () { // trigger after 200ms, when the animation completes
					_.forEach(groupedItems, function (rowOfItems) {
						this.createNewRow(rowOfItems).appendTo(this.$itemsContainer);
					}, this);

					$toRemove.remove();

					if (this.afterRendered) {
						this.afterRendered();
					}

					this.$container.trigger(this.eventsNS + 'on_elements_switch', {items: $items});

				}, this), 200);

				return this;
			},

			createNewRow: function ($items) {
				var $row = $(this.rowHTML);

				$items.forEach(function ($item) {
					var $card = $item.find(this.cardSel);

					$card
						.removeClass('is-fadeout')
						.addClass('is-fadein');

					setTimeout(_.bind(function ($card) {
						this.removeClass('is-fadein');
					}, $card ), 200);

					if (_.isString(this.appendItemsInside)) {
						$item.appendTo($row.find(this.appendItemsInside));
					}
					else {
						$item.appendTo($row);
					}
				}.bind(this));

				return $row;
			},
		},

		props: {
			itemsPerRow: 4,
		}
	});
});
