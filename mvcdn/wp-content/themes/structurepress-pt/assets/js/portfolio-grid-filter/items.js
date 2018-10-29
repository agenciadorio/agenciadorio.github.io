define(['jquery', 'underscore', 'stampit'], function ($, _, stampit) {
	return stampit({
		methods: {
			addItem: function ($item) {
				this.$items.push({
					categories: this.getItemCagories($item),
					$elm:       $item,
				});
				return this;
			},

			getItemsByCategoryName: function (categoryName) {
				if ('*' === categoryName) { // all items
					return this.getItems();
				}

				return _.chain(this.$items)
					.filter(function (item) {
						return _.contains(item.categories, categoryName);
					})
					.pluck('$elm')
					.value();
			},

			getItemCagories: function ($item) {
				return $item.data('categories').split(',');
			},

			getItems: function () {
				return _.pluck(this.$items, '$elm');
			}
		},

		props: {
			$items: []
		},
	});
});
