/*
 *  Document   : uiTables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables page
 */

var UiTables = function() {

    return {
        init: function() {
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();

            /* Initialize Datatables */
            $('#example-datatable').dataTable({
                "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 1 ] } ],
                "iDisplayLength": 10,
                "aLengthMenu": [[5, 10, 20], [5, 10, 20]]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');

        }
    };
}();