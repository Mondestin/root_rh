document.addEventListener('DOMContentLoaded', function () {

    const connectedUserEmail = $("#connectedUserEmail").val()

    $('#shareEventBtn').click((e) => {

        // Reset previous users list generated (shared with users)
        $('.sharedWith').remove()

        // Getting the current planning ID clicked
        const planningId = $('#eventIdInput').val()
        console.log(planningId)

        // Setting the planningId input
        $('#planningIdInput').val(planningId)

        // Getting the current planning ID as an object
        var currentPlanning = plannings.filter(p => p.id == planningId)[0]

        // Getting all attendees who participate into the event
        var currentAttendees = currentPlanning.attendees
        // Populate users list (shared with)
        if (currentAttendees && currentAttendees.length > 1) {
            currentAttendees.forEach(user => {
                if (user.email !== connectedUserEmail) {
                    const userBadgeElt = "<br class='sharedWith' /><span class='badge badge-success mr-2 sharedWith'>" + user.email + "</span>"
                    $("#sharedWith").append(userBadgeElt)
                }
            });
        } else {
            const userBadgeElt = "<span class='sharedWith text-sm'>Aucun utilisateur</span><br class='sharedWith' />"
            $("#sharedWith").append(userBadgeElt)
        }



    })
})