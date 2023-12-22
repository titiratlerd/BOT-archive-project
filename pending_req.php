<?php
    session_start();
    include('server.php');
    include('admin_navbar.php');

    date_default_timezone_set('Asia/Bangkok');
    $today = date("d/m/Y");
    $db_today = date("Y-m-d");
    $q_req_pending = "SELECT req.request_id,SUBSTRING(req.submit_time, 1, 10) AS submit_date, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc
    FROM request req, user us, request_doc req_doc 
    WHERE us.user_id = req.user_id AND req.request_id = req_doc.request_id AND req.req_status = 'pending'
    Group by req.request_id ASC;";
    $req_pending = mysqli_query($conn,$q_req_pending);
    $pen_rows = mysqli_fetch_all($req_pending, MYSQLI_ASSOC);
	mysqli_free_result($req_pending);
    $_SESSION['req_pending'] = $pen_rows;
    if(isset($_SESSION['req_pending']))
        $req_pending_num = count($_SESSION['req_pending']);
    $_SESSION['req_pending_num'] = $req_pending_num;
    $submitdate_arry = array();
    $req_id_arry = array();
    $indent50 = 'style = " margin-left: 50; "';
    $indent100 = 'style = " margin-left: 100; "';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending request</title>
</head>
<body>
    <div class="content-container">
        <div class="header-group">
            <h2 class = "new-page-header">คำขอที่รอดำเนินการ</h2>
            <h3 class = "today"><?php  if(isset($_SESSION['req_pending']) && $_SESSION['req_pending'] != null) echo "จำนวนคำขอ ".$req_pending_num." รายการ";?></h3>
            </div>

            <?php
                $q_req_pending = "SELECT req.request_id,SUBSTRING(req.submit_time, 1, 10) AS submit_date, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc
                FROM request req, user us, request_doc req_doc 
                WHERE us.user_id = req.user_id AND req.request_id = req_doc.request_id AND req.req_status = 'pending'
                Group by req.request_id ASC;";
                $result = mysqli_query($conn,$q_req_pending);
                while($pend_rows = mysqli_fetch_assoc($result)){
                    $Subtime = $pend_rows['submit_date'];
                    $Reqid = $pend_rows['request_id']; 
                    if(!in_array($Subtime,$submitdate_arry)){
                        array_push($submitdate_arry,$Subtime);
                        array_push($req_id_arry,$Reqid);
            ?>
            <h4 style="color: var(--navy, #34607F); font-weight : 400; font-size : 24px;"><?php $newDate = date("d/m/Y", strtotime($Subtime));  
            echo $newDate; ?></h4>


        <?php
            $q_pending_list = "SELECT req.req_status, req.request_id,SUBSTRING(req.submit_time, 1, 10) AS submit_date, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc 
            FROM request req, user us, request_doc req_doc
            WHERE req_doc.request_id = req.request_id AND SUBSTRING(req.submit_time, 1, 10) = '$Subtime'
            AND req.request_id = '$Reqid' AND us.user_id = req.user_id AND req.request_id = req_doc.request_id AND req.req_status = 'pending' Group by req.request_id ASC;";
            $pending_list_result = mysqli_query($conn,$q_pending_list);
            $arrayPending = array();
            while($pending_rows = mysqli_fetch_assoc($pending_list_result)){                                     
                $arrayPending[] = $pending_rows; 
            }
            foreach($arrayPending as $pend_item){
                echo '<div class="req-element">';
                    echo '<div class="req-list">';
                        echo '<div class="left-side">';
                            echo'<h4>'."เลขที่คำขอ : " .$pend_item['request_id'].'</h4>';
                            echo '<h3>' . $pend_item['name']. " " .$pend_item['surname']. '</h3>';
                            echo '<div class="obj-group">';
                                echo '<h5>' .  "วัตถุประสงค์ : " .$pend_item['objective'].'</h5>';
                                echo '<p>'. "รายละเอียดวัตถุประสงค์ : " .$pend_item['det_obj'].'</p>';
                                echo '</div>';
                        echo '</div>';
                    echo '<div class="right-side">';    
                    echo '<h5>'. "จำนวนเอกสาร " .$pend_item['count_req_doc']." รายการ". '</h5>';
                    if ($pend_item['req_status'] == 'pending')
                    {
                        echo '<h4>คำขอรอดำเนินการ</h4>';
                    }elseif($pend_item['req_status'] == 'success'){
                        echo '<h4>ส่งเอกสารเสร็จสิ้น</h4>';
                    }elseif($pend_item['req_status'] == 'unsuccess'){
                        echo '<h4>พิจารณาแล้วไม่สามารถส่งเอกสารได้</h4>';
                    }?>
                    <a href="req_detail.php?request_id=<?php echo $pend_item['request_id']?>
                    ">ดูรายละเอียด</a>
                    </div>
                    </div>
                <hr>
                <?php
            } ?>
        </div>    
        <?php
        }else{
            
            if(!in_array($Reqid,$req_id_arry)){
                array_push($req_id_arry,$Reqid);?> 
                
                <!--<h5 style="color: var(--navy, #34607F); font-weight : 400; font-size : 24px;"><?php //echo 'เลขที่คำขอ : ' . $Reqid ?></h5>-->
        <div>                       
                <?php                                       
                //-----------------Add Row into the Selected Docs --------------------------------
                                                        
                $q_pending_list = "SELECT req.req_status, req.request_id,SUBSTRING(req.submit_time, 1, 10) AS submit_date, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc 
                FROM request req, user us, request_doc req_doc
                WHERE req_doc.request_id = req.request_id AND SUBSTRING(req.submit_time, 1, 10) = '$Subtime'
                AND req.request_id = '$Reqid' AND us.user_id = req.user_id AND req.request_id = req_doc.request_id AND req.req_status = 'pending' Group by req.request_id ASC;";
                $pending_list_result = mysqli_query($conn,$q_pending_list);
                $arrayPending = array();
                while($pending_rows = mysqli_fetch_assoc($pending_list_result)){                                     
                    $arrayPending[] = $pending_rows; 
                }
                //print_r($arrayDoc);                                                         
                foreach($arrayPending as $pend_item){   
                    echo '<div class="req-element">';
                        echo '<div class="req-list">';
                            echo '<div class="left-side">';                                                        
                                echo'<h4>'."เลขที่คำขอ : " .$pend_item['request_id'].'</h4>';
                                echo '<h3>' . $pend_item['name']. " " .$pend_item['surname']. '</h3>';
                                echo '<div class="obj-group">';                
                                    echo '<h5>' .  "วัตถุประสงค์ : " .$pend_item['objective'].'</h5>';
                                    echo '<p>'. "รายละเอียดวัตถุประสงค์ : " .$pend_item['det_obj'].'</p>';
                                echo '</div>';
                        echo '</div>';
                        echo '<div class="right-side">';  
                        echo '<h5>'. "จำนวนเอกสาร " .$pend_item['count_req_doc']." รายการ". '</h5>';
                        if ($pend_item['req_status'] == 'pending')
                        {
                            echo '<h4>คำขอรอดำเนินการ</h4>';
                        }elseif($pend_item['req_status'] == 'success'){
                            echo '<h4>ส่งเอกสารเสร็จสิ้น</h4>';
                        }elseif($pend_item['req_status'] == 'unsuccess'){
                            echo '<h4>พิจารณาแล้วไม่สามารถส่งเอกสารได้</h4>';
                        }?>
                        <a href="req_detail.php?request_id=<?php echo $pend_item['request_id']?>
                        ">ดูรายละเอียด</a>
                        </div>
                        </div>
                    <hr>
                <?php
                } 
            }                                       
        } }?>
        
        <?php 
       ?>
    </div>


    </div>
    </div>
</body>
</html>