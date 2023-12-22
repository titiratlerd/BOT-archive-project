<?php
    require "functions.php";
    if(isset($_POST['req_status'])){
        $req_status = $_POST['req_status'];
    
        if($req_status === ""){
            $reqs = get_all_req();
        }else{
            $reqs = get_req_by_status($req_status);
        }
        echo json_encode($reqs);
    }

?>