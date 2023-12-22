<?php
    session_start();
    //echo $_SESSION['user_id'];
    //echo $_POST['doc_id'];
    include('server.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['put_to_list']) ){
            if(isset($_SESSION['docs'])){
                $mydocs = array_column($_SESSION['docs'],'doc_id');
                if(in_array($_POST['doc_id'],$mydocs)){
                    echo "<script>
                        alert('เอกสารนี้คุณได้เลือกไปแล้ว');
                        window.history.back();
                    </script>";
                }
                else
                {
                    $user_id = $_SESSION['user_id'];
                    $doc_id = $_POST['doc_id'];
                    $put_in_list = "INSERT INTO waitinglist (user_id, doc_id) VALUES ('$user_id', '$doc_id');";
                    $run_put_in_list = mysqli_query($conn,$put_in_list);
                    $count = count($_SESSION['docs']);
                    $_SESSION['docs'][$count] = array('doc_id'=>$_POST['doc_id'],
                                                    'doc_name'=>$_POST['doc_name']);
                    echo "<script>
                        alert('เพิ่มรายการเอกสารเรียบร้อย');
                        window.history.back();
                    </script>";
                }
            }
            else{
                $user_id = $_SESSION['user_id'];
                $doc_id = $_POST['doc_id'];
                $put_in_list = "INSERT INTO waitinglist (user_id, doc_id) VALUES ('$user_id', '$doc_id');";
                $run_put_in_list = mysqli_query($conn,$put_in_list);
                $_SESSION['docs'][0] = array('doc_id'=>$_POST['doc_id'],
                                            'doc_name'=>$_POST['doc_name']);
                echo "<script>
                    alert('เพิ่มรายการเอกสารเรียบร้อย');
                    window.history.back();
                </script>";
            }
        }
        if(isset($_POST['remove'])){
            foreach($_SESSION['docs'] as $key => $value)
            {
                if($value['doc_id'] == $_POST['doc_id'])
                {
                    $user_id = $_SESSION['user_id'];
                    $doc_id = $value['doc_id'];
                    $del_from_list = "DELETE FROM waitinglist WHERE user_id = '$user_id' AND doc_id = '$doc_id' ";
                    $run_del_from_list = mysqli_query($conn,$del_from_list);

                    unset($_SESSION['docs'][$key]);
                    $_SESSION['docs'] = array_values($_SESSION['docs']);
                    //echo "<script>
                       // alert('ลบรายการเอกสารเรียบร้อย');
                       // window.location.href='doc_list.php';
                    //</script>";
                }
                
            }
        }
        if(isset($_POST['req_update_btn'])){
            $req_id = $_POST['req_id'];
            $req_status = $_POST['req_status'];
            $q_update_status = "UPDATE request SET req_status = '$req_status' WHERE request_id = '$req_id' ";
            $update_status = mysqli_query($conn,$q_update_status);

            echo "<script>
                    alert('อัปเดตสถานะเอกสารเรียบร้อย');
                    window.history.back();
                </script>";
        }
    }
?>