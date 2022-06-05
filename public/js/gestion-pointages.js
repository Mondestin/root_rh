// Mondestin
$(function () {
    $('.modifyData').on('click', function () {
        // get the form with a specific id
        var dataId = $(this).data('id');
        document.getElementById(dataId).submit();

    });
})