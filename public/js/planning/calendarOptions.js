/**
 * Author: Johan Mickaël
 * Description: We specify here all options needed for the calendar
 */
var plannings = []
var newEvents = []
var calendarEl = document.getElementById('calendar');
const calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: "local",
    locale: 'fr',
    themeSystem: 'bootstrap',
    aspectRatio: 2.2,
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: 'prev,next today', // will normally be on the left. if RTL, will be on the right
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    eventResizableFromStart: true,
    editable: true,
    droppable: true,
    nowIndicator: true,
    eventReceive: addEvent,
    eventResize: editEventCallback,
    eventDrop: editEventCallback,
    eventClick: initializeEventEditInputs,
    eventSources: [
        {
            url: '/plannings/json',
            success: (data) => {
                plannings = JSON.parse(data)
                return plannings
            },
            method: 'POST',
            failure: (err) => {
                console.error(err)
                alert(err.message);
            }
        }
    ]
});

var Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 3000
});

// Helper to convert color in rgb format to hexadecimal
function rgbToHex(colorFormat) {
    // If the color is already in hexadecimal
    if (colorFormat.charAt(0) === '#') return colorFormat;
    return '#' + colorFormat.substr(4, colorFormat.indexOf(')') - 4).split(',').map((color) => parseInt(color).toString(16).padStart(2, '0')).join('');
}