// Mondestin
$(function () {
    $('.delete-data').on('click', function () {
        // get the form id
        var dataId = $(this).data('id');
        $.confirm({
            icon: 'fa-solid fa-question',
            theme: 'modern',
            closeIcon: true,
            title: 'Confirmation',
            content: 'Etes vous sûre de vouloir supprimer?',
            animation: 'scale',
            type: 'red',
            buttons: {
                Yes: {
                    text: '<i class="fa-solid fa-trash"></i> Oui, supprimer',
                    btnClass: 'btn-red',
                    action: function () {
                        // submit the corresponding form to delete the data
                        document.getElementById(dataId).submit();
                    }
                },
                No: {
                    text: '<i class="fa-solid fa-xmark"></i> Annuler',
                    btnClass: 'btn-primary',
                    action: function () { }
                }

            }
        });
    });
})