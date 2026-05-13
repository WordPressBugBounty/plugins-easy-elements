(function ($) {
  "use strict";

  const EasyEL = {
    init: function () {
      this.cacheDOM();
      this.bindEvents();
    },

    cacheDOM: function () {
      this.$modal = $("#easyel-template-modal");
      this.$openBtn = $("#easyel-theme-builder-add-template");
      this.$closeBtn = $(".easyel-close, .easyel-cancel-btn");
      this.$addRowBtn = $("#easyel-add-condition");
      this.$conditionsWrapper = $("#easyel-conditions-wrapper");
      this.$saveBtn = $(".easyel-save-btn");
      this.$editBtn = $(".easyel-edit-template");
    },

    bindEvents: function () {
      const self = this;

      $(document).on("click.easyel", "#easyel-theme-builder-add-template", function (e) {
        e.preventDefault();

        self.$modal = $("#easyel-template-modal");

        self.$modal
          .css({
            visibility: "visible",
            opacity: 0,
          })
          .animate({ opacity: 1 }, 200);
      });

      // Close modal
      self.$closeBtn.on("click.easyel", function () {
        self.$modal.fadeOut();
      });

      // Add new condition row
      self.$addRowBtn.on("click.easyel", function () {
        self.addConditionRow();

      });

      $(document).on("click.easyel", ".easyel-remove-row", function () {
      
        let $wrapper = $(this).closest("#easyel-conditions-wrapper, .easyel-conditions-wrapper-edit");

        let $row  = $(this).closest(".easyel-condition-row-edit, .easyel-condition-row");
        let $rows = $wrapper.children(".easyel-condition-row-edit, .easyel-condition-row");

        if ($rows.length > 1 && !$row.is($rows.first())) {
            $row.remove();
        }
    });

      // Close success modal
      $(document).on(
        "click.easyel",
        "#easyel-success-modal .easyel-close, #easyel-success-modal .easyel-cancel-btn",
        function () {
          $("#easyel-success-modal").fadeOut();
          location.reload();
        }
      );

      // Save & Close (AJAX)
      self.$saveBtn.on("click.easyel", function () {
        self.saveConditions();
      });

      self.$editBtn.on("click.easyel", function () {
        self.updateConditions();
      });
    },

    addConditionRow: function () {
        const $wrapper = this.$conditionsWrapper;

        const $row = $wrapper.find(".easyel-condition-row").first().clone();

        $row.find(".easyel-include-type").val("include");
        $row.find(".easyel-condition-sub").empty();

        $row.find(".easyel-condition-child-sub").hide().empty();

        const $mainSelect = $row.find(".easyel-condition-main");

        $wrapper.append($row);

        let selectedSub = $row.attr("data-selected-sub") || "all";
        this.populateSubOptions($mainSelect, selectedSub);
    },

    populateSubOptions: function ($mainSelect, selectedSub = "", callback = null) {
      const $row = $mainSelect.closest(".easyel-condition-row, .easyel-condition-row-edit");
      const $subSelect = $row.find(".easyel-condition-sub");
      const $childSub = $row.find(".easyel-condition-child-sub");

      const mainValue = $mainSelect.val();

      // reset selects
      $subSelect.empty().hide();
      $childSub.empty().hide();

      if (mainValue === "archives") {
        $subSelect.show();

        $.ajax({
          url: easyel_builder_obj.ajax_url,
          method: "POST",
          data: { action: "easyel_get_archives" },
          success: function (response) {
            if (response.success) {
              let data = response.data;


              function createOption(item) {
                let attrs = { 
                  value: item.value, 
                  text: item.label,
                  selected: item.value === selectedSub,
                };

                if (item.pro === true) {
                  attrs.disabled = true;
                  attrs["data-pro"] = "1";
                }

                return $("<option>", attrs);
              }

              if (data.core) {
                const $group = $("<optgroup>", { label: "Core Archives" });
                data.core.forEach((item) => $group.append(createOption(item)));
                $subSelect.append($group);
              }

              if (data.posts_archive) {
                const $group = $("<optgroup>", { label: "Posts Archive" });
                data.posts_archive.forEach((item) => $group.append(createOption(item)));
                $subSelect.append($group);
              }

              if (data.dynamic) {
                Object.keys(data.dynamic).forEach(function (groupLabel) {
                  const $group = $("<optgroup>", { label: groupLabel });

                  data.dynamic[groupLabel].forEach(function (item) {
                    $group.append(createOption(item));
                  });

                  $subSelect.append($group);
                });
              }


              if (data.products_archive) {
                const $group = $("<optgroup>", { label: "Products Archive" });
                data.products_archive.forEach((item) => $group.append(createOption(item)));
                $subSelect.append($group);
              }


                let subValue = $subSelect.val();

                const noChild = (easyel_builder_obj.no_child_sub || []).map(String);

                const subValueStr = String(subValue || '');

                if (noChild.includes(subValueStr)) {
                    $childSub.hide().empty();
                } else {
                    $childSub.show();
                }


              if (typeof callback === "function") callback();
              } else {
              
                if (typeof callback === "function") callback();
              }
          },
          error: function () {
            if (typeof callback === "function") callback();
          },
        });
      } else if (mainValue === "singular") {
        $subSelect.show();

        $.ajax({
          url: easyel_builder_obj.ajax_url,
          method: "POST",
          data: { action: "easyel_get_singulars" },
          success: function (response) {
            if (response.success && Array.isArray(response.data)) {
              response.data.forEach(function (item) {
                if (item.group) {
                  let $group = $subSelect.find('optgroup[label="' + item.group + '"]');
                  if (!$group.length) {
                    $group = $("<optgroup>", { label: item.group });
                    $subSelect.append($group);
                  }
                  $group.append(
                    $("<option>", {
                      value: item.value,
                      text: item.label,
                      selected: item.value === selectedSub,
                      disabled: item.pro,
                    })
                  );
                } else {
                  $subSelect.append(
                    $("<option>", {
                      value: item.value,
                      text: item.label,
                      selected: item.value === selectedSub,
                      disabled: item.pro,
                    })
                  );
                }
              });

              if (selectedSub) {
                $subSelect.val(selectedSub);
              }

              const staticNoChild = ["all", "front_page", "not_found404"];

              const dynamicNoChild = (typeof easyel_builder_obj !== "undefined" && easyel_builder_obj.no_child_sub)
                  ? easyel_builder_obj.no_child_sub
                  : [];

              // Merge + unique
              const noChildNeeded = [...new Set([...staticNoChild, ...dynamicNoChild])];

              let subValue = $subSelect.val();
              const subValueStr = String(subValue || '');

              if (noChildNeeded.includes(subValueStr)) {
                  $childSub.hide().empty();
              } else {
                  $childSub.show();
              }


              if (typeof callback === "function") callback();
            } else {
              if (typeof callback === "function") callback();
            }
          },
          error: function () {
            if (typeof callback === "function") callback();
          },
        });
      } else {
        // other mains: hide both selects
        $subSelect.hide();
        $childSub.hide();
        if (typeof callback === "function") callback();
      }
    },

    populateChildSubOptions: function ($subSelect, selectedChild = "") {

        if (!$subSelect || !$subSelect.length || !$subSelect[0].isConnected) {
            return;
        }

        const $row = $subSelect.closest(".easyel-condition-row, .easyel-condition-row-edit");

        if (!$row.length || !$row[0].isConnected) {
            return;
        }

        const $childSub = $row.find(".easyel-condition-child-sub");

        if (!$childSub.length || !$childSub[0].isConnected) {
            return;
        }

        const type = String($subSelect.val() || "");
        const noChild = (easyel_builder_obj.no_child_sub || []).map(String);

        /* --------------------------------
        * NO CHILD SUB CASE
        * -------------------------------- */
        if (noChild.includes(type)) {

            if ($childSub.hasClass("select2-hidden-accessible")) {
                $childSub.select2("destroy");
            }

            $childSub
                .prop("disabled", true)
                .val("")
                .hide();

            return;
        }

        /* --------------------------------
        * DESTROY EXISTING SELECT2
        * -------------------------------- */
        if ($childSub.hasClass("select2-hidden-accessible")) {
            $childSub.select2("destroy");
        }

        $childSub
            .prop("disabled", false)
            .show()
            .html("");

        /* --------------------------------
        * SAFE DROPDOWN PARENT
        * -------------------------------- */
        let $dropdownParent = $row.closest(".easyel-template-modal, .easyel-edit-template-condition");

        if (!$dropdownParent.length || !$dropdownParent[0].isConnected) {
            $dropdownParent = $(document.body);
        }

        /* --------------------------------
        * INIT SELECT2
        * -------------------------------- */
        $childSub.select2({
            width: "300px",
            dropdownParent: $dropdownParent,
            placeholder: "Search...",
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: easyel_builder_obj.ajax_url,
                type: "POST",
                delay: 250,
                data: function (params) {
                    return {
                        action: "easyel_get_child_sub_options",
                        nonce: easyel_builder_obj.nonce,
                        type: type,
                        search: params.term || "",
                    };
                },
                processResults: function (res) {

                    if (
                        selectedChild &&
                        res &&
                        Array.isArray(res.results) &&
                        !$childSub.find(`option[value="${selectedChild}"]`).length
                    ) {
                        const selectedOption = res.results.find(
                            item => String(item.id) === String(selectedChild)
                        );

                        if (selectedOption) {
                            const option = new Option(
                                selectedOption.text,
                                selectedChild,
                                true,
                                true
                            );

                            $childSub.append(option);
                        }
                    }

                    return res;
                },
                cache: true,
            },
        });

        /* --------------------------------
        * LOAD LABEL IF VALUE FROM DB
        * -------------------------------- */
        if (
            selectedChild &&
            !$childSub.find(`option[value="${selectedChild}"]`).length
        ) {
            $.ajax({
                url: easyel_builder_obj.ajax_url,
                type: "POST",
                data: {
                    action: "easyel_get_child_sub_options",
                    nonce: easyel_builder_obj.nonce,
                    type: type,
                    search: "",
                },
                success: function (res) {
                    if (!res || !Array.isArray(res.results)) {
                        return;
                    }

                    const selectedOption = res.results.find(
                        item => String(item.id) === String(selectedChild)
                    );

                    if (selectedOption) {
                        const option = new Option(
                            selectedOption.text,
                            selectedChild,
                            true,
                            true
                        );

                        $childSub.append(option).trigger("change");
                    }
                },
            });
        }

        /* --------------------------------
        * FINAL SET VALUE
        * -------------------------------- */
        if (selectedChild) {
            $childSub.val(selectedChild).trigger("change");
        }
    },


    saveConditions: function () {
      const conditions = [];
      this.$conditionsWrapper.find(".easyel-condition-row").each(function () {

        console.log( $(this).find(".easyel-condition-sub").val() );
        console.log( $(this).find(".easyel-condition-child-sub").val() );

        conditions.push({
          include: $(this).find(".easyel-include-type").val(),
          main: $(this).find(".easyel-condition-main").val(),
          sub: $(this).find(".easyel-condition-sub").val(),
          child_sub: $(this).find(".easyel-condition-child-sub").val(),
        });
      });

      let easyTmplType = $(".easyel-builder-tmpl-type").val();
      let easyTmplName = $(".easyel-builder-template-name").val();

      $.ajax({
        url: easyel_builder_obj.ajax_url,
        type: "POST",
        data: {
          action: "easyel_save_template_conditions",
          conditions: conditions,
          template_type: easyTmplType,
          template_name: easyTmplName,
          nonce: easyel_builder_obj.nonce,
        },
        success: function (response) {
          if (response.success) {
            $("#easyel-edit-template").attr("href", response.data.edit_url);
            $("#easyel-template-modal").fadeIn().css({
              visibility: "hidden",
              opacity: "0",
            });

            $("#easyel-success-modal").fadeIn().css({
              visibility: "visible",
              opacity: "1",
            });
          } else {
            $(".easyel-template-error-message").html(response.data.message);
          }
        },
        error: function () {
          alert("Something went wrong!");
        },
      });
    },

    updateConditions: function () {
      let $modal = $(".easyel-edit-template-condition");
      let post_id = $modal.attr("data-post-id");
      let template_name = $modal.find(".easyel-builder-template-name").val();
      let template_type = $modal.find(".easyel-builder-tmpl-type").val();

      let conditions = [];

      $modal
        .find(".easyel-conditions-wrapper-edit .easyel-condition-row-edit")
        .each(function () {
          let $row = $(this);
          let include = $row.find(".easyel-include-type").val();
          let main = $row.find(".easyel-condition-main").val();
          let sub = $row.find(".easyel-condition-sub").val();
          let child_sub = $row.find(".easyel-condition-child-sub").val();

          console.log( child_sub );

          conditions.push({
            include: include,
            main: main,
            sub: sub,
            child_sub: child_sub,
          });
        });

      $.ajax({
        url: easyel_builder_obj.ajax_url,
        type: "POST",
        data: {
          action: "easyel_update_builder",
          nonce: easyel_builder_obj.nonce,
          post_id: post_id,
          template_name: template_name,
          template_type: template_type,
          conditions: conditions,
        },
        success: function (res) {
          if (res.success) {
            $modal.fadeOut();
            location.reload();
          } else {
            $(".easyel-template-error-message").html(res.data.message);
          }
        },
      });
    },

  };

  $(document).ready(function () {
    EasyEL.init();
  });

  $(document).on("change", ".easyel-condition-sub", function () {

    const $sub = $(this);
    EasyEL.populateChildSubOptions($sub);

  });


   $(document).ready(function ($) {
    $(".easyel-condition-row .easyel-condition-main, .easyel-condition-row-edit .easyel-condition-main").each(function () {
      let $main = $(this);
      let $row = $main.closest(".easyel-condition-row, .easyel-condition-row-edit");
      let $sub = $row.find(".easyel-condition-sub");

      let selectedSub = $sub.data("selected") || $sub.val() || "all";
      let selectedChild = $row.find(".easyel-condition-child-sub").data("selected") || $row.find(".easyel-condition-child-sub").val() || "";

      EasyEL.populateSubOptions($main, selectedSub, function () {
       
        EasyEL.populateChildSubOptions($sub, selectedChild);
      });
    });


    $(document).on("change", ".easyel-condition-main", function () {
      const $main = $(this);
      const $row = $main.closest(".easyel-condition-row, .easyel-condition-row-edit");
      const $sub = $row.find(".easyel-condition-sub");

      EasyEL.populateSubOptions($main, "all", function () {
        EasyEL.populateChildSubOptions($sub, "");
      });
    });


    function easyelUpdateEditWithElementorUrl() {
      const post_id = $("#easyel-template-modal-edit").attr("data-post-id");

      if (post_id) {
        const editUrl = new URL(
          easyel_builder_obj.admin_url + "post.php",
          window.location.origin
        );
        editUrl.searchParams.set("post", post_id);
        editUrl.searchParams.set("action", "elementor");

        $("#easyel-edit-with-elementor").attr("href", editUrl.toString());
      }
    }

    // Modal open & load data
   $(".type-easy_theme_builder .row-actions .edit a").on(
      "click",
      function (e) {
        e.preventDefault();

        let post_id = $(this).closest("tr").attr("id").replace("post-", "");

        $(".easyel-edit-template-condition").attr("data-post-id", post_id);

        easyelUpdateEditWithElementorUrl();
        

        // AJAX Call
        $.ajax({
          url: easyel_builder_obj.ajax_url,
          type: "POST",
          data: {
            action: "easyel_get_builder",
            nonce: easyel_builder_obj.nonce,
            post_id: post_id,
          },
          success: function (res) {
            if (res.success) {
              let data = res.data;
              console.log(data);

              // Fill modal fields
              $(".easyel-builder-template-name").val(data.template_name);
              $(".easyel-builder-tmpl-type").val(data.template_type);

              const $wrapper = $("#easyel-conditions-wrapper-edit");
              // Reset old conditions
              $wrapper.empty();

              if (data.conditions.length) {
                data.conditions.forEach(function (cond) {
                  let $row = $(easyel_render_condition_row(cond));
                  $row.find(".easyel-condition-child-sub").data("selected", cond.child_sub || "");
                  $wrapper.append($row);
                  let $subSelect  = $(".easyel-condition-sub", $row);
                  const $mainSelect = $(".easyel-condition-main", $row);

                 

                  EasyEL.populateSubOptions($mainSelect, cond.sub || "all", function () {
                    EasyEL.populateChildSubOptions($subSelect, cond.child_sub || "");
                  });

                });
              } else {
                let $row = $(easyel_render_condition_row());
                let $subSelect  = $(".easyel-condition-sub", $row);
                const $mainSelect = $(".easyel-condition-main", $row);
                $wrapper.append($row);
              

                EasyEL.populateSubOptions($mainSelect, "all", function () {
                  EasyEL.populateChildSubOptions($subSelect, "");
                });
              }


              $(".easyel-edit-template-condition").fadeIn().css({
                visibility: "visible",
                opacity: "1",
              });
            } else {
              alert(res.data.message);
            }
          },
        });
      }
    );

    // Close modal
    $(document).on("click", ".easyel-close, .easyel-cancel-btn", function () {
      $(".easyel-edit-template-condition").fadeOut();
      location.reload();
    });

    $(document).on("click", ".easyel-edit-template-condition", function (e) {
      if ($(e.target).is(".easyel-edit-template-condition")) {
        $(".easyel-edit-template-condition").fadeOut();
      }
    });

   $(document).on("click", "#easyel-add-condition-edit", function (e) {
      e.preventDefault();

      const $wrapper = $(".easyel-conditions-wrapper-edit");

      const $row = $(easyel_render_condition_row());
      $wrapper.append($row);

      const $mainSelect  = $row.find(".easyel-condition-main");
      const $subSelect   = $row.find(".easyel-condition-sub");

      EasyEL.populateSubOptions($mainSelect, "all", function () {
        EasyEL.populateChildSubOptions($subSelect, "");
      });
    });


    function easyel_render_condition_row(cond = {}) {
      let include = cond.include || "include";
      let main = cond.main || "";

      return `
        <div class="easyel-condition-row-edit">
            <select class="easyel-include-type">
                <option value="include" ${
                  include == "include" ? "selected" : ""
                }>Include</option>
                <option value="exclude" ${
                  include == "exclude" ? "selected" : ""
                }>Exclude</option>
            </select>
            <select class="easyel-condition-main">
                <option value="archives" ${
                  main == "archives" ? "selected" : ""
                }>Archives</option>
                <option value="singular" ${
                  main == "singular" ? "selected" : ""
                }>Singular</option>
            </select>
            <select class="easyel-condition-sub">
               
            </select>
            <select class="easyel-condition-child-sub">
               
            </select>
            <span class="easyel-remove-row">&times;</span>
        </div>`;
    }
  });


})(jQuery);