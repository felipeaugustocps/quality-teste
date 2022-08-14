<?php
require '../conn.php';
$db = new Database();

if($db){
    $data = get_object_vars(json_decode(file_get_contents('php://input')));
    $insert = $db->insert('usuarios', array_keys($data), array_values($data));
    $db->disconnect();
    
    if($insert){
        $data['id'] = $insert;
        echo json_encode([ 'status' => true, 'data' => $data ]);
    } else {
        echo json_encode([ 'status' => false, 'code' => 400 ]);
    }
} else {
    echo json_encode([ 'status' => false, 'code' => 503 ]);
}