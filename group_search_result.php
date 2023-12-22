<?php
    session_start();
    include('server.php');
    include('nav_bar.php');

    $date = $_SESSION['date_query'];

    $perpage = 20;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }
    $start = ($page - 1) * $perpage; 
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

    $style = "";
    if(isset($_GET['box_id'])){
        $box_id = $_GET['box_id'];
        $mysql = "SELECT dep.dept_name, doc.doc_name, doc.start_date, doc.end_date, doc.doc_id
        FROM document doc, department dep
        WHERE doc.dept_id = dep.dept_id 
        AND (doc.doc_id in (SELECT doc_id  
        FROM document WHERE box_id = '$box_id'))";
        if(isset($date)){
            $mysql .= $date;
        }
        $mysql_limit = $mysql . " limit {$start} , {$perpage}";
        $option = $box_id;
    }
    elseif(isset($_GET['class_name'])){
        $class_name = $_GET['class_name'];
        $mysql = "SELECT dep.dept_name, doc.doc_name, doc.start_date, doc.end_date, doc.doc_id
        FROM document doc, department dep
        WHERE doc.dept_id = dep.dept_id 
        AND (doc.doc_id in (SELECT doc_id  
        FROM document WHERE class_name = '$class_name'))";
        if(isset($date)){
            $mysql .= $date;
        }
        $option = $class_name;
        $mysql_limit = $mysql . " limit {$start} , {$perpage}";
    }
        
        $mysql .= " ORDER BY doc.end_date ;";
        //print_r($mysql);
        //echo $mysql;
        
        $run_limit = mysqli_query($conn,$mysql_limit);
        $run_all = mysqli_query($conn,$mysql);
        $foundnum = mysqli_num_rows($run_all);
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
</head>

<body>
<div class="content-container">
<?php
    if ($foundnum==0)
    {
    echo "ไม่พบผลการค้นหาของคำค้น'<b>$option</b>'.";
}
else{

echo "<h2 class = 'page-header'>ผลการค้นหาทั้งหมด <strong> $foundnum</strong> รายการ <p style = 'text-align : left !important;'>ของคำค้น \"" .$option."\" </p></h2>"; 

//$getquery = mysqli_query($conn,$mysql);
//$docs = mysqli_fetch_all($getquery, MYSQLI_ASSOC);
//mysqli_free_result($getquery);
//mysqli_close($conn);

  $total_record = mysqli_num_rows($run_all);
  $total_page = ceil($total_record / $perpage);

  $endAt = min($start + $perpage, $total_record);
  $startAt = min($total_record, $start + 1);  
  echo "รายการที่ $startAt - $endAt จาก $total_record รายการ<br>";

}

?>
<div class="containerr">
<!--<table class = "table" style = "margin: 0 auto" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
	<tr>
		<th>ฝ่ายงาน</th>
		<th>ชื่อแฟ้ม</th>
		<th>ปีเริ่มต้น</th>
        <th>ปีสิ้นสุด</th>
        <th>เลขที่แฟ้ม</th>
		<th>รายละเอียด</th>
        <th>เพิ่มรายการเอกสาร</th>
	</tr>-->
  <?php 
  while ($doc = mysqli_fetch_assoc($run_limit)){ ?>
  <div class="req-element">
                <div class="req-list">
                    <div class="left-side">
                        <h5><?php echo "เลขที่แฟ้ม : " .$doc['doc_id'];?></h5>
                        <h4 style = "color : #34607F! important;"><?php echo  $doc['doc_name'];?></h4>
                        <div class="obj-group">
                            <h5><?php echo "ฝ่ายงาน : " . $doc['dept_name'];?></h5>
                            <p><?php echo "ระยะเวลาเอกสาร : ปี " .$doc['start_date']." - ".$doc['end_date'];?></p>
                        </div>
                    </div>

                    <div class="right-side">
                        <a href="search_detail.php?doc_id=<?php echo $doc['doc_id']?>" ><i class="fa-solid fa-magnifying-glass-plus"></i> ดูรายละเอียด </a>
                    </div>
                    </div>
                <hr>
      <!--
		    <tr>
        <td><?php echo $doc['dept_name']; ?></td>
		    <td><?php echo $doc['doc_name']; ?></td>
		    <td><?php echo $doc['start_date']; ?></td>
        <td><?php echo $doc['end_date']; ?></td>
        <td><?php echo $doc['doc_id']; ?></td>
		    <td><a href="search_detail.php?doc_id=<?php echo $doc['doc_id']?>" ><i class="fa-solid fa-magnifying-glass-plus"></i></a></td>
        <td><form class = "doc_list_form" action="doc_list_db.php" method = "POST" >
        <div class="put_to_list_bt">
          <button type="submit" name = "put_to_list" class="table-btn btn-info">เพิ่มเอกสาร</button>
          <input type="hidden" value = "เพิ่มรายการเอกสาร" name="put_to_list">
          <input type="hidden" value = <?php echo $doc['doc_id'] ?> name="doc_id">
          <input type="hidden" value = "<?php echo $doc['doc_name'] ?>" name= "doc_name" >
        </div> 
        </form>
    </td>
		</tr>
</table>-->
<?php }?>
</div>
</div>

<?php
  $total_record = mysqli_num_rows($run_all);
  $total_page = ceil($total_record / $perpage);
  
  $startAt = min($total_record, $start + 1); 
  $endAt = min($start + $perpage, $total_record);

  $visible_pages = 5;
  $halfVisible = floor($visible_pages / 2);
  $startPage = max(1, min($current_page - $halfVisible, $total_page - $visible_pages + 1));
  $endPage = min($total_page, $startPage + $visible_pages - 1);
   
  echo "รายการที่ $startAt - $endAt จาก $total_record รายการ<br>";
 ?>
 <nav <?php if(!isset($_GET['class_name']))  echo 'style="display:none;"'; ?>>
    <span class="pagination" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
      <li>
        <a href="group_search_result.php?class_name=<?php echo $class_name; ?>&page=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
        <?php for ($i = $startPage; $i <= $endPage; $i++){ 
          $activeClass = ($i == $current_page) ? 'active' : '';
          echo "<li>";
          echo "<a href='group_search_result.php?class_name=$class_name&page=$i' class='$activeClass' >$i</a>
      </li>";
        }?>
    
      <li>
        <a href="group_search_result.php?class_name=<?php echo $class_name; ?>&page=<?php echo $total_page;?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </span>
 </nav>

 <nav <?php if(!isset($_GET['box_id']))  echo 'style="display:none;"'; ?>>
    <span class="pagination" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
      <li>
        <a href="group_search_result.php?box_id=<?php echo $box_id; ?>&page=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
        <?php for ($i = $startPage; $i <= $endPage; $i++){ 
          $activeClass = ($i == $current_page) ? 'active' : '';
          echo "<li>";
          echo "<a href='group_search_result.php?box_id=$box_id&page=$i' class='$activeClass' >$i</a>
      </li>";
        }?>
    
      <li>
        <a href="group_search_result.php?box_id=<?php echo $box_id; ?>&page=<?php echo $total_page;?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </span>
 </nav>
 </div>
</div>
</body>

</html>  


