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

// read all
$read_all_data = $cities->read_all();

// get the number of rows
$num = $read_all_data->num_rows;

if ($num > 0)
{
    $result_array = array();
    $result_array['data'] = array();

    while ($row = $read_all_data->fetch_assoc())
    {
        // convert the dates back to the original form given in json data
        $start_date = date('m/d/Y', strtotime($row['start_date']));
        $end_date = date('m/d/Y', strtotime($row['end_date']));

        // collect all data in an array
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
        'data' => 'Empty'
    ));
}
