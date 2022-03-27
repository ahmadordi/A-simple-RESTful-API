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

if ($cities->check_if_table_exists())
{
    echo json_encode(array(
        'msg' => 'Table already exists'
    ));
}
else
{
    if ($cities->create_table())
    {
        echo json_encode(array(
            'msg' => 'Table created'
        ));
    }
}
