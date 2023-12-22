<?php
    session_start();
    include('server.php');
    include('admin_navbar.php');
    include "functions.php";

    if(get_all_req() != null){
        $all_req = get_all_req();
        $_SESSION['all_req_rows'] = $all_req;
        $all_req_count = count($_SESSION['all_req_rows']);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All request</title>
</head>
<body>
    <div class="content-container">
        <div class="top-page">
            <div class="header-group right-col">
                <h2 class = "new-page-header">รายการคำขอทั้งหมด</h2>
                <h3 class = "today"><?php  if(isset($_SESSION['all_req_rows']) && $_SESSION['all_req_rows'] != null) echo "จำนวน ".$all_req_count." รายการ";?></h3>
            </div>
            <div class="drop-down left-col">
                <select id="req-status" name="req-status">
                    <option value="">รายการคำขอทั้งหมด</option>
                    <option value="pending">รอดำเนินการ</option>
                    <option value="success">ส่งเอกสารเสร็จสิ้น</option>
                    <option value="unsuccess">พิจารณาแล้วไม่สามารถส่งเอกสารได้</option>
                </select>
            </div>
        </div>
    <div class="req-wrapper req_del">
        <?php
        if(isset($all_req_count)){
        if($all_req_count != 0){
            foreach($_SESSION['all_req_rows'] as $row): ?>
                <div class="all-req-info">
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
            } 
        }else{
            echo "ยังไม่มีคำขอเข้ามา";
        }?>
        <script src="script.js"></script> 
    </div>
    </div>
</body>
</html>