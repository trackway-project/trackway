; // semi-colon is a safety net against concatenated scripts and/or other modules which may not be closed properly.
(function ($) {
    var _defaults = {
        loader: ' <i class="fa fa-spinner fa-pulse"></i>',
        statisticsInitialized: false
    };

    /**
     *
     * @type {{init: Function, foo: Function, _foo2: Function}}
     */
    var TrackwayCalendar = {
        /**
         * initial method to set up module
         */
        init: function () {
            var self = this;

            $(self.element).fullCalendar({
                header: {
                    left: 'agendaDay,agendaWeek,month',
                    center: 'title'

                },
                defaultView: 'agendaWeek',
                height: self._getCalendarHeight(),
                allDaySlot: false,
                events: Routing.generate('calendar_events'),
                editable: true,
                scrollTime: '06:00:00',
                weekends: false,
                loading: function (view, element) {
                    self._updateStatistics();
                },
                dayClick: function (date, jsEvent, view, resourceObj) {
                    $('#entryModal').EntryModal('setDate', date);
                    $('#entryModal').EntryModal('newEntry');
                },
                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
                    if (key.command || key.control) {
                        self.copyEvent(event);
                    } else {
                        self.editEventTime(event);
                    }
                },
                eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                    self.editEventTime(event);
                },
                eventRender: function (event, element) {
                    element.find('.fc-title').html(event.title);
                },
                eventClick: function (event, jsEvent, view) {
                    self.editEvent(event);
                }
            });

            $(window).resize(function () {
                $(self.element).fullCalendar('option', 'height', self._getCalendarHeight());
            });

            $(window).keydown(function() {
                if (key.command || key.control) {
                    $('.fc-event.fc-draggable').addClass('copy');
                }
            });
            $(window).keyup(function() {
                if (!key.command && !key.control) {
                    $('.fc-event.fc-draggable.copy').removeClass('copy');
                }
            });
        },

        editEvent: function (event) {
            var self = this;
            var idObj = self._splitEventIdString(event.id);
            if (idObj.type == 'entry') {
                $('#entryModal').EntryModal('editEntry', idObj.id);
            } else if (idObj.type == 'absence') {
                $('#entryModal').EntryModal('editAbsence', idObj.id);
            }
        },

        editEventTime: function (event) {
            var self = this;
            var idObj = self._splitEventIdString(event.id);
            if (idObj.type != 'undefined') {
                var path = idObj.type == 'entry'
                    ? Routing.generate('timeentry_calendar_edit', {id: idObj.id})
                    : Routing.generate('absence_calendar_edit', {id: idObj.id});
                var start = event.start;
                var end = event.end;
                var oldTitle = event.title;
                event.title = oldTitle + self.settings.loader;
                $(self.element).fullCalendar('updateEvent', event);
                $.getJSON(
                    path,
                    {
                        id: idObj.id,
                        start: start.format(),
                        end: end.format()
                    },
                    function (response) {
                        event.title = oldTitle;
                        $(self.element).fullCalendar('updateEvent', event);
                        if (response.status != 'success') {
                            alert("Update failed! Reload the page and try again.");
                        } else {
                            $(self.element).fullCalendar('refetchEvents');

                            // refresh notifications in background
                            $('.notifications-menu').Notifications('refresh');
                        }
                    }
                );
            }
        },

        copyEvent: function (event) {
            var self = this;
            var idObj = self._splitEventIdString(event.id);
            if (idObj.type != 'undefined') {
                var path = idObj.type == 'entry'
                    ? Routing.generate('timeentry_calendar_copy', {id: idObj.id})
                    : Routing.generate('absence_calendar_copy', {id: idObj.id});
                var start = event.start;
                var end = event.end;
                var oldTitle = event.title;
                event.title = oldTitle + self.settings.loader;
                $(self.element).fullCalendar('updateEvent', event);
                $.getJSON(
                    path,
                    {
                        start: start.format(),
                        end: end.format()
                    },
                    function (response) {
                        $(self.element).fullCalendar('refetchEvents');

                        // refresh notifications in background
                        $('.notifications-menu').Notifications('refresh');
                    }
                );
            }
        },

        _splitEventIdString: function (idString) {
            var returnVal = {
                type: 'undefined',
                id: 0
            };
            if (idString.indexOf('entry_') > -1) {
                returnVal.type = 'entry';
            }
            if (idString.indexOf('absence_') > -1) {
                returnVal.type = 'absence';
            }
            if (returnVal.type != 'undefined') {
                returnVal.id = idString.substr(idString.indexOf('_') + 1);
            }
            return returnVal;
        },

        _getCalendarHeight: function () {
            return $(window).height() - 310;
        },

        _updateStatistics: function () {
            var self = this;
            if (!self.settings.statisticsInitialized) {
                $('#statistics').StatisticsLoader({
                    startDate: $(self.element).fullCalendar('getView').start.format(),
                    endDate: $(self.element).fullCalendar('getView').end.format()
                });
                self.settings.statisticsInitialized = true;
            } else {
                $('#statistics').StatisticsLoader(
                    'updateDates',
                    $(self.element).fullCalendar('getView').start.format(),
                    $(self.element).fullCalendar('getView').end.format()
                );
            }
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
    function TrackwayCalendarConstructor(element, options) {
        this.element = element;
        this.settings = $.extend({}, _defaults, options);
        this.init();
    }

    /**
     * [auto-generated code]
     * Avoid Module.prototype conflicts
     */
    $.extend(TrackwayCalendarConstructor.prototype, TrackwayCalendar);

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
    $.fn.TrackwayCalendar = function () {
        window.uiModuleWrapper = window.uiModuleWrapper || new UIModuleWrapper({});
        return window.uiModuleWrapper.handle("TrackwayCalendar", TrackwayCalendarConstructor, this, arguments);
    };

})(jQuery, window, document);