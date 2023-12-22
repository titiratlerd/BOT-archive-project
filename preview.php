<?php
    session_start();
    include('server.php'); 
    include('nav_bar.php');

    $sel_docs = $_POST["select"];

    if(isset($_POST["select"])){
        //$sel_docs = $_POST['select'];
        foreach($sel_docs as $key => $value)
        {
            $get_sel_doc_info = "SELECT doc_id, doc_name FROM document WHERE doc_id = '$value'";
            $sel_doc_result = mysqli_query($conn,$get_sel_doc_info);
            $sel_doc_rows = mysqli_fetch_array($sel_doc_result);
            //print_r($sel_doc_rows);
            //echo $value;
            
            if(isset($_SESSION['sel_docs'])){
                $count = count($_SESSION['sel_docs']);
                $_SESSION['sel_docs'][$count] = array('doc_id'=>$sel_doc_rows['doc_id'],
                                                  'doc_name'=>$sel_doc_rows['doc_name']);
            }else{
            $_SESSION['sel_docs'][0] = array('doc_id'=>$sel_doc_rows['doc_id'],
                                            'doc_name'=>$sel_doc_rows['doc_name']);
            }
        }
    }


    if(isset($_SESSION['sel_docs']) && isset($_POST['create_request'])){
        //echo "<br>";
        //print_r($_SESSION['docs']);
        $the_docs = $_SESSION['sel_docs'];
        $user_mail = $_SESSION['email'];
        $user_id = $_SESSION['user_id'];
        $get_user_info = "SELECT * FROM user WHERE user_id = '$user_id'";
        $result = mysqli_query($conn,$get_user_info);
        $rows = mysqli_fetch_array($result);
        $objective = mysqli_real_escape_string($conn,$_POST['objective']);
        $det_objective = mysqli_real_escape_string($conn,$_POST['det_objective']);
        $_SESSION["obj"] = $objective;
        $_SESSION["det_obj"] = $det_objective;
        $_SESSION["go_to_db"] = $the_docs;
    }
    elseif(isset($_SESSION['sel_docs']) && isset($_POST['remove'])){
        $remove = $_SESSION['sel_docs'];
        foreach($_SESSION['new_docs'] as $key => $value)
        {   
            $my_del_docs = array_column($remove,'doc_id');
            if(in_array($value['doc_id'],$my_del_docs))
            {
                $user_id = $_SESSION['user_id'];
                $doc_id = $value['doc_id'];
                $del_from_list = "DELETE FROM waitinglist WHERE user_id = '$user_id' AND doc_id = '$doc_id' ";
                $run_del_from_list = mysqli_query($conn,$del_from_list);
                unset($_SESSION['new_docs'][$key]);
                $_SESSION['new_docs'] = array_values($_SESSION['new_docs']);
                echo "<script>
                    alert('ลบรายการเอกสารเรียบร้อย');
                    window.location.href='doc_list.php';
                </script>";
            }
            
        }
    }
    //echo $_SESSION["sel"];
    unset($_SESSION['sel_docs']);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Request</title>
</head>
<body>

