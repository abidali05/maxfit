(function ($) {
    "use strict";

    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    if ($.fn.DataTable) {
        $('.datatable').each(function () {
            if ($.fn.dataTable.isDataTable(this)) {
                return;
            }

            $(this).DataTable({
                paging: true,
                searching: false,
                info: true,
                lengthChange: false,
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                order: [],
                dom: "<'dt-shell'rt<'dt-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-3'ip>>",
                language: {
                    paginate: {
                        previous: '<i class="fa fa-angle-left"></i>',
                        next: '<i class="fa fa-angle-right"></i>'
                    }
                }
            });
        });
    }
})(jQuery);
