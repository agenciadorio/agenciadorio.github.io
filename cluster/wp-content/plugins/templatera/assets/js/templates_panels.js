var templatera_editor;
(function ($) {
	var templateraOptions = {
		save_template_action: 'vc_templatera_save_template',
		appendedClass: 'templatera_templates',
		appendedTemplateType: 'templatera_templates',
		delete_template_action: 'vc_templatera_delete_template'
	};

	var TemplateraPanelEditorBackend = vc.TemplatesPanelViewBackend.extend(templateraOptions);
	var TemplateraPanelEditorFrontend = vc.TemplatesPanelViewFrontend.extend(templateraOptions);

	$(document).ready(function () {
		// we need to update currect template panel to new one (extend functionality)
		if (vc_mode && vc_mode === 'admin_page') {
			if (vc.templates_panel_view) {
				vc.templates_panel_view.undelegateEvents(); // remove is required to detach event listeners and clear memory
				vc.templates_panel_view = templatera_editor = new TemplateraPanelEditorBackend({el: '#vc_templates-panel'});

				$('#vc-templatera-editor-button').click(function (e) {
					e && e.preventDefault && e.preventDefault();
					vc.templates_panel_view.render().show(); // make sure we show our window :)
				});
			}
		}
	});

	$(window).on('vc_build', function () {
		if (vc.templates_panel_view) {
			vc.templates_panel_view.undelegateEvents(); // remove is required to detach event listeners and clear memory
			vc.templates_panel_view = templatera_editor = new TemplateraPanelEditorFrontend({el: '#vc_templates-panel'});

			$('#vc-templatera-editor-button').click(function (e) {
				e && e.preventDefault && e.preventDefault();
				vc.templates_panel_view.render().show(); // make sure we show our window :)
			});
		}
	});
})(window.jQuery);