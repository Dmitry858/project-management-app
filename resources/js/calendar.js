const Calendar = require('@toast-ui/calendar');
const DatePicker = require('tui-date-picker');
require('@toast-ui/calendar/dist/toastui-calendar.min.css');
require('tui-date-picker/dist/tui-date-picker.css');
require('tui-time-picker/dist/tui-time-picker.css');
import CalendarHandler from './CalendarHandler';

let calendarHandler = new CalendarHandler(locale, timezoneName, calendars, permissions);
calendarHandler.init(Calendar, DatePicker);
