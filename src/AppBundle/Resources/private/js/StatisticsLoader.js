; // semi-colon is a safety net against concatenated scripts and/or other modules which may not be closed properly.
(function ($) {
    var _defaults = {
        statisticsUrl: '/timeentry/statistics'
    };

    /**
     * The module StatisticsLoader loads statistics and displays them
     *
     * @type {{init: Function, updateDates: Function, _updateStatistics: Function}}
     */
    var StatisticsLoader = {
        /**
         * initial method to set up module
         */
        init: function () {
            var self = this;

            if (this.settings.startDate == null) {
                jQuery.error('Required setting "startDate" can not be empty');
            }
            if (this.settings.endDate == null) {
                jQuery.error('Required setting "endDate" can not be empty');
            }

            self._updateStatistics();
        },

        /**
         *
         * @param startDate
         * @param endDate
         */
        updateDates: function (startDate, endDate) {
            var self = this;

            self.settings.startDate = startDate;
            self.settings.endDate = endDate;
            self._updateStatistics();
        },

        /**
         * @private
         */
        _updateStatistics: function () {
            var self = this;

            $.getJSON(
                self.settings.statisticsUrl,
                {
                    start: self.settings.startDate,
                    end: self.settings.endDate
                },
                function (data) {
                    var days = 0;
                    var entrySum = 0;
                    var absenceSum = 0;
                    $.each(data, function (key, val) {
                        if (val.entry) {
                            entrySum += val.entry;
                        }
                        if (val.absence) {
                            absenceSum += val.absence;
                        }
                        days++;
                    });
                    var html = '';
                    if (entrySum > 0 ) {
                        html += "Entry sum: " + entrySum + 'h<br/>';
                    }
                    if (absenceSum > 0 ) {
                        html += "Absence sum: " + absenceSum + 'h<br/>';
                    }
                    var average = (entrySum + absenceSum) / days;
                    if (average > 0 ) {
                        html += "<strong>Average: " + average + "h</strong>";
                    }
                    $(self.element).find('td').html(html);
                }
            );
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
    function StatisticsLoaderConstructor(element, options) {
        this.element = element;
        this.settings = $.extend({}, _defaults, options);
        this.init();
    }

    /**
     * [auto-generated code]
     * Avoid Module.prototype conflicts
     */
    $.extend(StatisticsLoaderConstructor.prototype, StatisticsLoader);

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
    $.fn.StatisticsLoader = function () {
        window.uiModuleWrapper = window.uiModuleWrapper || new UIModuleWrapper({});
        return window.uiModuleWrapper.handle("StatisticsLoader", StatisticsLoaderConstructor, this, arguments);
    };

})(jQuery, window, document);