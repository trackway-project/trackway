; // semi-colon is a safety net against concatenated scripts and/or other modules which may not be closed properly.
(function ($) {
    var _defaults = {
        entryId: 'newEntry',
        absenceId: 'newAbsence',
        loader: '<div class="modal-body"><i class="fa fa-spinner fa-pulse"></i></div>'
    };

    /**
     *
     * @type {{init: Function, foo: Function, _foo2: Function}}
     */
    var EntryModal = {
        /**
         * initial method to set up module
         */
        init: function () {
            var self = this;

            if (this.settings.entryUrl == null) {
                jQuery.error('Required setting "entryUrl" can not be empty');
            }
            if (this.settings.absenceUrl == null) {
                jQuery.error('Required setting "absenceUrl" can not be empty');
            }

            $(self.element).find('#modalTabs').find('a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            $(self.element).find('#modalTabs').on('shown.bs.tab', function (e) {
                var type = $(e.target).data('type');
                if (type == 'entry') {
                    self.newEntry();
                } else if (type == 'absence') {
                    self.newAbsence();
                }
            });
        },

        setDate: function (date) {
            var self = this;
            self.settings.date = date;
        },

        newEntry: function () {
            var self = this;
            self._showTabs();
            self._form(self.settings.entryUrl, self.settings.entryId, true);
        },

        newAbsence: function () {
            var self = this;
            self._showTabs();
            self._form(self.settings.absenceUrl, self.settings.absenceId, true);
        },

        editEntry: function (id) {
            var self = this;
            self._showEntryTab();
            // TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
            self._form('/timeentry/' + id + '/edit', self.settings.entryId, false);
        },

        editAbsence: function (id) {
            var self = this;
            self._showAbsenceTab();
            // TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
            self._form('/absence/' + id + '/edit', self.settings.entryId, false);
        },

        _form: function (url, divId, useDate) {
            var self = this;
            $(self.element).find('#' + divId).html(self.settings.loader);
            $(self.element).modal('show');
            var data = useDate ? {start: self.settings.date.unix()} : {};
            $.ajax({
                url: url,
                data: data,
                cache: false
            }).done(function (html) {
                $(self.element).find('#' + divId).html(html);
                $(self.element).find('#' + divId).find('form').submit(function (event) {
                    var form = $(this);
                    form.find('button[type="submit"]').button('loading');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: form.serialize(),
                        success: function (response) {
                            if (response == '') {
                                location.reload();
                            } else {
                                $(self.element).find('#' + divId).html(response);
                            }
                        }
                    });
                    return false;
                });
            });
        },

        _showTabs: function () {
            var self = this;
            $(self.element).find('.nav-tabs-custom li').show();
        },

        _hideTabs: function () {
            var self = this;
            $(self.element).find('.nav-tabs-custom li').hide();
        },

        _showEntryTab: function () {
            var self = this;
            self._hideTabs();
            $(self.element).find('.nav-tabs-custom li#entryModalNavTab').show();
        },

        _showAbsenceTab: function () {
            var self = this;
            self._hideTabs();
            $(self.element).find('.nav-tabs-custom li#absenceModalNavTab').show();
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
    function EntryModalConstructor(element, options) {
        this.element = element;
        this.settings = $.extend({}, _defaults, options);
        this.init();
    }

    /**
     * [auto-generated code]
     * Avoid Module.prototype conflicts
     */
    $.extend(EntryModalConstructor.prototype, EntryModal);

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
    $.fn.EntryModal = function () {
        window.uiModuleWrapper = window.uiModuleWrapper || new UIModuleWrapper({});
        return window.uiModuleWrapper.handle("EntryModal", EntryModalConstructor, this, arguments);
    };

})(jQuery, window, document);