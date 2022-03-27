function insert_data_in_table() {
    $.ajax({
        type: "POST",
        url: "api/cities/insert_json_in_db.php",
        dataType: "JSON",
        data: {},
        beforeSend: function(xhr) {

        },
        error: function(qXHR, textStatus, errorThrow) {
            console.log("error3");
        },

        success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (data['msg'] == 'Done') {
                $('#dataLoadedText').show();
                $('#showTable').show();
            }

        }
    });
}

function create_table() {
    $.ajax({
        type: "POST",
        url: "api/cities/create_table_db.php",
        dataType: "JSON",
        data: {},
        beforeSend: function(xhr) {

        },
        error: function(qXHR, textStatus, errorThrow) {
            console.log("error4");
        },

        success: function(data, textStatus, jqXHR) {
            console.log(data);
            insert_data_in_table();
            // if (data['msg'] == 'Table created') {
            //     insert_data_in_table();
            // }else if (data['msg'] == 'Table already exists') {
            //   $('#tableCreatedText').text('Table already exists');
            // }


        }
    });
}

function check_if_table_exists() {
    $.ajax({
        type: "POST",
        url: "api/cities/check_if_table_exists_db.php",
        dataType: "JSON",
        data: {},
        beforeSend: function(xhr) {

        },
        error: function(qXHR, textStatus, errorThrow) {
            console.log("error5");
        },

        success: function(data, textStatus, jqXHR) {
            console.log(data);
            if (data['msg'] == "Table already exists") {
                console.log('exists');
                $('#tableCreatedText').text('Table is already exist!');
                insert_data_in_table();
            } else if (data['msg'] == "Table does not exist") {
                $('#tableCreatedText').text('Table is created!');
                create_table();
            }

        }
    });

}

function get_all_data() {
    console.log('test');
    $.ajax({
        type: "GET",
        url: "api/cities/load_all_data.php",
        dataType: "JSON",
        data: {},
        beforeSend: function(xhr) {
            // console.log('before send');
            $('#loadingTable').show();
            $('#errorMsg').hide();
            $('#tableReloadBtn').addClass('fast loading');
        },
        error: function(qXHR, textStatus, errorThrow) {
            $('#errorMsg').show();
            $('#loadingTable').hide();
            $('#tableReloadBtn').removeClass('fast loading');
            console.log("error1");
        },

        success: function(data, textStatus, jqXHR) {
            var dataLength = data['data'].length;
            if (typeof dataLength == 'undefined') {
                $('#countResult').text('Count: 0');
            } else {
                $('#countResult').text('Count: ' + dataLength);
            }
            $('#citiesTable').DataTable({
                data: data['data'],
                columns: [{
                        'data': 'city'
                    },
                    {
                        'data': 'start_date'
                    },
                    {
                        'data': 'end_date'
                    },
                    {
                        'data': 'price'
                    },
                    {
                        'data': 'status'
                    },
                    {
                        'data': 'color'
                    }
                ],
                destroy: true,
                "paging": true,
                "pageLength": 10,
                "lengthMenu": [
                    [10, 20, 50, 100, 500, -1],
                    [10, 20, 50, 100, 500, "All"]
                ],
                "order": [0, 'asc'],
                "searching": true,

                rowCallback: function(row, data, index) {
                    var colorCode = $(row).find('td:eq(5)').text();
                    $(row).find('td:eq(5)').css('background-color', colorCode);

                    // if we don't want to show the value of the color in cell, we can use these lines
                    // $(row).find('td:eq(5)').text('');
                    $(row).find('td:eq(5)').prop('title', colorCode);

                    // // Convert the start date format to the local format
                    // var startDate =$(row).find('td:eq(1)').text();
                    // startDate = new Date(startDate);
                    // startDate = startDate.toLocaleDateString();
                    // $(row).find('td:eq(1)').text(startDate);
                    //
                    // // Convert the end date format to the local format
                    // var endDate =$(row).find('td:eq(2)').text();
                    // endDate = new Date(endDate);
                    // endDate = endDate.toLocaleDateString();
                    // $(row).find('td:eq(2)').text(endDate);



                }
            });

            $('#loadingTable').hide();
            $('#errorMsg').hide();
            $('#tableReloadBtn').removeClass('fast loading');

        }
    });
}


