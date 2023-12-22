<?php
    session_start();
    include('server.php');
    include('nav_bar.php');
    
    
// เก็บระบบเซฟเอาไว้ทำ
if(isset($_SESSION['go_to_db']) && isset($_POST['save_request'])){
    $obj = $_SESSION["obj"];
    $det_obj = $_SESSION["det_obj"];
    date_default_timezone_set('Asia/Bangkok');
    $today = date("Y-m-d H:i:s");
    $user = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];

    $add_request = "INSERT INTO request (user_id, objective, req_status, det_obj) VALUES ('$user_id', '$obj', 'pending', '$det_obj');";
    $run_add_request = mysqli_query($conn, $add_request);
 
    $get_req_info = "SELECT request_id FROM request WHERE user_id = '$user_id' AND det_obj = '$det_obj' AND objective = '$obj' AND submit_time = '$today';";
    $get_req_id = mysqli_query($conn,$get_req_info);
    $req_id_rows = mysqli_fetch_assoc($get_req_id);
    $req_id = $req_id_rows['request_id'];
    $_SESSION['req_id'] = $req_id;
    //}
    //save data to database
    foreach($_SESSION['go_to_db'] as $key => $value){
        $add_request_doc = "INSERT INTO request_doc VALUES ('$_SESSION[req_id]','$value[doc_id]');";
        $run_add_request = mysqli_query($conn, $add_request_doc);
        $del_from_waiting_table = "DELETE FROM waitinglist WHERE user_id = '$user_id' AND doc_id = '$value[doc_id]' ";
        $del_from_waiting_table  = mysqli_query($conn, $del_from_waiting_table);
    }
    
    //send email
    $_SESSION['subject'] = "เลขที่คำขอ : " . $_SESSION['req_id']. " ผู้ใช้บริการเอกสารจดหมายเหตุส่งคำขอ";
    //echo $_SESSION['subject'];
    
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
    <title>Request completed</title>
</head>
<body class = "fin_container" >
<div class="finish-main">
    <div class="finish-header">
        <h2 class = "page-header">ส่งคำขอการใช้เอกสารเสร็จสมบูรณ์</h2>
    </div>
    <div class="finish-info info center">
        <h3><?php echo "เลขที่คำขอ : ". $_SESSION['req_id'] ; ?></h3>
        <h4>โปรดรอการติดต่อกลับจากเจ้าหน้าที่ หรือ หากมีข้อสงสัยติดต่อที่ อีเมล archives@bot.or.th</h4>
        <i class="fa-regular fa-paper-plane fa-4x" style = "padding : 20px; color: var(--blue, #7C9EB3);"></i>
    </div>

<div class="his_page_bt">
    <a type="submit" class = "fin_button his" name = "history" href = "history.php">ดูประวัติการขอ    <i class="fa-solid fa-clock-rotate-left"></i></a>
    <a type="submit" class = "fin_button search-again" name = "save_request" href = "index.php" >เริ่มต้นค้นหาอีกครั้ง    
    <i class="fa-solid fa-magnifying-glass"></i></a>
</div>  
    
</div>

</body>
</html>

<?php
/*
    $gone = $_SESSION['go_to_db'];
    if($_SESSION['docs'] != null){
    foreach($_SESSION['docs'] as $key => $value)
    {
        //echo $value['doc_id'];

        //echo "<br>";
        $my_sel_docs = array_column($gone,'doc_id');
        //print_r($my_sel_docs);
        //echo "<br>";
        if(in_array($value['doc_id'],$my_sel_docs))
        {
            unset($_SESSION['docs'][$key]);
        }
    }
}*/
    unset($_SESSION['req_id']);
    unset($_SESSION['new_docs']);
    unset($_SESSION["obj"]);
    unset($_SESSION['name']);
    unset($_SESSION['tel']);
    unset($_SESSION['career']);
    unset($_SESSION['organization']);
    unset($_SESSION['sub_date']);
    unset($_SESSION['objj']);
    unset($_SESSION['det_objj']);
?>

<?php
include('server.php');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';
if(isset($_SESSION['subject'])){
    //echo $_SESSION['subject'];
    $subject = $_SESSION['subject'];
    $mail_content = $_SESSION['mail_content'];
    //echo $mail_content;
    //echo  $send_email;
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'archivebotproject@gmail.com';                     //SMTP username
        $mail->Password   = 'pppouvluzaaraluw';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    
        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('archivebotproject@gmail.com', 'Archive BOT project');
        $mail->addAddress('titirat.lerd@gmail.com');     //Add a recipient
                //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('pics/BOT-Museum-3.jpg','BOT-Museum-3.jpg');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $mail_content;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

    unset($_SESSION['subject']);
    }
?>