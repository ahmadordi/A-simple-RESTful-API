<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require ('../../config/db/db_connect.php');
require ('../../models/cities.php');

// make an object for database connection
$database = new Database();
$db = $database->db_connect();

// object for Cisties
$cities = new Cities($db);

$json_file = '../../config/json/data.json';
echo json_encode(array(
    'msg' => $cities->insert_data_in_db($json_file)
));
