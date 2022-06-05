/**
 * Author: Johan Mickaël
 * Description: This file is required to save event into the database
 */

// Asynchronous method to save event into the database
async function addEvent(dropInfo) {
    // Sending ajax request to the server to update the current event into the database
    const event = dropInfo.event
    $.ajax({
        url: '/plannings/new',
        data: 'title=' + event.title +
            '&start=' + event.startStr +
            '&allDay=' + event.allDay +
            '&color=' + event.backgroundColor +
            '&textColor=' + event.textColor,
        type: "POST",
        success: async function (json) {
            // Setting the id for the new event dropped
            json = await JSON.parse(json)
            event.setProp('id', json.id)
            plannings.push(event)
            Toast.fire({
                icon: 'success',
                title: 'Sauvegardé'
            })
        },
        error: function (err) {
            Toast.fire({
                icon: 'error',
                title: err.statusText
            })
        }
    });
}

// Ajax method to update a specific event into the database
function editEventCallback(dropInfo) {
    editEvent(dropInfo.event)
}

function editEvent(event) {
    $.ajax({
        url: '/plannings/edit',
        data: 'id=' + event.id +
            '&title=' + event.title +
            '&start=' + event.startStr +
            '&end=' + event.endStr +
            '&allDay=' + event.allDay +
            '&color=' + event.backgroundColor +
            '&textColor=' + event.textColor,
        type: "POST",
        success: function (json) {
            hideEventEditForm()
            Toast.fire({
                icon: 'success',
                title: 'Sauvegardé'
            })
        },
        error: function (err) {
            Toast.fire({
                icon: 'error',
                title: err.statusText
            })
        }
    });
}

function deleteEvent(event) {
    $.ajax({
        url: '/plannings/delete',
        data: 'id=' + event.id,
        type: "POST",
        success: function (json) {
            hideEventEditForm()
            Toast.fire({
                icon: 'info',
                title: 'Supprimé'
            })
        },
        error: function (err) {
            Toast.fire({
                icon: 'error',
                title: err.statusText
            })
        }
    });
}

function initializeEventEditInputs(eventClickInfo) {
    $('#cardEventEdit').show()
    $("#eventTitleInput").val(eventClickInfo.event.title)
    $("#eventColorInput").val(rgbToHex(eventClickInfo.event.backgroundColor))
    $("#eventTextColorInput").val(rgbToHex(eventClickInfo.event.textColor))
    $("#eventStartInput").val(eventClickInfo.event.startStr.slice(0, 19))
    $("#eventEndInput").val(eventClickInfo.event.endStr.slice(0, 19))
    $("#eventAllDayInput").val(eventClickInfo.event.allDay)
    $("#eventIdInput").val(eventClickInfo.event.id)
}

function hideEventEditForm() {
    $('#cardEventEdit').hide()
}