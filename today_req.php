<?php
// test
    session_start();
    include('server.php');
    include('admin_navbar.php');

    date_default_timezone_set('Asia/Bangkok');
    $today = date("d/m/Y");
    $db_today = date("Y-m-d");
    $q_req_today = "SELECT req.req_status, req.request_id, us.name, us.surname, req.objective, req.det_obj, count(req_doc.doc_id) AS count_req_doc
    FROM request req, user us, request_doc req_doc 
    WHERE SUBSTRING(req.submit_time, 1, 10) = '$db_today' AND us.user_id = req.user_id AND req.request_id = req_doc.request_id
    Group by req.request_id;";
    $req_today = mysqli_query($conn,$q_req_today);
    #$today_rows = mysqli_fetch_all($req_today);
    $today_rows = mysqli_fetch_all($req_today, MYSQLI_ASSOC);
	mysqli_free_result($req_today);
	mysqli_close($conn);
	#print_r($today_rows);
    $_SESSION['today_rows'] = $today_rows;
    if(isset($_SESSION['today_rows']))
        $today_count = count($_SESSION['today_rows']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today request</title>
</head>
<body>
    <div class="content-container">
    <div class="header-group">
        <h2 class = "new-page-header">รายการคำขอวันนี้   <i class="fa-regular fa-sun"></i></h2>
        <h3 class = "today"><?php echo $today; ?></h3>
        <h4 class = "today"><?php  if(isset($_SESSION['today_rows']) && $_SESSION['today_rows'] != null) echo "จำนวนคำขอ ".$today_count." รายการ";?></h4>
    </div>
        <?php
        if($today_count != 0){
            foreach($today_rows as $row): ?>
            <div class="req-element">
                <div class="req-list">
                    <div class="left-side">
                        <h4><?php echo "เลขที่คำขอ : " .$row['request_id'];?></h4>
                        <h3><?php echo  $row['name']." " .$row['surname'];?></h3>
                        <div class="obj-group">
                            <h5><?php echo "วัตถุประสงค์ : " .$row['objective'];?></h5>
                            <p><?php echo "รายละเอียดวัตถุประสงค์ : " .$row['det_obj'];?></p>
                        </div>
                    </div>

                    <div class="right-side">
                        <h5><?php 
                            $_SESSION['count_req_doc'] = "จำนวนเอกสาร  " .$row['count_req_doc']."  รายการ"; 
                            echo $_SESSION['count_req_doc'];?></h5>
                        <?php 
                            if ($row['req_status'] == 'pending')
                            {
                                echo "<h4>คำขอรอดำเนินการ</h4>";
                            }elseif($row['req_status'] == 'success'){
                                echo "<h4 style='border-color: #59D47B !important;'>ส่งเอกสารเสร็จสิ้น</h4>";
                            }elseif($row['req_status'] == 'unsuccess'){
                                echo "<h4 style='border-color: grey !important;'>พิจารณาแล้วไม่สามารถส่งเอกสารได้</h4>";
                            }?>
                        <a href="req_detail.php?request_id=<?php echo $row['request_id']?>">ดูรายละเอียด</a>
                    </div>
                    </div>
                <hr>
            <?php endforeach;
            
            }else{
                echo "วันนี้ยังไม่มีคำขอเข้ามา";
            } ?>
    
    </div>
    </div>
</body>
</html>