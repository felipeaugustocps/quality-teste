<?php
require '../conn.php';
$db = new Database();

if($db){
    $usuarios = $db->selectQueryAssoc("SELECT * FROM usuarios ORDER BY nome ASC");
    $db->disconnect();

    if($usuarios){
        echo json_encode([ 'status' => true, 'data' => $usuarios ]);
    } else {
        echo json_encode([ 'status' => false, 'code' => 404 ]);
    }
} else {
    echo json_encode([ 'status' => false, 'code' => 500 ]);
}