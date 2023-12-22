<?php
    session_start();
    include('server.php');

    $errors = array();

    if (isset($_POST['login_user'])){
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);
    
        if(empty($email)){
            array_push($errors, "กรุณากรอกอีเมลที่ท่านใช้สมัคร");
        }

        if(empty($password)) {
            array_push($errors, "กรุณากรอกรหัสผ่าน");
        }

        if (count($errors) == 0 ) {
        $password = md5($password);
        $query = "SELECT * FROM account WHERE email = '$email' AND password = '$password' ";
        $result = mysqli_query($conn, $query);
        $rows = mysqli_fetch_array($result);
        
            if(mysqli_num_rows($result) == 1) {
                $_SESSION['email'] = $email;
                $_SESSION['success'] = "login เสร็จสิ้น";

                
                $_SESSION['user_level'] = $rows['user_level'];

                if($_SESSION['user_level'] == 'a'){
                    header("Location: today_req.php");
                }else{
                    header("location: index.php");
                }
                
            } else {
                array_push($errors, "อีเมล หรือ รหัสผ่าน ไม่ถูกต้อง");
                $_SESSION['error'] = "อีเมล หรือ รหัสผ่าน ไม่ถูกต้อง";
                header("location: log-in.php");
                }
    } else {
        array_push($errors, "กรุณากรอกอีเมลและรหัสผ่านที่ท่านใช้สมัคร");
        $_SESSION['error'] = "กรุณากรอกอีเมลและรหัสผ่านที่ท่านใช้สมัคร";
        header("location: log-in.php");}
    }
?>