function get_data_from_to(dateFrom, dateTo) {
    console.log('get_data_from_to');
    console.log(dateFrom);
    console.log(dateTo);

    $.ajax({
        type: "GET",
        url: "api/cities/load_data_from_to.php",
        dataType: "JSON",
        data: {
            dateFrom: dateFrom,
            dateTo: dateTo
        },
        beforeSend: function(xhr) {
            // console.log('before send');
            $('#loadingTable').show();
            $('#errorMsg').hide();
            $('#searchBtn').addClass('fast loading');
        },
        error: function(qXHR, textStatus, errorThrow) {
            $('#errorMsg').show();
            $('#loadingTable').hide();
            $('#searchBtn').removeClass('fast loading');
            console.log("error2");
        },

        success: function(data, textStatus, jqXHR) {
            var dataLength = data['data'].length;
            if (typeof dataLength == 'undefined') {
                $('#countResult').text('Count: 0');
            } else {
                $('#countResult').text('Count: ' + dataLength);
            }



            $('#citiesTable').DataTable({
                destroy: true,
                data: data['data'],
                columns: [{
                        'data': 'city'
                    },
                    {
                        'data': 'start_date'
                    },
                    {
                        'data': 'end_date'
                    },
                    {
                        'data': 'price'
                    },
                    {
                        'data': 'status'
                    },
                    {
                        'data': 'color'
                    }
                ],

                "paging": true,
                "pageLength": 10,
                "lengthMenu": [
                    [10, 20, 50, 100, 500, -1],
                    [10, 20, 50, 100, 500, "All"]
                ],
                "order": [0, 'asc'],
                "searching": true,

                rowCallback: function(row, data, index) {
                    var colorCode = $(row).find('td:eq(5)').text();
                    $(row).find('td:eq(5)').css('background-color', colorCode);

                    // if we don't want to show the value of the color in cell, we can use these lines
                    // $(row).find('td:eq(5)').text('');
                    $(row).find('td:eq(5)').prop('title', colorCode);

                    // // Convert the start date format to the local format
                    // var startDate =$(row).find('td:eq(1)').text();
                    // startDate = new Date(startDate);
                    // startDate = startDate.toLocaleDateString();
                    // $(row).find('td:eq(1)').text(startDate);
                    //
                    // // Convert the end date format to the local format
                    // var endDate =$(row).find('td:eq(2)').text();
                    // endDate = new Date(endDate);
                    // endDate = endDate.toLocaleDateString();
                    // $(row).find('td:eq(2)').text(endDate);



                }
            });

            $('#loadingTable').hide();
            $('#errorMsg').hide();
            $('#searchBtn').removeClass('fast loading');

        }
    });
}

function calendarsInit() {
    $('#rangestart').calendar({
        type: 'date',
        firstDayOfWeek: 1,
        today: 1,
        endCalendar: $('#rangeend'),
        formatter: {
            date: function(date, settings) {
                if (!date) return '';
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return month + '/' + day + '/' + year;
            }
        }
    });
    $('#rangeend').calendar({
        type: 'date',
        firstDayOfWeek: 1,
        today: 1,
        startCalendar: $('#rangestart'),
        formatter: {
            date: function(date, settings) {
                if (!date) return '';
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return month + '/' + day + '/' + year;
            }
        }
    });
}



function searchDateRange() {
    $('#searchBtn').click(function() {
        if ($('#dateFromInput').val() == '' && $('#dateToInput').val() == '') {
            $('#emptyErrorTxt').show();
        } else {
            $('#emptyErrorTxt').hide();
            var dateFrom = $('#dateFromInput').val();
            var dateTo = $('#dateToInput').val();
            get_data_from_to(dateFrom, dateTo);
        }
    });
}
