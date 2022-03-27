<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/db/db_connect.php';
include_once '../../models/cities.php';

// make an object for database connection
$database = new Database();
$db = $database->db_connect();

// object for Cisties
$cities = new Cities($db);

// read date range
if (isset($_GET['dateFrom']) && isset($_GET['dateTo']))
{
    $date_from = $_GET['dateFrom'];
    $date_to = $_GET['dateTo'];
    $date_from = date('Y-m-d', strtotime($date_from));
    $date_to = date('Y-m-d', strtotime($date_to));

    // $date_from = '2013-01-01';
    // $date_to = '2013-02-01';
    $read_data_from_to = $cities->read_from_to($date_from, $date_to);

    // get the number of rows
    $num = $read_data_from_to->num_rows;

    if ($num > 0)
    {
        $result_array = array();
        $result_array['data'] = array();

        while ($row = $read_data_from_to->fetch_assoc())
        {
            $start_date = date('m/d/Y', strtotime($row['start_date']));
            $end_date = date('m/d/Y', strtotime($row['end_date']));
            $one_row = array(
                'id' => $row['id'],
                'city' => $row['city'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'price' => $row['price'],
                'status' => $row['status'],
                'color' => $row['color']
            );
            // Push to array
            array_push($result_array['data'], $one_row);
        }
        // Convert the array to json format and echo it
        echo json_encode($result_array);
    }
    else
    {
        // if the table is empty
        echo json_encode(array(
            'data' => array(
                'id' => '-',
                'city' => '-',
                'start_date' => '-',
                'end_date' => '-',
                'price' => '-',
                'status' => '-',
                'color' => '-'
            )
        ));
    }
}
