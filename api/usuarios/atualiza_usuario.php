<?php
require '../conn.php';
$db = new Database();

if($db){
    $data = get_object_vars(json_decode(file_get_contents('php://input')));

    $id = $data['id'];
    unset($data['id']);

    $update = $db->update('usuarios', array_keys($data), array_values($data), "id = $id");
    $db->disconnect();
    
    if($update){
        $data['id'] = $id;
        echo json_encode([ 'status' => true, 'data' => $data ]);
    } else {
        echo json_encode([ 'status' => false, 'code' => 400 ]);
    }
} else {
    echo json_encode([ 'status' => false, 'code' => 503 ]);
}