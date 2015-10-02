; // semi-colon is a safety net against concatenated scripts and/or other modules which may not be closed properly.
(function ($) {
    var _defaults = {
        notificationIcons: {
            success: 'fa fa-check-square-o text-green',
            warning: 'fa fa-exclamation text-yellow',
            error: 'fa fa-exclamation-triangle text-red'
        },
        url: null
    };

    /**
     * @type {{init: Function, refresh: Function, refreshCounter: Function}}
     */
    var Notifications = {

        init: function () {
            var self = this;

            self.refreshListener();
            self.refreshCounter();
        },

        refresh: function() {
            var self = this;

            $.getJSON(self.settings.url, function(data) {
                var menu = $(self.element).find('.menu');

                $.each(data, function(type, messages) {
                    $.each(messages, function(index, message) {
                        var iconClasses = self.settings.notificationIcons[type] + ' ' + type;
                        menu.append('<li><a href="#"><i class="' + iconClasses + '"></i> ' + message + '</a></li>');
                    });
                });

                self.refreshListener();
                self.refreshCounter();
            });
        },

        refreshListener: function() {
            var self = this;

            $(self.element).find('.menu li a').off('click').on('click', function() {
                $(this).parent().remove();
                self.refreshCounter();
                return false;
            });
        },

        refreshCounter: function () {
            var self = this;

            var successCount = $(self.element).find('.menu li .success').length;
            var warningCount = $(self.element).find('.menu li .warning').length;
            var errorCount = $(self.element).find('.menu li .error').length;
            var totalCount = successCount + warningCount + errorCount;

            $(self.element)
                .find('#notificationCount')
                .removeClass('label-success label-warning label-danger')
                .addClass(errorCount > 0 ? 'label-danger' : (warningCount > 0 ? 'label-warning' : 'label-success'))
                .text(totalCount > 0 ? totalCount : '');
        }
    };

    /**
     * [auto-generated code]
     * The actual module constructor
     *
     * @param element
     * @param options
     * @constructor
     */
    function NotificationsConstructor(element, options) {
        this.element = element;
        this.settings = $.extend({}, _defaults, options);
        this.init();
    }

    /**
     * [auto-generated code]
     * Avoid Module.prototype conflicts
     */
    $.extend(NotificationsConstructor.prototype, Notifications);

    /**
     * [auto-generated code]
     * A module wrapper
     * - preventing against multiple instantiations and
     * - handling method calls and
     * - magic getter & setter
     *
     * @returns {*}
     * @constructor
     */
    $.fn.Notifications = function () {
        window.uiModuleWrapper = window.uiModuleWrapper || new UIModuleWrapper({});
        return window.uiModuleWrapper.handle("Notifications", NotificationsConstructor, this, arguments);
    };

})(jQuery, window, document);