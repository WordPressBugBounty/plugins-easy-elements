jQuery(window).on("elementor:init", function () {

	// Extend Elementor Control
	const EasyAutocomplete = elementor.modules.controls.BaseData.extend({

		isSearch: false,

		//------------------------------------------------------
		// Load initial selected items from DB
		//------------------------------------------------------
		loadSelectedResults() {
			const ids = this.normalizeIDs(this.getControlValue());
			if (!ids.length) return;

			this.showSpinner();

			jQuery.ajax({
				url: easyel_custom_select_obj.ajax_url,
				type: "POST",
				data: {
					action: this.model.get("render"),
					post_type: this.model.get("post_type"),
					taxonomy: this.model.get("taxonomy"),
					query_type: this.model.get("query_type"),
					id: ids,
					security: easyel_custom_select_obj.security,
				},
				success: (results) => {
					this.isSearch = true;
					this.model.set("options", results);
					this.render();
				},
			});
		},

		//------------------------------------------------------
		// Normalize ID Input
		//------------------------------------------------------
		normalizeIDs(value) {
			if (!value) return [];
			return Array.isArray(value) ? value : [value];
		},

		//------------------------------------------------------
		// Add loading spinner
		//------------------------------------------------------
		showSpinner() {
			this.ui.select.prop("disabled", true);
			this.$el
				.find(".elementor-control-title")
				.after(`<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>`);
		},

		//------------------------------------------------------
		// Ready state – initialize Select2 dropdown
		//------------------------------------------------------
		onReady: function () {
			const self = this;

			this.ui.select.select2({
				placeholder: "Search",
				allowClear: true,
				minimumInputLength: 2,
				ajax: {
					url: easyel_custom_select_obj.ajax_url,
					method: "POST",
					dataType: "json",
					delay: 250,
					data(params) {
						return {
							q: params.term,
							action: self.model.get("search"),
							post_type: self.model.get("post_type"),
							taxonomy: self.model.get("taxonomy"),
							query_type: self.model.get("query_type"),
							security: easyel_custom_select_obj.security,
						};
					},
					processResults(data) {
						return { results: data };
					},
					cache: true,
				},
			});

			if (!this.isSearch) {
				this.loadSelectedResults();
			}
		},

		//------------------------------------------------------
		// Cleanup when destroyed
		//------------------------------------------------------
		onBeforeDestroy() {
			if (this.ui.select.data("select2")) {
				this.ui.select.select2("destroy");
			}
			this.$el.remove();
		},
	});

	// Register the new control
	elementor.addControlView("easyel_autocomplete", EasyAutocomplete);
});