<?php
    session_start();
    include('server.php');
    $errors = array();

    if (isset($_POST['reg_user'])){
        $firstname = mysqli_real_escape_string($conn,$_POST['firstname']);
        $surname = mysqli_real_escape_string($conn,$_POST['surname']);
        $career = mysqli_real_escape_string($conn,$_POST['career']);
        $organization = mysqli_real_escape_string($conn,$_POST['organization']);
        $tel = mysqli_real_escape_string($conn,$_POST['tel']);
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $address = mysqli_real_escape_string($conn,$_POST['address']);
        $topic = mysqli_real_escape_string($conn,$_POST['topic']);
        $password_1 = mysqli_real_escape_string($conn,$_POST['password_1']);
        $password_2 = mysqli_real_escape_string($conn,$_POST['password_2']);

        if(empty($firstname)){
            array_push($errors, "กรุณากรอกชื่อ");
            $_SESSION['error'] = "กรุณากรอกชื่อ";
        }
        if(empty($surname)) {
            array_push($errors, "กรุณากรอกนามสกุล");
            $_SESSION['error'] = "กรุณากรอกนามสกุล";
        }
        if(empty($career)) {
            array_push($errors, "กรุณากรอกอาชีพ");
            $_SESSION['error'] = "กรุณากรอกอาชีพ";
        }
        if(empty($organization)) {
            array_push($errors, "กรุณากรอกชื่อสถานศึกษา หรือ สถานที่ทำงาน");
            $_SESSION['error'] = "กรุณากรอกอาชีพ";
        }
        if(empty($tel)) {
            array_push($errors, "กรุณากรอกเบอร์โทรศัพท์");
            $_SESSION['error'] = "กรุณากรอกเบอร์โทรศัพท์";
        }
        if(empty($email)) {
            array_push($errors, "กรุณากรอกอีเมล");
            $_SESSION['error'] = "กรุณากรอกอีเมล";
        }
        if(empty($address)) {
            array_push($errors, "กรุณากรอกที่อยู่ที่ติดต่อได้");
            $_SESSION['error'] = "กรุณากรอกที่อยู่ที่ติดต่อได้";
        }
        if(empty($topic)) {
            array_push($errors, "กรุณากรอกหัวข้อที่ต้องการค้นคว้า");
            $_SESSION['error'] = "กรุณากรอกหัวข้อที่ต้องการค้นคว้า";
        }
        if(empty($password_1)) {
            array_push($errors, "กรุณากรอกรหัสผ่าน");
            $_SESSION['error'] = "กรุณากรอกรหัสผ่าน";
        }
        if ($password_1 != $password_2) {
            array_push($errors, "รหัสผ่านของท่านไม่ตรงกัน");
            $_SESSION['error'] = "รหัสผ่านของท่านไม่ตรงกัน";
        }
        $email_check = "SELECT * FROM account WHERE email = '$email' LIMIT 1";
        $query = mysqli_query($conn, $email_check);
        $result = mysqli_fetch_assoc($query);

        if ($result) {// if email exists
            if ($result['email'] == $email) {
                array_push($errors,"อีเมลนี้ถูกใช้ลงทะเบียนแล้ว");
                $_SESSION['error'] = "อีเมลนี้ถูกใช้ลงทะเบียนแล้ว";
            }
        }
        if (count($errors)== 0 ) {
            $password = md5($password_1);

            $sql = "INSERT INTO user (name,surname,career,organization,address,tel,email,topic)
            VALUES ('$firstname','$surname','$career','$organization','$address','$tel','$email',
            '$topic');";
            $sql2 = "INSERT INTO account (email, password, user_level) VALUES ('$email','$password','m');";
        
            $result2 = mysqli_query($conn, $sql2);
            $result3 = mysqli_query($conn, $sql);

            $_SESSION['email'] = $email;
            $_SESSION['success'] = "login เสร็จสิ้น" ;
            header('location: register_finish.php');
        }else {
            header("location: register.php");
         }
    
    }



?>