<div class="preview-main">
    <div class="preview-header">
        <h2>คำขอใช้เอกสารจดหมายเหตุธนาคารแห่งประเทศไทย</h2>
        <div class="notice">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p>หากกดส่งแล้วจะไม่สามารถแก้ไขคำขอได้ กรุณาตรวจสอบรายละเอียดด้านล่างก่อนกดส่งคำขอ</p>
        </div>
    </div>
    <div class="preview-req-list">            
            <h3>รายการเอกสาร</h3>
                    <table class = "table" <?php if(!isset($_SESSION['new_docs'])) echo 'style="display:none;"';?> style = "width: 100%;">
                    <tr>
                        <th style = "border-radius: 20px 0px 0px 0px;">ลำดับ</th>
                        <th style = "border-radius: 0px 0px 0px 0px;">ชื่อแฟ้ม</th>
                        <th style = "border-radius: 0px 20px 0px 0px;">เลขที่แฟ้ม</th>
                    </tr>
        <?php 
        if(isset($_SESSION['new_docs'])){
            foreach($the_docs as $a_doc): 
                ?>
                    <td style = "text-align :center;"><?php echo array_search($a_doc,$the_docs) +1 ; ?></td>
                    <td style = "text-align : left;"><?php echo $a_doc['doc_name']; ?></td>
                    <td style = "text-align :center;"><?php echo $a_doc['doc_id']; ?></td>
                </tr>
            <?php endforeach; }?>
        </table>
    </div>
    <div class="preview-req">
        <h3>รายละเอียดคำขอ</h3>
        <div class="req-det">
                <div class="row">
                    <p style="width: 250px;">ทำเรื่องขอเอกสารเมื่อวันที่</p>
                    <p>:</p>
                    <p><?php echo date("d/m/Y"); 
                    $_SESSION['sub_date'] = 'ทำเรื่องขอเอกสารเมื่อวันที่ : '. date("d/m/Y"); ?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">จุดประสงค์การขอใช้</p>
                    <p>:</p>
                    <p><?php echo $objective;
                    $_SESSION['objj'] = 'จุดประสงค์การขอใช้ : '. $objective; ?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">รายละเอียดจุดประสงค์</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $det_objective;
                    $_SESSION['det_objj'] = 'รายละเอียดจุดประสงค์ : '. $det_objective; ?></p>
                </div>
        </div>
    <div class="preview-user">
        <h3>ข้อมูลส่วนตัว</h3>
        <div class="req-det">
                <div class="row">
                    <p style="width: 250px;">ชื่อผู้ส่งคำขอ</p>
                    <p>:</p>
                    <p style="max-width: 850px;"><?php echo $rows['name']. " " . $rows['surname'] ;
                    $_SESSION['name'] = 'ชื่อผู้ส่งคำขอ : '. $rows['name']. " " . $rows['surname'];?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">เบอร์โทรศัพท์</p>
                    <p>:</p>
                    <p><?php echo $rows['tel'] ;
                    $_SESSION['tel'] = 'เบอร์โทรศัพท์ : '. $rows['tel'];?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">อีเมล</p>
                    <p>:</p>
                    <p><?php echo $user_mail ;
                    $_SESSION['mail'] = 'อีเมล : '. $user_mail;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">ที่อยู่</p>
                    <p>:</p>
                    <p style="max-width: 850px;"><?php echo $rows['address'] ;
                     $_SESSION['address'] = 'ที่อยู่ : '. $rows['address'];?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">สถานที่ทำงาน / สถานศึกษา</p>
                    <p>:</p>
                    <p><?php echo $rows['organization'] ;
                    $_SESSION['organization'] = 'สถานที่ทำงาน / สถานศึกษา : '.$rows['organization'];?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">อาชีพ</p>
                    <p>:</p>
                    <p><?php echo $rows['career'] ;
                    $_SESSION['career'] = 'career : '. $rows['career'];?></p>
                </div>
        </div>
    </div>
    
    </div>
    </div>
    </div>
</div>
<form class = "sav_req_form" action="store_req.php" method = "POST" >
<div class="save_req_bt" >
        <button type="submit" name = "save_request" class="btn_submit">ส่งคำขอใช้เอกสาร <i style = "margin-top : 5px; margin-left : 5px; align-items: baseline;"class="fa-solid fa-arrow-right"></i></button>
        <input type="hidden" value = "เพิ่มรายการเอกสาร" name="put_to_list">
        <input type="hidden" value = <?php echo $a_doc['doc_id'] ?> name="doc_id">
        <input type="hidden" value = "<?php echo $a_doc['doc_name'] ?>" name= "doc_name" >
</div> 
</form>
</body>
</html>

<?php
    include('server.php');
    if(isset($_SESSION["go_to_db"])){
    $user_info = "<b>ข้อมูลผู้ใช้</b>" ."<br>" . $_SESSION['name'] ."<br>" .$_SESSION['mail'] ."<br>" .$_SESSION['tel']."<br>" .$_SESSION['career']."<br>" 
    .$_SESSION['organization'] ."<br>" .
    $_SESSION['address'];
    $req_info = "<b>รายละเอียดคำขอ</b>" ."<br>" . $_SESSION['sub_date'] ."<br>" .$_SESSION['objj']."<br>" .$_SESSION['det_objj'];

        $doc_save = $_SESSION["go_to_db"];
        $doc_list = "
        <table class = 'table style = 'padding : 10px; margin : 5px'>
            <tr>
                <th>เลขที่แฟ้ม</th>
                <th>ชื่อแฟ้ม</th>
                <th>เลขที่กล่อง</th>
                <th>Location</th>
                <th>Zone</th>
            </tr>";
        foreach($doc_save as $key => $value){
        
        $get_doc_info =  "SELECT doc.doc_id, doc.doc_name, doc.box_id, box.location, box.zone 
        FROM document doc, doc_box box WHERE doc.box_id = box.box_id AND doc.doc_id = '$value[doc_id]'";

        $result = mysqli_query($conn,$get_doc_info);
        $rows = mysqli_fetch_array($result);
        $doc_list .= "
            <tr>
        <td>$rows[doc_id]</td>
        <td>$rows[doc_name]</td>
        <td>$rows[box_id]</td>
        <td>$rows[location]</td>
        <td>$rows[zone]</td>
        </tr>     
        
        ";
        }
        $doc_list .= "</table>";
        //echo $doc_list;
        $doc_list_info = "<b>รายการเอกสาร</b>" ."<br>" . $doc_list;
    }

    $_SESSION['mail_content'] =  $user_info ."<br>"  ."<br>" . $req_info."<br>"  ."<br>" . $doc_list_info;

    /*unset($_SESSION['name']);
    unset($_SESSION['tel']);
    unset($_SESSION['career']);
    unset($_SESSION['organization']);
    unset($_SESSION['sub_date']);
    unset($_SESSION['objj']);*/

?>