
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
        <img src="pics/Logo_BOT_Th_Eng_Alt2_H_s.png" alt="Bot logo" class="topnav-element">
        <a class="navbar-brand center topnav-element" >ระบบสืบค้นจดหมายเหตุ หอจดหมายเหตุธนาคารแห่งประเทศไทย</a>
        <div class="username dropdown topnav-element">
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
      <div class="navbar-nav">
        <div class="inner-nav">
          <a class="nav-link" href="index.php"><i class="fa-solid fa-magnifying-glass nav-icon" ></i> สืบค้นจดหมายเหตุ</a>
          <?php
          if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $q_from_waiting_list = "SELECT COUNT(doc_id) AS doc_num FROM waitinglist WHERE user_id = '$user_id';";
            $from_waiting_list = mysqli_query($conn,$q_from_waiting_list);
            $from_waiting_list_arry = mysqli_fetch_array($from_waiting_list);
            }
            if(isset($from_waiting_list_arry))
              $count = $from_waiting_list_arry['doc_num'];
          ?>
          <a class="nav-link" href="doc_list.php"><i class="fa-solid fa-list-check nav-icon"></i>  รายการเอกสารที่เลือก <?php  if(isset($from_waiting_list_arry) && $from_waiting_list_arry != null  && $count != 0) echo "( ".$count." )";?></a>
          <a class="nav-link" href="history.php"><i class="fa-solid fa-clock-rotate-left nav-icon"></i>  ประวัติการขอเอกสาร</a>
          <a class="nav-link" href="faq.php"><i class="fa-regular fa-circle-question nav-icon"></i>  คำถามที่พบบ่อย</i></a>
          </div>
      </div>
    </div>
  </div>
</nav>
</body>
</html>