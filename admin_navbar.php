

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
<div class="container-fluid">
    <div class="container-nav">
      <div class="top-nav">
        <img src="pics/Logo_BOT_Th_Eng_Alt2_H_s.png" alt="Bot logo">
        <a class="navbar-brand center" style="margin-right: 0!important;">ระบบสืบค้นจดหมายเหตุ หอจดหมายเหตุธนาคารแห่งประเทศไทย</a>

      <div class="username dropdown">
        <div class="dropbtn">
          <a class="navbar-user-name" >
            <?php 
              $email = $_SESSION['email'];
              $get_user_name = "SELECT name FROM user, account WHERE user.email = '$email'"; 
              $result = mysqli_query($conn,$get_user_name);
              $rows = mysqli_fetch_array($result);
              $user_name = $rows['name'];
              $_SESSION['user_name'] = $user_name;
              echo $_SESSION['user_name'];
            ?>
            </a>
            <i class="fa-solid fa-caret-down"></i>
          </div>
          <div class="dropdown-content">
            <a href="#">แก้ไขข้อมูลส่วนตัว</a>
            <a href="index.php?logout='1'">ออกจากระบบ</a>              
          </div>
        </div>
      </div>
    </div>


    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav center">
      <div class="inner-nav">
        <?php
          $today_count = 0;
          if(isset($_SESSION['today_rows']))
            $today_count = count($_SESSION['today_rows']);
        ?>
        <a class="nav-link" href="today_req.php"><i class="fa-regular fa-sun"></i> รายการคำขอวันนี้<?php  if(isset($_SESSION['today_rows']) && $_SESSION['today_rows'] != null) echo " ( ".$today_count." )";?></a>
        <?php
          $pending_count = 0;
          if(isset($_SESSION['req_pending']))
            $pending_count = count($_SESSION['req_pending']);
        ?>

          <a class="nav-link" href="pending_req.php"><i class="fa-solid fa-list"></i>  คำขอที่รอดำเนินการ<?php  if(isset($_SESSION['req_pending']) && $_SESSION['req_pending'] != null) echo " ( ".$_SESSION['req_pending_num']." )";?></a>
     
        <a class="nav-link" href="all_req.php"><i class="fa-solid fa-list-check nav-icon"></i>  คำขอทั้งหมด</a>
        </div>
      </div>
    </div>
  </div>
</nav>
</body>
</html>