<?php 
    session_start();
    include('server.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <title>Register</title>
</head>
<body class = "container">
    <div class="main-pad">
        <div class="back-to-login">
            <a href="log-in.php" class = "back_to_signin"><i class="fa-solid fa-arrow-left" style = "padding-right : 5px">            
            </i> ย้อนกลับ</a>
        </div>
        <div class="head">
            <h2>สมัครสมาชิก</h2>
            <div class="notice">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p>กรุณากรอกข้อมูลเป็นภาษาไทย</p>
            </div>
        </div>
        <form action="register_db.php" method='post'>
            <?php include('errors.php'); ?>
            <?php if(isset($_SESSION['error'])) : ?>
                <div class="error">
                    <h3>
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>
            <div class="sign_up_form">
            <div class="first-col">
                <div class="sub-head">
                    <h3>ข้อมูลส่วนตัว</h3>
                </div>
                <div class="two-info">
                    <div class="input-group">
                        <label for="firstname">ชื่อ</label>
                        <input type="text" name = "firstname">
                    </div>
                    <div class="input-group">
                        <label for="surname">นามสกุล</label>
                        <input type="text" name = "surname">
                    </div>
                </div>

                <div class="two-info">
                    <div class="input-group">
                        <label for="career">อาชีพ</label>
                        <input type="text" name = "career">
                    </div>
                    <div class="input-group">
                        <label for="organization">สถานที่ทำงาน หรือ สถานศึกษา</label>
                        <input type="text" name = "organization">
                    </div>
                    </div>
                    <div class="one-info">
                <div class="input-group">
                    <label for="address">ที่อยู่ที่ติดต่อได้</label>
                    <textarea autofocus="autofocus" id="myTextarea" name="address" style="resize: none; font-size : 18px;" cols="40" rows="5"></textarea>
                    <!--<input type="text" name = "address">-->
                </div>
                </div>
                <div class="two-info">
                    <div class="input-group">
                        <label for="tel">เบอร์โทรศัพท์ที่ติดต่อได้</label>
                        <input type="text" name = "tel" maxlength="10">
                    </div>
                    <div class="input-group">
                        <label for="topic">หัวข้อที่สนใจค้นคว้า</label>
                        <input type="text" name = "topic">
                    </div>
                </div>
            </div>
            <div class="second-col">
                <h3>ข้อมูลสำหรับ login</h3>
                <div class="one-info">
                    <div class="input-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name = "email">
                    </div>
                    <div class="input-group">
                        <label for="password_1">กำหนดรหัสผ่าน (ระบุรหัสผ่านอย่างน้อย 5 ตัวอักษร)</label>
                        <input type="password" name = "password_1" minlength="5">
                    </div>
                    <div class="input-group">

                        <label for="password_2">ยืนยันรหัสผ่าน</label>
                        <input type="password" name = "password_2" minlength="5">
                    </div>
                    </div>
                
               
            </div>
            </div>
            <!--<div class="accept center">
                <form action="/action_page.php">
                    <input type="checkbox" id="vehicle1" name="accept_rules" value="accept">
                    <label for="vehicle1">ข้าพเจ้ายินยอม <a href="#">นโยบายความเป็นส่วนตัว</a>และยอมรับ <a href="#">เงื่อนไขการใช้บริการ</a></label><br>
                </form>
            </div>-->
            <div class="input-group center">
                <button class="input-group-regis"  type = "submit" name = "reg_user">สมัครสมาชิก</button>
            </div>
            </form>
        </div>
    </body>
</html>