<?php
require '../conn.php';
$db = new Database();

if($db){
    $delete = $db->delete("usuarios", $_GET['id']);
    $db->disconnect();

    if($delete){
        echo json_encode([ 'status' => true, 'data' => $_GET['id'] ]);
    } else {
        echo json_encode([ 'status' => false, 'code' => 404 ]);
    }
} else {
    echo json_encode([ 'status' => false, 'code' => 500 ]);
}