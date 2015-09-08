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
                //TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
                events: '/timeentry/calendar',
                editable: true,
                minTime: '06:00:00',
                maxTime: '21:00:00',
                weekends: false,
                viewRender: function (view, element) {
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
                //TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
                var path = idObj.type == 'entry'
                    ? '/timeentry/' + idObj.id + '/calendar_edit'
                    : '/absence/' + idObj.id + '/calendar_edit';
                var start = event.start;
                var end = event.end;
                var oldTitle = event.title;
                event.title = oldTitle + self.settings.loader;
                $(self.element).fullCalendar('updateEvent', event);
                $.getJSON(
                    path,
                    {
                        id: idObj.id,
                        start: start.unix(),
                        end: end.unix()
                    },
                    function (response) {
                        event.title = oldTitle;
                        $(self.element).fullCalendar('updateEvent', event);
                        if (response.status != 'success') {
                            alert("Update failed! Reload the page and try again.");
                        }
                    }
                );
            }
        },

        copyEvent: function (event) {
            var self = this;
            var idObj = self._splitEventIdString(event.id);
            if (idObj.type != 'undefined') {
                // TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
                var path = idObj.type == 'entry'
                    ? '/timeentry/' + idObj.id + '/copy'
                    : '/absence/' + idObj.id + '/copy';
                var start = event.start;
                var end = event.end;
                var oldTitle = event.title;
                event.title = oldTitle + self.settings.loader;
                $(self.element).fullCalendar('updateEvent', event);
                $.getJSON(
                    path,
                    {
                        start: start.unix(),
                        end: end.unix()
                    },
                    function (response) {
                        location.reload();
                    }
                );
            }
        },

        deleteEntry: function (type, id) {
            var self = this;

            // TODO: use generated path (fos js routing bundle. {{ path('timeentry_edit', { 'id': entity.id }) }})
            var path = type == 'absence'
                ? '/absence/' + id + '/delete'
                : '/timeentry/' + id + '/delete';

            $('#entryModal').find('button[type="button"]').button('loading');
            $.getJSON(
                path,
                {},
                function (response) {
                    if (response.status == 'success') {
                        location.reload();
                    }
                }
            );
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