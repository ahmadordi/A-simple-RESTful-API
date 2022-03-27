<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require ('../../config/db/db_connect.php');
require ('../../models/cities.php');

// make an object for database connection
$database = new Database();
$db = $database->db_connect();

// object for Cisties
$cities = new Cities($db);

$req_method = $_SERVER['REQUEST_METHOD'];

if ($req_method == 'DELETE')
{
    $data = file_get_contents('php://input');
    $data = json_decode($data);

    $cities->id = $data->id;

    // delete a record from cities table usinng id
    if ($cities->delete_data())
    {
        echo json_encode(array(
            'message' => 'Record deleted'
        ));
    }
    else
    {
        echo json_encode(array(
            'message' => 'Something went wrong. Record Not deleted'
        ));
    }

}
