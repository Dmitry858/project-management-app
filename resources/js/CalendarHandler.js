export default class CalendarHandler {

    isInited = false;
    dayNames = [];
    allDayTitle = 'All day';
    defaultWeekOptions = {};
    defaultMonthOptions = {};
    calendar = null;
    eventObj = null;
    state = {
        'loadedRangeStart': '',
        'loadedRangeEnd': '',
    };
    dateRangeStart = null;
    dateRangeEnd = null;

    constructor(locale, timezoneName, calendars, permissions) {
        this.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.locale = locale;
        this.timezoneName = timezoneName;
        this.calendars = calendars;
        this.permissions = permissions;

        if (locale === 'ru') {
            this.dayNames = ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'];
            this.allDayTitle = 'Весь день';
        }

        this.defaultWeekOptions = {
            startDayOfWeek: 1,
            dayNames: this.dayNames,
            narrowWeekend: false,
            workweek: false,
            showNowIndicator: true,
            showTimezoneCollapseButton: false,
            timezonesCollapsed: false,
            hourStart: 0,
            hourEnd: 24,
            eventView: true,
            taskView: false,
            collapseDuplicateEvents: false,
        }

        this.defaultMonthOptions = {
            startDayOfWeek: 1,
            dayNames: this.dayNames,
        }
    }

    init(Calendar, DatePicker) {
        if (this.isInited) return;
        let that = this;
        this.DatePicker = DatePicker;

        this.calendar = new Calendar('#calendar', {
            usageStatistics: false,
            defaultView: window.innerWidth <= 600 ? 'day' : 'week',
            week: this.defaultWeekOptions,
            month: this.defaultMonthOptions,
            useFormPopup: true,
            useDetailPopup: true,
            calendars: this.calendars,
            timezone: {
                zones: [
                    {
                        timezoneName: this.timezoneName,
                    },
                ],
            },
            template: {
                popupSave() {
                    return that.locale === 'ru' ? 'Добавить' : 'Add';
                },
                popupUpdate() {
                    return that.locale === 'ru' ? 'Сохранить' : 'Update';
                },
                popupEdit() {
                    return that.locale === 'ru' ? 'Редактировать' : 'Edit';
                },
                popupDelete() {
                    return that.locale === 'ru' ? 'Удалить' : 'Delete';
                },
                popupIsAllday() {
                    return that.allDayTitle;
                },
                popupDetailDate({ start, end }) {
                    let startDate = that.getFormattedDateAndTime(new Date(start.d));
                    let endDate = that.getFormattedDateAndTime(new Date(end.d));
                    return `${startDate} - ${endDate}`;
                },
                titlePlaceholder() {
                    return that.locale === 'ru' ? 'Название события' : 'Subject';
                },
                alldayTitle() {
                    return `<span class="toastui-calendar-left-content toastui-calendar-template-alldayTitle">${that.allDayTitle}</span>`;
                },
                timegridDisplayPrimaryTime({ time }) {
                    return `${time.getHours()}:00`;
                },
            },
        });

        this.setDateRange();

        let fromDate = this.calendar.getDateRangeStart().d.d;
        fromDate.setDate(fromDate.getDate() - 7);
        fromDate = fromDate.toLocaleDateString('ru-RU');
        let toDate = this.calendar.getDateRangeEnd().d.d;
        toDate.setDate(toDate.getDate() + 7);
        toDate = toDate.toLocaleDateString('ru-RU');
        this.getUserEvents(fromDate, toDate);

        this.bindJsEvents();

        this.isInited = true;
    }

    bindJsEvents() {
        if (this.isInited) return;
        let that = this;

        this.calendar.on('clickEvent', (eventObj) => {
            this.changePopupOpacity('.toastui-calendar-detail-container');

            setTimeout(() => {
                const editBtn = document.querySelector('.toastui-calendar-edit-button'),
                      deleteBtn = document.querySelector('.toastui-calendar-delete-button');
                if (editBtn && eventObj.event.calendarId !== 'private' && !this.permissions.includes('edit-events-of-projects-and-public-events')) {
                    editBtn.remove();
                }

                if (editBtn) {
                    editBtn.addEventListener('click', that.onClickEditBtn.bind(that, eventObj.event));
                }

                if (deleteBtn && eventObj.event.calendarId !== 'private' && !this.permissions.includes('delete-events-of-projects-and-public-events')) {
                    deleteBtn.remove();
                }
            }, 100);
        });

        this.calendar.on('selectDateTime', (eventObj) => {
            that.eventObj = eventObj;
            that.changePopupOpacity('.toastui-calendar-form-container');

            if (that.locale === 'ru') {
                that.modifyPopupFields(eventObj);
            }

            that.modifyCalendarDropdownSection();
            that.fixAllDayEvents();
        });

        this.calendar.on('beforeCreateEvent', (eventObj) => {
            if (that.locale === 'ru' && this.eventObj) {
                eventObj.start.d.d = this.eventObj.start;
                eventObj.end.d.d = this.eventObj.end;
                eventObj.isAllday = this.eventObj.isAllday;
            }
            this.createNewEvent(eventObj);
        });

        this.calendar.on('beforeUpdateEvent', ({ event, changes }) => {
            let isEmpty = Object.entries(changes).length === 0;

            if (isEmpty) {
                if (this.eventObj && this.eventObj.start != event.start.d.d) {
                    isEmpty = false;
                    changes.start = {
                        'd': {
                            'd': this.eventObj.start
                        },
                        'modified': true
                    };
                }
                if (this.eventObj && this.eventObj.end != event.end.d.d) {
                    isEmpty = false;
                    changes.end = {
                        'd': {
                            'd': this.eventObj.end
                        },
                        'modified': true
                    };
                }
                if (this.eventObj && this.eventObj.isAllday != event.isAllday) {
                    isEmpty = false;
                    changes.isAllday = this.eventObj.isAllday;
                }
            }
            if (isEmpty) return;

            let data = {};
            for (let key in changes) {
                if (key === 'title') {
                    data[key] = changes[key];
                } else if (key === 'start' || key === 'end') {
                    data[key] = changes[key].d.d;
                } else if (key === 'isAllday') {
                    data.is_allday = changes[key] ? 1 : 0;
                    if (changes[key]) {
                        if (!changes.start) {
                            event.start.d.d.setHours(0, 0, 0);
                            data.start = event.start.d.d;
                        }
                        if (!changes.end) {
                            event.end.d.d.setHours(23, 59, 59);
                            data.end = event.end.d.d;
                        }
                    } else {
                        if (this.eventObj && changes.start && this.eventObj.start != changes.start.d.d) {
                            changes.start.d.d = this.eventObj.start;
                        }
                        if (this.eventObj && changes.end && this.eventObj.end != changes.end.d.d) {
                            changes.end.d.d = this.eventObj.end;
                        }
                    }
                } else if (key === 'calendarId') {
                    data.is_private = changes[key] === 'private' ? 1 : 0;
                    if (changes[key].indexOf('project_') !== -1) {
                        data.project_id = Number(changes[key].replace('project_',''));
                    }
                }
            }

            fetch('/events/' + event.id + '?ajax=1', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': this.csrf,
                    'Content-Type': 'application/json;charset=utf-8',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.status && result.status === 'error') {
                        document.querySelector('.error-message').innerText = result.text;
                    } else if ((result.errors || result.exception) && result.message) {
                        document.querySelector('.error-message').innerText = result.message;
                    }

                    if (result.status && result.status === 'success') {
                        if (changes.isAllday !== undefined) {
                            changes.category = changes.isAllday === true ? 'allday' : 'time';
                        }
                        if ((changes.start && changes.start.modified) || (changes.end && changes.end.modified)) {
                            this.recreateEvent(event, changes);
                        } else {
                            this.calendar.updateEvent(event.id, event.calendarId, changes);
                        }
                    }
                })
                .catch((e) => {
                    document.querySelector('.error-message').innerText = e.message;
                });
        });

        this.calendar.on('beforeDeleteEvent', (eventObj) => {
            fetch('/events/' + eventObj.id + '?ajax=1', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrf
                }
            })
                .then(response => response.json())
                .then(result => {
                    if (result.status && result.status === 'error') {
                        document.querySelector('.error-message').innerText = result.text;
                    }

                    if (result.status && result.status === 'success') {
                        this.calendar.deleteEvent(eventObj.id, eventObj.calendarId);
                        this.fixAllDayEvents();
                    }
                })
                .catch((e) => {
                    document.querySelector('.error-message').innerText = e.message;
                });
        });

        document.querySelector('#calendar-view').addEventListener('change', this.onChangeViewSelect.bind(this));

        document.querySelector('button.today').addEventListener('click', this.onClickTodayBtn.bind(this));

        document.querySelector('button.prev').addEventListener('click', this.moveToNextOrPrevRange.bind(this, -1));

        document.querySelector('button.next').addEventListener('click', this.moveToNextOrPrevRange.bind(this, 1));

        setTimeout(() => {
            let alldayPanel = document.querySelector('.toastui-calendar-allday-panel');
            if (alldayPanel) {
                alldayPanel.addEventListener('mousedown', this.fixAllDayEvents.bind(this));
                alldayPanel.addEventListener('mouseup', this.fixAllDayEvents.bind(this));
                let resizeObserver = new ResizeObserver(() => {
                    this.fixAllDayEvents();
                });
                resizeObserver.observe(alldayPanel);
            }
        }, 100);

        let filterCheckboxes = document.querySelectorAll('.calendar-filter .form-check-input');
        if (filterCheckboxes.length > 0) {
            for (let checkbox of filterCheckboxes) {
                checkbox.addEventListener('change', this.onChangeFilterSelect.bind(this));
            }
        }
    }

    onChangeViewSelect(event) {
        this.calendar.changeView(event.currentTarget.value);
        this.setDateRange();
        this.fixAllDayEvents();
    }

    onChangeFilterSelect(event) {
        let calendarId = event.currentTarget.value;
        if (calendarId === 'project') {
            let ids = [];
            for (let cal of this.calendars) {
                if (cal.id.indexOf('project') !== -1) {
                    ids.push(cal.id);
                }
            }
            calendarId = ids;
        }
        this.calendar.setCalendarVisibility(calendarId, event.currentTarget.checked);
        this.fixAllDayEvents();
    }

    onClickTodayBtn() {
        this.calendar.today();
        this.setDateRange();
        this.fixAllDayEvents();
    }

    moveToNextOrPrevRange(offset) {
        if (offset === -1) {
            this.calendar.prev();
        } else if (offset === 1) {
            this.calendar.next();
        }
        this.setDateRange();
        this.fixAllDayEvents();
    }

    onClickEditBtn(eventObj) {
        if (eventObj.start.d) {
            eventObj.start = eventObj.start.d.d;
            eventObj.end = eventObj.end.d.d;
        }
        this.eventObj = eventObj;
        this.changePopupOpacity('.toastui-calendar-form-container');
        this.modifyCalendarDropdownSection();

        if (this.locale === 'ru') {
            this.modifyPopupFields(eventObj);
        }
    }

    changeDateFormat(datePickers) {
        for (let datePicker of datePickers) {
            let input = datePicker.querySelector('input');
            if (input) {
                let dateObj = new Date(input.value);
                input.value = this.getFormattedDateAndTime(dateObj);
            }
        }
    }

    getFormattedDateAndTime(dateObj) {
        let loc = this.locale === 'ru' ? 'ru-RU' : 'en-GB';
        let startDate = dateObj.toLocaleDateString(loc);
        let hours = `${dateObj.getHours()}`.padStart(2, '0');
        let min = `${dateObj.getMinutes()}`.padStart(2, '0');
        return startDate + ' ' + hours + ':' + min;
    }

    changePopupOpacity(selector) {
        setTimeout(() => {
            const popupContainer = document.querySelector('.toastui-calendar-popup-container ' + selector);
            if (popupContainer) {
                popupContainer.classList.add('opacity-100');
            }
        }, 100);
    }

    modifyPopupFields(eventObj) {
        setTimeout(() => {
            const datePickers = document.querySelectorAll('.toastui-calendar-popup-date-picker'),
                  saveBtn = document.querySelector('button.toastui-calendar-popup-confirm');

            if (datePickers.length > 0) {
                this.changeDateFormat(datePickers);

                for (let node of datePickers) {
                    let container = node.querySelector('.toastui-calendar-datepicker-container'),
                        input = node.querySelector('input');

                    this.DatePicker.localeTexts['ru'] = {
                        titles: {
                            // days
                            DD: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                            // daysShort
                            D: ['ВС', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ'],
                            // months
                            MMMM: [
                                'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                                'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                            ],
                            // monthsShort
                            MMM: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек']
                        },
                        titleFormat: 'MMM yyyy',
                        todayFormat: 'D, dd MMMM, yyyy',
                        date: 'Date',
                        time: 'Time',
                        usageStatistics: false
                    };

                    const datepicker = new this.DatePicker(container, {
                        language: 'ru',
                        weekStartDay: 'Mon',
                        timePicker: {
                            usageStatistics: false,
                            showMeridiem: false
                        },
                        date: input.name === 'start' ? eventObj.start : eventObj.end,
                    });

                    datepicker.on('change', () => {
                        this.eventObj[input.name] = datepicker.getDate();
                        let value = this.getFormattedDateAndTime(datepicker.getDate());
                        if (this.eventObj.isAllday) {
                            let arrValue = value.split(' ');
                            value = arrValue[0];
                        }
                        input.value = value;
                        datepicker.close();
                    });

                    input.addEventListener('click', (event) => {
                        event.currentTarget.nextElementSibling.querySelector('.tui-datepicker').classList.toggle('tui-hidden');
                        let anotherInputName = event.currentTarget.name === 'start' ? 'end' : 'start';
                        let anotherInput = event.currentTarget.parentElement.parentElement.querySelector('input[name="'+anotherInputName+'"]')
                        if (anotherInput) {
                            anotherInput.nextElementSibling.querySelector('.tui-datepicker').classList.add('tui-hidden');
                        }
                    });
                }
            }

            if (saveBtn) {
                saveBtn.addEventListener('click', this.validateStartEndDates.bind(this));
            }

            this.modifyAllDayCheckbox(eventObj.isAllday);
        }, 100);
    }

    modifyCalendarDropdownSection() {
        setTimeout(() => {
            const dropdownSection = document.querySelector('.toastui-calendar-popup-section.toastui-calendar-dropdown-section');
            if (!dropdownSection) return;
            dropdownSection.addEventListener('click', this.modifyDropdownItems.bind(this));
        }, 100);
    }

    modifyDropdownItems() {
        setTimeout(() => {
            let menu = document.querySelector('.toastui-calendar-dropdown-menu');
            if (!menu) return;
            const items = menu.querySelectorAll('.toastui-calendar-dropdown-menu-item');

            for (let item of items) {
                let content = item.querySelector('.toastui-calendar-content').innerText;

                if (content !== 'Личное' && content !== 'Private'
                    && !this.permissions.includes('create-events-of-projects-and-public-events')) {
                    item.classList.add('hidden');
                }
            }
            menu.classList.add('opacity-100');
        }, 50);
    }

    modifyAllDayCheckbox(isAllday) {
        const allDayCheckbox = document.querySelector('.toastui-calendar-popup-section-allday');
        if (allDayCheckbox) {
            let clone = allDayCheckbox.cloneNode(true);
            allDayCheckbox.parentElement.append(clone);
            allDayCheckbox.classList.add('hidden');
            clone.classList.add('clone');
            clone.addEventListener('click', this.allDayCheckboxHandler.bind(this));

            if (isAllday) {
                const icon = clone.querySelector('.toastui-calendar-icon'),
                      input = clone.querySelector('input[name="isAllday"]');
                input.value = 'true';
                icon.classList.add('toastui-calendar-ic-checkbox-checked');
                icon.classList.remove('toastui-calendar-ic-checkbox-normal');
                this.toggleTimeDisplay(false, clone);
            }
        }
    }

    allDayCheckboxHandler(event) {
        const icon = event.currentTarget.querySelector('.toastui-calendar-icon'),
              input = event.currentTarget.querySelector('input[name="isAllday"]');
        if (!icon || !input) return;

        if (input.value === 'true') {
            input.value = 'false';
            icon.classList.remove('toastui-calendar-ic-checkbox-checked');
            icon.classList.add('toastui-calendar-ic-checkbox-normal');
            this.eventObj.isAllday = false;
            this.toggleTimeDisplay(true, event.currentTarget);
        } else {
            input.value = 'true';
            icon.classList.remove('toastui-calendar-ic-checkbox-normal');
            icon.classList.add('toastui-calendar-ic-checkbox-checked');
            this.eventObj.isAllday = true;
            this.toggleTimeDisplay(false, event.currentTarget);
        }
    }

    toggleTimeDisplay(show, node) {
        const inputs = node.parentElement.querySelectorAll('input.toastui-calendar-content');

        if (show) {
            for (let input of inputs) {
                if (this.eventObj) {
                    let value = this.getFormattedDateAndTime(this.eventObj[input.name]);
                    input.value = value;
                }
            }
        } else {
            for (let input of inputs) {
                let arrValue = input.value.split(' ');
                input.value = arrValue[0];
            }
        }
    }

    showPreloader() {
        document.getElementById('calendar').classList.add('relative');
        document.getElementById('calendar').insertAdjacentHTML('beforeend', '<div class="preloader-wrap"><div class="preloader"></div></div>');
        document.querySelector('.calendar-header').classList.add('disabled');
    }

    hidePreloader() {
        document.getElementById('calendar').classList.remove('relative');
        document.querySelector('.calendar-header').classList.remove('disabled');
        document.querySelector('.preloader-wrap').remove();
    }

    getUserEvents(from, to) {
        this.showPreloader();
        let url = '/events?ajax=1&from=' + from + '&to=' + to;
        fetch(url)
            .then(response => response.json())
            .then(result => {
                this.hidePreloader();
                if (result.status && result.status === 'error') {
                    document.querySelector('.error-message').innerText = result.text;
                }

                if (result.status && result.status === 'success') {
                    let newEvents = this.controlOfDuplicates(result.result);
                    this.calendar.createEvents(newEvents);
                    this.fixAllDayEvents();
                    this.updateStateOfLoadedRange(from, to);
                }
            })
            .catch((e) => {
                document.querySelector('.error-message').innerText = e.message;
            });
    }

    createNewEvent(eventObj) {
        let data = {
            'title': eventObj.title,
            'start': eventObj.start.d.d,
            'end': eventObj.end.d.d,
            'is_allday': eventObj.isAllday ? 1 : 0,
            'is_private': eventObj.calendarId === 'private' ? 1 : 0,
        };

        if (eventObj.calendarId.indexOf('project_') !== -1) {
            data.project_id = Number(eventObj.calendarId.replace('project_',''));
        }

        fetch('/events?ajax=1', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.csrf,
                'Content-Type': 'application/json;charset=utf-8',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.status && result.status === 'error') {
                    document.querySelector('.error-message').innerText = result.text;
                } else if ((result.errors || result.exception) && result.message) {
                    document.querySelector('.error-message').innerText = result.message;
                }

                if (result.status && result.status === 'success' && result.id) {
                    let newEvent = {
                        id: result.id,
                        calendarId: eventObj.calendarId,
                        title: data.title,
                        start: data.start,
                        end: data.end,
                        state: '',
                    };
                    if (eventObj.isAllday) {
                        newEvent.isAllday = true;
                        newEvent.category = 'allday';
                    }
                    this.calendar.createEvents([newEvent]);
                    this.hideMarks(eventObj.isAllday);
                    this.fixAllDayEvents();
                }
            })
            .catch((e) => {
                document.querySelector('.error-message').innerText = e.message;
            });
    }

    hideMarks(isAllday) {
        let selector = isAllday ? '.toastui-calendar-allday' : '.toastui-calendar-time';
        selector += '.toastui-calendar-grid-selection';
        let marks = document.querySelectorAll(selector);
        if (marks.length > 0) {
            for (let mark of marks) {
                mark.classList.add('hidden');
            }
        }
    }

    fixAllDayEvents() {
        let dateRangeStart = this.dateRangeStart ? new Date(this.dateRangeStart) : this.calendar.getDateRangeStart().d.d,
            dateRangeEnd = this.dateRangeEnd ? new Date(this.dateRangeEnd) : this.calendar.getDateRangeEnd().d.d;

        setTimeout(() => {
            let allDayEventsNodes = document.querySelectorAll('.toastui-calendar-allday-panel .toastui-calendar-weekday-event-block');
            if (allDayEventsNodes.length === 0) return;

            for (let node of allDayEventsNodes) {
                const event = this.calendar.getEvent(node.dataset.eventId, node.dataset.calendarId);
                if (event) {
                    let start = event.start.d.d,
                        end = event.end.d.d;
                    if (start > dateRangeEnd || end < dateRangeStart) {
                        node.remove();
                    }
                }
            }
        }, 30);
    }

    recreateEvent(event, changes) {
        this.calendar.deleteEvent(event.id, event.calendarId);
        let newEvent = {
            id: event.id,
            calendarId: changes.calendarId ? changes.calendarId : event.calendarId,
            title: changes.title ? changes.title : event.title,
            start: changes.start ? changes.start.d.d : event.start.d.d,
            end: changes.end ? changes.end.d.d : event.end.d.d,
            state: '',
        };
        if (changes.isAllday !== undefined) {
            newEvent.category = changes.isAllday === true ? 'allday' : 'time';
        } else {
            if (event.isAllday === true) {
                newEvent.category = 'allday';
            }
        }
        this.calendar.createEvents([newEvent]);
        this.fixAllDayEvents();
    }

    validateStartEndDates(event) {
        const btnParent = event.currentTarget.parentElement.parentElement,
              startInput = btnParent.querySelector('input[name="start"]'),
              endInput = btnParent.querySelector('input[name="end"]'),
              isAlldayInput = btnParent.querySelector('.clone input[name="isAllday"]');

        if (!startInput && !endInput) return;

        let startDate = startInput.value,
            endDate = endInput.value,
            isAllday = isAlldayInput.value === 'true',
            arStartDate = startDate.split(' '),
            arEndDate = endDate.split(' ');

        let formattedStartDate = arStartDate[0].split('.');
        formattedStartDate.reverse();
        formattedStartDate = formattedStartDate.join('-');
        if (arStartDate[1]) {
            formattedStartDate += ' ' + arStartDate[1] + ':00';
        }

        let formattedEndDate = arEndDate[0].split('.');
        formattedEndDate.reverse();
        formattedEndDate = formattedEndDate.join('-');
        if (arEndDate[1]) {
            formattedEndDate += ' ' + arEndDate[1] + ':00';
        }

        let startDateTs = Date.parse(formattedStartDate),
            endDateTs = Date.parse(formattedEndDate);

        if ((endDateTs <= startDateTs && !isAllday) || (endDateTs < startDateTs && isAllday)) {
            event.preventDefault();
            this.showErrorMessage(event.currentTarget,'Дата окончания события должна быть больше даты начала');
            return;
        }

        if (!isAllday) {
            if (arStartDate[0] !== arEndDate[0]) {
                event.preventDefault();
                this.showErrorMessage(event.currentTarget,'Пожалуйста, выберите время в рамках одного дня');
            }
        }
    }

    showErrorMessage(node, text) {
        let error = node.parentElement.querySelector('.error-message');
        if (!error) {
            error = document.createElement('span');
            error.classList.add('error-message');
            error.innerText = text;
            node.parentElement.prepend(error);
        } else {
            error.innerText = text;
        }
    }

    setDateRange() {
        let loc = this.locale === 'ru' ? 'ru-RU' : 'en-GB',
            dateRangeStart = this.calendar.getDateRangeStart().d.d.toLocaleDateString(loc),
            dateRangeEnd = this.calendar.getDateRangeEnd().d.d.toLocaleDateString(loc),
            dateRange = document.getElementById('date-range');

        if (dateRangeStart === dateRangeEnd) {
            dateRange.innerText = dateRangeStart;
        } else {
            dateRange.innerText = dateRangeStart + ' - ' + dateRangeEnd;
        }

        this.dateRangeStart = this.calendar.getDateRangeStart().d.d.getTime();
        this.dateRangeEnd = this.calendar.getDateRangeEnd().d.d.getTime();
        this.checkLoadedRange();
    }

    checkLoadedRange() {
        if (!this.state.loadedRangeStart && !this.state.loadedRangeEnd) {
            return;
        }
        let rangeStartDate = this.calendar.getDateRangeStart().d.d.toLocaleDateString('ru-RU'),
            rangeEndDate = this.calendar.getDateRangeEnd().d.d.toLocaleDateString('ru-RU'),
            rangeStartDateTs = this.getDateTimestamp(rangeStartDate),
            rangeEndDateTs = this.getDateTimestamp(rangeEndDate),
            loadedRangeStartTs = this.getDateTimestamp(this.state.loadedRangeStart),
            loadedRangeEndTs = this.getDateTimestamp(this.state.loadedRangeEnd);

        if (rangeStartDateTs < loadedRangeStartTs || rangeEndDateTs > loadedRangeEndTs) {
            let fromDate, toDate;
            if (rangeStartDateTs < loadedRangeStartTs) {
                fromDate = this.calendar.getDateRangeStart().d.d;
                fromDate.setDate(fromDate.getDate() - 7);
                fromDate = fromDate.toLocaleDateString('ru-RU');
                toDate = new Date(loadedRangeStartTs);
                toDate.setDate(toDate.getDate() - 1);
                toDate = toDate.toLocaleDateString('ru-RU');
            }

            if (rangeEndDateTs > loadedRangeEndTs) {
                if (!fromDate) {
                    fromDate = new Date(loadedRangeEndTs);
                    fromDate.setDate(fromDate.getDate() + 1);
                    fromDate = fromDate.toLocaleDateString('ru-RU');
                }
                toDate = this.calendar.getDateRangeEnd().d.d;
                toDate.setDate(toDate.getDate() + 7);
                toDate = toDate.toLocaleDateString('ru-RU');
            }

            this.getUserEvents(fromDate, toDate);
        }
    }

    getDateTimestamp(date) {
        let arDate = date.split('.');
        arDate.reverse();
        let dateString = arDate.join('-');
        return Date.parse(dateString);
    }

    updateStateOfLoadedRange(fromDate, toDate) {
        if (!this.state.loadedRangeStart) {
            this.state.loadedRangeStart = fromDate;
        } else {
            let fromDateTs = this.getDateTimestamp(fromDate),
                loadedRangeStartTs = this.getDateTimestamp(this.state.loadedRangeStart);
            if (fromDateTs < loadedRangeStartTs) {
                this.state.loadedRangeStart = fromDate;
            }
        }

        if (!this.state.loadedRangeEnd) {
            this.state.loadedRangeEnd = toDate;
        } else {
            let toDateTs = this.getDateTimestamp(toDate),
                loadedRangeEndTs = this.getDateTimestamp(this.state.loadedRangeEnd);
            if (toDateTs > loadedRangeEndTs) {
                this.state.loadedRangeEnd = toDate;
            }
        }
    }

    controlOfDuplicates(newEvents) {
        const filteredEvents = [],
              currentEventsIds = [];
        let iterator = this.calendar.store.getState().calendar.events.internalMap.entries();
        for (let item of iterator) {
            currentEventsIds.push(item[1].id);
        }
        for (let item of newEvents) {
            if (!currentEventsIds.includes(item.id)) {
                filteredEvents.push(item);
            }
        }
        return filteredEvents;
    }

}
