<?php
require '../conn.php';
$db = new Database();

if($db){
    $usuario = $db->selectQueryAssoc("SELECT * FROM usuarios WHERE id = " . $_GET['id']);
    $db->disconnect();

    if($usuario){
        echo json_encode([ 'status' => true, 'data' => $usuario ]);
    } else {
        echo json_encode([ 'status' => false, 'code' => 404 ]);
    }
} else {
    echo json_encode([ 'status' => false, 'code' => 500 ]);
}