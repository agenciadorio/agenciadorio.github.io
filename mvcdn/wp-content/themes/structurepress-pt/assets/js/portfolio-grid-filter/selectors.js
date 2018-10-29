define(['stampit'], function (stampit) {
	return stampit().props({
		navElmSel:         '.js-wpg-nav',
		navHolder:         '.js-wpg-nav-holder',
		navMobileFilter:   '.js-filter',
		itemsContainerSel: '.js-wpg-items',
		itemSel:           '.js-wpg-item',
		cardSel:           '.js-wpg-card',
		eventsNS:          'wpge_',
		hashPrefix:        'projects_',
	});
});
