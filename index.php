<?php 
    session_start();
    include('server.php'); 
    include('nav_bar.php'); 

    if(!isset($_SESSION['email'])){
        $_SESSION['msg'] = "please log in";
        header('location: log-in.php');
        }
    if (isset($_GET['logout'])) {
        //session_destroy();
        unset($_SESSION['email']);
        unset($_SESSION['docs']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_id']);
        header('location: log-in.php');
    }
    $user_mail = $_SESSION['email'];
    $get_user_info = "SELECT * FROM user WHERE email = '$user_mail';";
    $result = mysqli_query($conn,$get_user_info);
    $rows = mysqli_fetch_array($result);
    $user_id = $rows['user_id'];
    $_SESSION['user_id'] = $user_id;
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="homeheader">
        <!--<h2>ระบบสืบค้นจดหมายเหตุ</br>หอจดหมายเหตุธนาคารแห่งประเทศไทย</h2>-->
    </div>

    <div class="content">
        <form name="form1" method="get" action="search.php">
        <h3 class = "search-name">Basic search</h3>
        <div class="search-main">
            <div class="search-bar-group">
		        <input class = "search-bar" type="text" placeholder="พิมพ์คำค้นที่ต้องการค้นหา" name="search" aria-label="search" required>
                <button class = "search-bt" name = "submit">
                    <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span> ค้นหา
                </button>
                <!--<input type="hidden" name="submit"></input>-->
            </div>
            <div class="under-search-bar ">
                <a style = " max-width: fit-content; margin-top: 20px; margin-right: 40px;" href="advanced_search.php">Advanced search</a>
            </div>
        </div>
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
	    </form>

        
    </div>
</body>
</html>