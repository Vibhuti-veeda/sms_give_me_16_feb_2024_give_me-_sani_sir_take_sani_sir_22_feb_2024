$(document).ready(function() {
    $('.datatable-search thead tr').clone(true).appendTo( '.datatable-search thead' );
    $('.datatable-search thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text"  placeholder=" '+title+'" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
    });
    var table = $('#datatable-buttons').removeAttr('width').DataTable({
        
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        orderCellsTop: true,
        fixedHeader: true,
        lengthChange:true,
            "scrollX": true,
            dom: 'Blfrtip',
            buttons:[
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
    });
});
$(document).ready(function() {
    $('.datatable-searches thead tr').clone(true).appendTo( '.datatable-searches thead' );
    $('.datatable-searches thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text"  placeholder=" '+title+'" />' );
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
    });

    var table = $('#datatable-activitylist').removeAttr('width').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "columnDefs" : [{"targets":[3,4,5,6], "type":"date"}],
        orderCellsTop: true,
        fixedHeader: true,
        lengthChange:true,
        "scrollX": true,
        dom: 'Blfrtip',
        
        buttons:[
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ],
    });
});

var table = $('#datatable-buttons-schedule').DataTable({
    orderCellsTop: true,
    fixedHeader: true,
    lengthChange:!1,
        "pageLength": 50,
        "scrollX": true,
        dom: 'Bfrtip',
        buttons:[
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
});

// Designer Brand Commission datatable
$(document).ready(function() {
    $('#commissionDatatable').DataTable({
        dom: 'Bfrtip',
        lengthChange: !1,
        scrollX: true,
        paging: true,
        scrollCollapse: false,
        responsivePriority: 1,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [ 1, 2, 3],
                    format: {
                        body: function ( inner, rowidx, colidx, node ) {
                            if ($(node).children("input").length > 0) {
                                return $(node).children("input").first().val();
                            } else {
                                return inner;
                            }
                        }
                    }
                }
            },
        ]
    });

});

$(document).ready(function() {
    $('#mytable thead tr').clone(true).appendTo( '#mytable thead' );
    $('#mytable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder=" Search '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
    });

    var table = $('#mytable').DataTable( {
        orderCellsTop: true,
        fixedHeader: true,
        lengthChange:!1,
        "scrollX": true,
    });

    // set default value in column 1
    table.columns(1).search( "Lili" ).draw();
});

// datatable intialize and individual search
$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#tableList thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#tableList thead');
 
    var tabl = $('#tableList').DataTable({
        "paging": false,
        "info": false,
        "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true,
        // buttons: ['excel', 'colvis'],
        buttons: ['colvis'],
        dom: 'Bfrtip',
        fixedColumns: {
            left: 2
        },
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
 
            // For each column
            api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                    .off('keyup change')
                    .on('change', function (e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api
                            .column(colIdx)
                            .search(
                                this.value != ''
                                    ? regexr.replace('{search}', '(((' + this.value + ')))')
                                    : '',
                                this.value != '',
                                this.value == ''
                            )
                            .draw();
                    })
                    .on('keyup', function (e) {
                        e.stopPropagation();

                        $(this).trigger('change');
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
        },
    });
});