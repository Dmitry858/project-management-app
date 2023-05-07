export default class CalendarHandler {

    isInited = false;
    dayNames = [];
    allDayTitle = 'All day';
    defaultWeekOptions = {};
    calendar = null;
    eventObj = null;

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
    }

    init(Calendar, DatePicker) {
        if (this.isInited) return;
        let that = this;
        this.DatePicker = DatePicker;

        this.calendar = new Calendar('#calendar', {
            usageStatistics: false,
            defaultView: window.innerWidth <= 600 ? 'day' : 'week',
            week: this.defaultWeekOptions,
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

        this.showPreloader();

        this.getUserEvents();

        this.bindJsEvents();

        this.isInited = true;
    }

    bindJsEvents() {
        if (this.isInited) return;
        let that = this;

        this.calendar.on('clickEvent', (eventObj) => {
            console.log('clickEvent');
            console.log(eventObj.event);
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
            console.log('selectDateTime');
            console.log(eventObj);
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
            console.log('beforeUpdateEvent');
            console.log(event);
            console.log(changes);
        });

        this.calendar.on('beforeDeleteEvent', (eventObj) => {
            console.log('beforeDeleteEvent');
            console.log(eventObj);

            this.calendar.deleteEvent(eventObj.id, eventObj.calendarId);
        });

        document.querySelector('button.today').addEventListener('click', this.onClickTodayBtn.bind(this));

        document.querySelector('button.prev').addEventListener('click', this.moveToNextOrPrevRange.bind(this, -1));

        document.querySelector('button.next').addEventListener('click', this.moveToNextOrPrevRange.bind(this, 1));

        setTimeout(() => {
            let alldayPanel = document.querySelector('.toastui-calendar-allday-panel');
            if (alldayPanel) {
                alldayPanel.addEventListener('mousedown', this.fixAllDayEvents.bind(this));
                alldayPanel.addEventListener('mouseup', this.fixAllDayEvents.bind(this));
            }
        }, 100);
    }

    onClickTodayBtn() {
        this.calendar.today();
        this.fixAllDayEvents();
    }

    moveToNextOrPrevRange(offset) {
        if (offset === -1) {
            this.calendar.prev();
        } else if (offset === 1) {
            this.calendar.next();
        }
        this.fixAllDayEvents();
    }

    onClickEditBtn(eventObj) {
        console.log('onClickEditBtn');
        if (eventObj.start.d) {
            eventObj.start = eventObj.start.d.d;
            eventObj.end = eventObj.end.d.d;
        }
        this.eventObj = eventObj;
        this.changePopupOpacity('.toastui-calendar-form-container');

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
            const datePickers = document.querySelectorAll('.toastui-calendar-popup-date-picker');

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
                    console.log(input.name);
                    console.log(this.eventObj);
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

    getUserEvents() {
        fetch('/events?ajax=1')
            .then(response => response.json())
            .then(result => {
                this.hidePreloader();

                console.log(result);
                if (result.status && result.status === 'error') {
                    document.querySelector('.error-message').innerText = result.text;
                }

                if (result.status && result.status === 'success') {
                    this.calendar.createEvents(result.result);
                    this.fixAllDayEvents();
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
        let dateRangeStart = this.calendar.getDateRangeStart(),
            dateRangeEnd = this.calendar.getDateRangeEnd();

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

}
