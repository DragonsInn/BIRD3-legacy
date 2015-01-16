/**
 * bootstrap-ddselect.js
 * v1.0.1
 *
 * By Martin Eriksson
 */
(function ($) {

    "use strict";

    var DDSelect = (function () {

        // Constructor
        //
        function DDSelect(element, options) {
            var that = this;

            // Keep references to our jQuery elements
            //
            this.$select = $(element);
            this.$btnGroup = $(DDSelect.template);

            // Apply our options
            //
			if (options.parentClass) {
				this.$btnGroup.addClass(options.parentClass);
			}
            if (options.buttonClass) {
                this.$btnGroup.find('.btn').addClass(options.buttonClass);
            }
            if (options.sizeClass) {
                this.$btnGroup.find('.btn').addClass(options.sizeClass);
            }
            if (options.dropup) {
                this.$btnGroup.addClass('dropup');
            }

            // Initialize our DropDown and hide the Select element.
            //
            this.$select.hide().after(this.$btnGroup);

            // Disable click on the button
            //
            this.$btnGroup.find('.btn').on('click', function (e) {
                e.preventDefault();
            });

            // Capture the change event of the select to update our display value
            //
            this.$select.on('change', function (e) {
                that.$btnGroup.find('.btn').first().text($(this).find(':selected').text());
            });

            // Add the button group HTML
            //
            this.refresh();
        }

        DDSelect.prototype.update = function () {
            var currentSelected = this.$select.find(':selected');

            // Set the text of the DropDown as of the currently selected option text
            //
            this.$btnGroup.find('.btn .text').text(currentSelected.text());

            // Check if the Select element is disabled
            //
            this.$btnGroup.find('.btn').toggleClass('disabled', this.$select.is(':disabled'));
        };

        DDSelect.prototype.refeshOptions = function () {
            var that = this;

            // Remove all Options from the DropDown
            //
            this.$btnGroup.find('.dropdown-menu').empty();

            // Adds a single option to the DropDown
            //
            function addOption($option, $parent) {
                var $link = $('<a href="" />').attr('data-value', $option.attr('value')).text($option.text()),
                    $listItem = $('<li />').append($link);

                if ($option.is(':disabled')) {
                    $listItem.addClass('disabled');
                    $link.attr('tabindex', '-1').attr('href', '#');
                } else {
                    $link.on('click', function (e) {
                        e.preventDefault();
                        $(this).parents('.btn-group').prev('select').val($(this).attr('data-value'));
                        that.refresh();
                    });
                }

                $parent.append($listItem);
            }

            // Adds an option group to the DropDown
            //
            function addOptionGroup($optgroup, $parent) {
                var $submenu = $('<li class="dropdown-submenu"><a tabindex="-1" href="#">' + $optgroup.attr('label') + '</a><ul class="dropdown-menu"></ul></li>');

                $optgroup.children('option').each(function () {
                    addOption($(this), $submenu.children('.dropdown-menu'));
                });

                $parent.append($submenu);
            }

            // Add all items to the DropDown
            //
            this.$select.children().each(function () {
                var $this = $(this);

                if ($this.is('optgroup')) {
                    addOptionGroup($this, that.$btnGroup.children('.dropdown-menu'));
                } else if ($this.is('option')) {
                    addOption($this, that.$btnGroup.children('.dropdown-menu'));
                }
            });
        };

        DDSelect.prototype.refresh = function () {
            this.update();
            this.refeshOptions();
        };

        DDSelect.prototype.getApi = function () {
            var that = this;

            return {
                refresh: function () {
                    that.refresh();
                },
                show: function () {
                    that.$btnGroup.show();
                },
                hide: function () {
                    that.$btnGroup.hide();
                }
            };
        };

        DDSelect.template = '<div class="btn-group">' +
            '<button class="btn" data-toggle="dropdown"><span class="text" /></button>' +
            '<button class="btn dropdown-toggle" data-toggle="dropdown">' +
            '<span class="caret" />' +
            '</button>' +
            '<ul class="dropdown-menu" />' +
            '</div>';

        return DDSelect;

    })();

    // The jQuery Plugin API
    //
    $.fn.ddselect = function () {

        var options = {},
            method = '',
            dataKey = 'ddselect';

        // Parse the arguments
        //
        if (typeof arguments[0] === 'string') {
            action = arguments[0];
        } else {
            options = arguments[0];
        }

        // Merge the options with the default ones
        //
        options = $.extend({}, $.fn.ddselect.defaultOptions, options);

        // Return the jQuery chain
        //
        return this.each(function () {
			var ddSelect = $(this).data(dataKey),
				api = null;

			if (ddSelect) {
				api = ddSelect.getApi();
				method = 'refresh';
			}

            if (method) {
				if (api[method]) {
					api[method]();
				} else {
					$.error('Method ' + method + ' does not exist on jQuery.dropdownselect');
				}
            } else {
				$(this).data(dataKey, new DDSelect(this, options));
            }
        });
    };

    // The default options
    //
    $.fn.ddselect.defaultOptions = {
        dropup: false,
		parentClass: 'ddselect',
        buttonClass: '',
        sizeClass: ''
    };

})(jQuery);
