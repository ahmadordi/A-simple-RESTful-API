<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require ('../../config/db/db_connect.php');
require ('../../models/cities.php');

// make an object for database connection
$database = new Database();
$db = $database->db_connect();

// object for Cisties
$cities = new Cities($db);

$method = $_SERVER['REQUEST_METHOD'];
// echo json_encode($method);
if ($method == 'PUT')
{
    $data = file_get_contents('php://input');
    $data = json_decode($data);

    $cities->id = $data->id;

    $cities->city = $data->city;
    $cities->start_date = $data->start_date;
    $cities->end_date = $data->end_date;
    $cities->price = $data->price;
    $cities->status = $data->status;
    $cities->color = $data->color;

    // echo json_encode($cities->id);
    // Update cities
    if ($cities->update_data())
    {
        echo json_encode(array(
            'message' => 'Record Updated'
        ));
    }
    else
    {
        echo json_encode(array(
            'message' => 'Something went wrong. Record Not Updated'
        ));
    }

}
