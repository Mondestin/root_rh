/**
 * Author: Johan Mickaël
 * Description: This will render the calendar and all external events
 */

/* initialize the external events */
document.addEventListener('DOMContentLoaded', function () {
    let containerEl = document.getElementById('external-events');
    new FullCalendar.Draggable(containerEl, {
        itemSelector: '.external-event',
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText,
                backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color')
            };
        }
    });
    calendar.render();

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default

    // Color chooser button
    $('#color-chooser > li > span').click(function (e) {
        e.preventDefault()
        // Save color
        currColor = $(this).css('color')
        // Add color effect to button
        $('#add-new-event').css({
            'background-color': currColor,
            'border-color': currColor
        })
    })

    $('#add-new-event').click(function (e) {
        e.preventDefault()
        // Get value and make sure it is not null
        var val = $('#new-event').val()
        if (val.length == 0) {
            return
        }

        // Create events
        var event = $('<div />')
        event.css({
            'background-color': currColor,
            'border-color': currColor,
            'color': '#fff'
        }).addClass('external-event')
        event.text(val)
        $('#external-events').prepend(event)

        // Remove event from text input
        $('#new-event').val('')
    })

    $('#editEventBtn').click((e) => {
        e.preventDefault()
        const eventId = $('#eventIdInput').val()
        let event = calendar.getEventById(eventId)
        event.setProp('title', $("#eventTitleInput").val())
        event.setStart($("#eventStartInput").val())
        event.setEnd($("#eventEndInput").val())
        event.setProp('backgroundColor', $("#eventColorInput").val())
        event.setProp('borderColor', $("#eventColorInput").val())
        event.setProp('textColor', $("#eventTextColorInput").val())
        editEvent(event)
        $("#eventModal").modal('hide')
    })

    $('#removeEventBtn').click((e) => {
        if (!confirm("Supprimer cet évènement ?")) return;
        e.preventDefault()
        const eventId = $('#eventIdInput').val()
        let event = calendar.getEventById(eventId)
        event.remove()
        deleteEvent(event)
        $("#eventModal").modal('hide')
    })
});