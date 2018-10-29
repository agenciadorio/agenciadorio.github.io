/**
 * Utilities for the admin dashboard
 */

jQuery( document ).ready( function( $ ) {
	'use strict';

	// Select Icon on Click
	$( 'body' ).on( 'click', '.js-selectable-icon', function ( ev ) {
		ev.preventDefault();
		var $this = $( this );
		$this.siblings( '.js-icon-input' ).val( $this.data( 'iconname' ) ).change();
	} );

} );


/********************************************************
 			Backbone code for repeating fields in widgets
********************************************************/

// Namespace for Backbone elements
window.StructurePress = {
	Models:    {},
	ListViews: {},
	Views:     {},
	Utils:     {},
};


/**
 ******************** Backbone Models *******************
 */

_.extend( StructurePress.Models, {
	OpenPositionItem: Backbone.Model.extend( {
		defaults: {
			'text': '',
			'icon': 'fa-home',
		}
	} ),

	ContactProfileItem: Backbone.Model.extend( {
		defaults: {
			'text': '',
			'icon': 'fa-home',
		}
	} ),
} );



/**
 ******************** Backbone Views *******************
 */

// Generic single view that others can extend from
StructurePress.Views.Abstract = Backbone.View.extend( {
	initialize: function ( params ) {
		this.templateHTML = params.templateHTML;

		return this;
	},

	render: function () {
		this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );

		return this;
	},

	destroy: function ( ev ) {
		ev.preventDefault();

		this.remove();
		this.model.trigger( 'destroy' );
	},
} );

_.extend( StructurePress.Views, {
	// View of a single open position detail item
	OpenPositionItem: StructurePress.Views.Abstract.extend( {
		className: 'pt-widget-single-open-position-item',

		events: {
			'click .js-pt-remove-op-detail-item': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );
			this.$( 'input.js-icon-input' ).val( this.model.get( 'icon' ) );
			return this;
		},
	} ),

	// View of a single contact profile item
	ContactProfileItem: StructurePress.Views.Abstract.extend( {
		className: 'pt-widget-single-contact-profile-item',

		events: {
			'click .js-pt-remove-contact-profile-item': 'destroy',
		},

		render: function () {
			this.$el.html( Mustache.render( this.templateHTML, this.model.attributes ) );
			this.$( 'input.js-icon-input' ).val( this.model.get( 'icon' ) );
			return this;
		},
	} ),

} );



/**
 ******************** Backbone ListViews *******************
 *
 * Parent container for multiple view nodes.
 */

StructurePress.ListViews.Abstract = Backbone.View.extend( {

	initialize: function ( params ) {
		this.widgetId     = params.widgetId;
		this.itemsModel   = params.itemsModel;
		this.itemView     = params.itemView;
		this.itemTemplate = params.itemTemplate;

		// Cached reference to the element in the DOM
		this.$items = this.$( params.itemsClass );

		// Collection of items(locations, people, testimonials,...),
		this.items = new Backbone.Collection( [], {
			model: this.itemsModel
		} );

		// Listen to adding of the new items
		this.listenTo( this.items, 'add', this.appendOne );

		return this;
	},

	addNew: function ( ev ) {
		ev.preventDefault();

		var currentMaxId = this.getMaxId();

		this.items.add( new this.itemsModel( {
			id: (currentMaxId + 1)
		} ) );

		return this;
	},

	getMaxId: function () {
		if ( this.items.isEmpty() ) {
			return -1;
		}
		else {
			var itemWithMaxId = this.items.max( function ( item ) {
				return parseInt( item.id, 10 );
			} );

			return parseInt( itemWithMaxId.id, 10 );
		}
	},

	appendOne: function ( item ) {
		var renderedItem = new this.itemView( {
			model:        item,
			templateHTML: jQuery( this.itemTemplate + this.widgetId ).html()
		} ).render();

		var currentWidgetId = this.widgetId;

		// If the widget is in the initialize state (hidden), then do not append a new item
		if ( '__i__' !== currentWidgetId.slice( -5 ) ) {
			this.$items.append( renderedItem.el );
		}

		return this;
	}
} );

// Collection of all locations, but associated with each individual widget
_.extend( StructurePress.ListViews, {
	// Collection of all open position detail items, but associated with each individual widget
	OpenPositionItems: StructurePress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-op-detail-item': 'addNew'
		}
	} ),

	// Collection of all contact profile items, but associated with each individual widget
	ContactProfileItems: StructurePress.ListViews.Abstract.extend( {
		events: {
			'click .js-pt-add-contact-profile-item': 'addNew'
		}
	} ),
} );



/**
 ******************** Repopulate Functions *******************
 */


_.extend( StructurePress.Utils, {
	// Generic repopulation function used in all repopulate functions
	repopulateGeneric: function ( collectionType, parameters, json, widgetId ) {
		var collection = new collectionType( parameters );

		// Convert to array if needed
		if ( _( json ).isObject() ) {
			json = _( json ).values();
		}

		// Add all items to collection of newly created view
		collection.items.add( json, { parse: true } );
	},

	/**
	 * Function which adds the existing open position detail items to the DOM
	 * @param  {json} openPositionItemsJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateOpenPositionItems: function ( openPositionItemsJSON, widgetId ) {
		var parameters = {
			el:           '#op-details-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.op-detail-items',
			itemTemplate: '#js-pt-op-detail-item-',
			itemsModel:   StructurePress.Models.OpenPositionItem,
			itemView:     StructurePress.Views.OpenPositionItem,
		};

		this.repopulateGeneric( StructurePress.ListViews.OpenPositionItems, parameters, openPositionItemsJSON, widgetId );
	},

	/**
	 * Function which adds the existing contact profile items to the DOM
	 * @param  {json} contactProfileItemJSON
	 * @param  {string} widgetId ID of widget from PHP $this->id
	 * @return {void}
	 */
	repopulateContactProfileItems: function ( contactProfileItemJSON, widgetId ) {
		var parameters = {
			el:           '#contact-profile-items-' + widgetId,
			widgetId:     widgetId,
			itemsClass:   '.contact-profile-items',
			itemTemplate: '#js-pt-contact-profile-item-',
			itemsModel:   StructurePress.Models.ContactProfileItem,
			itemView:     StructurePress.Views.ContactProfileItem,
		};

		this.repopulateGeneric( StructurePress.ListViews.ContactProfileItems, parameters, contactProfileItemJSON, widgetId );
	},
} );