<?php 
session_start();
header('Content-type: application/json');
if(isset($_SESSION['upload_progress_upload'])){
    echo json_encode($_SESSION['upload_progress_upload']);
} else {
    echo json_encode(array('fertig' => true));
}
?>