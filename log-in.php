<?php
    session_start();
    include('server.php'); 
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class = "container">
    <div class="main">
        <div class="header">
            <img src="pics/Logo_BOT.png" alt="Bot login Logo">
            <h4>ระบบสืบค้นจดหมายเหตุ</h4>
            <h4>หอจดหมายเหตุ ธนาคารแห่งประเทศไทย</h4>
        </div>
        <div class="form">
            <form action="login_db.php" method = "post">
            <?php if(isset($_SESSION['error'])) : ?>
                    <div class="error">
                        <p>
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </p>
                    </div>
            <?php endif ?>
            <div class="login-input-group login">
                <!--<label for="email">Email</label> -->
                <input type="email" name = "email" placeholder = "อีเมล" >
            </div>

            <div class="login-input-group login">
                <!--<label for="password">Password</label>-->
                <input type="password" name = "password" placeholder = "รหัสผ่าน" >
            </div>
            
            <div class="input-group ">
                <button type = "submit" name = "login_user" class = "btn">เข้าสู่ระบบ</button>
                <a  role = "button"href="#">ลืมรหัสผ่าน</a>   
            </div>
            <hr>
            <div class="sign_up">
                <a  class="sign_up_bt" role = "button"href="register.php">สมัครสมาชิก</a>      
            </div>
            </form>
        </div>
    </div>
</body>
</html>