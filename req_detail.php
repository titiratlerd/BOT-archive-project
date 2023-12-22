<?php
    session_start();
    include('server.php');
    include('admin_navbar.php');

    $style = "";
    if(isset($_GET['request_id'])){
        $req_id = $_GET['request_id'];
        //echo  $req_id;
        
        $mysql = "SELECT  doc.doc_name, doc.doc_id, doc.box_id, box.location, box.zone
        FROM document doc, request req, user us, request_doc reqdoc, doc_box box
        WHERE doc.box_id = box.box_id AND req.request_id = reqdoc.request_id AND reqdoc.doc_id = doc.doc_id
        AND req.request_id = '$req_id' GROUP BY doc.doc_id;";
        $resalt = mysqli_query($conn,$mysql);
        $doc_in_req = mysqli_fetch_all($resalt, MYSQLI_ASSOC);
        $_SESSION['docs_array'] = $doc_in_req;
        mysqli_free_result($resalt);
        

        $get_detail_info = "SELECT req.req_status, us.name, us.surname, us.tel, us.address, us.career, us.organization, us.email, SUBSTRING(req.submit_time, 1, 10) AS sub_time,
        req.objective, req.det_obj
        FROM request req, user us
        WHERE req.user_id = us.user_id 
        AND req.request_id = '$req_id';";
        $req_deatail = mysqli_query($conn,$get_detail_info);
        $det_row = mysqli_fetch_array($req_deatail);
        mysqli_close($conn);

    }else{
      echo "<h1 class = 'text-danger' <?php echo $style;?>>ขออภัยในความไม่สะดวกจ้า</h1>";
    }

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <title>Request Detail</title>
</head>
<body>
    <div class="content-container">
    <div class="back-to-login">
            <a onclick="history.back()"><i class="fa-solid fa-arrow-left fa-2x" style = "cursor: pointer; color: rgba(52, 96, 127, 0.80);">            
        </i></a>
        </div>
        <div class="flex-between">
        <div class="header-group">
            <h4 class="today"><?php echo "เลขที่คำขอ :  ".$req_id; ?></h4>
            <h2><?php echo $det_row['name'].'  '.$det_row['surname']; ?></h2>
        </div>
            
        <div class="header-group">
            <label>สถานะคำขอปัจจุบัน : <?php 
                if ($det_row['req_status'] == 'pending')
                {
                    echo "คำขอรอดำเนินการ";
                }elseif($det_row['req_status'] == 'success'){
                    echo "ส่งเอกสารเสร็จสิ้น";
                }elseif($det_row['req_status'] == 'unsuccess'){
                    echo "พิจารณาแล้วไม่สามารถส่งเอกสารได้";
                } ?>
            </label>
            <div class="status-dropdown">
                <form action="doc_list_db.php" method="POST">
                    <input type="hidden" name="req_id" value = "<?php echo $req_id ?>">
                    <select id="mySelect" onchange="myFunction()" name="req_status" class="form-select">
                        <option value="pending">คำขอรอดำเนินการ</option>
                        <option value="success">ส่งเอกสารเสร็จสิ้น</option>
                        <option value="unsuccess">พิจารณาแล้วไม่สามารถส่งเอกสารได้</option>
                    </select>
                    <button type="submit" name = "req_update_btn" class="sub-btn">อัปเดตสถานะ</button>
                </form>
            </div>
        </div>
        </div>
        <div class="flex-between">
            <h4>รายการเอกสาร</h4>
            <h5><?php $count = 0;
                            if(isset($_SESSION['docs_array'])){
                                $count = count($_SESSION['docs_array']);
                                echo "ทั้งหมด  ".$count."  รายการ";
                            }?>
                    </h5>
            </div>
                <table class = "table" <?php if(!isset($_SESSION['docs_array'])) echo 'style="display:none;"'; ?>>
            <tr>
                <th style = "border-radius: 20px 0px 0px 0px;">ลำดับ</th>
                <th style = "border-radius: 0px 0px 0px 0px;">ชื่อแฟ้ม</th>
                <th style = "border-radius: 0px 20px 0px 0px;">เลขที่แฟ้ม</th>
                <th style = "border-radius: 0px 20px 0px 0px;">เลขที่กล่อง</th>
                <th style = "border-radius: 0px 20px 0px 0px;">Location</th>
                <th style = "border-radius: 0px 20px 0px 0px;">Zone</th>
            </tr>
    <?php 
    if(isset($_SESSION['docs_array'])){
        foreach($doc_in_req as $a_doc): 
            ?>
                <td><?php echo array_search($a_doc,$doc_in_req) +1 ; ?></td>
                <td style="text-align: left!important;"><?php echo $a_doc['doc_name']; ?></td>
                <td><?php echo $a_doc['doc_id']; ?></td>
                <td><?php echo $a_doc['box_id']; ?></td>
                <td><?php echo $a_doc['location']; ?></td>
                <td><?php echo $a_doc['zone']; ?></td>
            </tr>
        <?php endforeach; }?>
    </table>
    <div class="req-with-head">
    <h4>รายละเอียดคำขอ</h4>
            <div class="req-det">
                <div class="row">
                    <p style="width: 250px;">ทำเรื่องขอเอกสารเมื่อวันที่</p>
                    <p>:</p>
                    <p><?php echo $det_row['sub_time']; ?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">จุดประสงค์</p>
                    <p>:</p>
                    <p><?php echo $det_row['objective'];?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">รายละเอียดจุดประสงค์</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $det_row['det_obj'];?></p>
                </div>
            </div>
    </div>
    <div class="req-with-head">
        <h4>ข้อมูลติดต่อ</h4>
            <div class="req-det">
                <div class="row">
                    <p style="width: 250px;">เบอร์โทรศัพท์</p>
                    <p>:</p>
                    <p><?php echo $det_row['tel'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">อีเมล</p>
                    <p>:</p>
                    <p><?php echo $det_row['email'];?></p>
                </div>
            </div>
    </div>
    <div class="req-with-head">
        <h4>ข้อมูลผู้ใช้</h4>
            <div class="req-det">
                <div class="row">
                    <p style="width: 250px;">ที่อยู่</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $det_row['address'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">อาชีพ</p>
                    <p>:</p>
                    <p><?php echo $det_row['career'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">สถานที่ทำงาน / สถานศึกษา</p>
                    <p>:</p>
                    <p><?php echo $det_row['organization'] ;?></p>
                </div>
            </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</body>

</html>  


