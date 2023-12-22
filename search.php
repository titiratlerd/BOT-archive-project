
<?php
    session_start();
    include('server.php');
    include('nav_bar.php');
    
    $button = $_GET ['submit'];
    $search = $_GET ['search'];

    $perpage = 20;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }
    $start = ($page - 1) * $perpage; 
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

      
    // connect to database
    $sql ="SELECT dep.dept_name, doc.doc_name, doc.start_date, doc.end_date, doc.doc_id
      FROM document doc, department dep
      WHERE doc.dept_id = dep.dept_id 
      AND (doc.doc_id in (SELECT doc_id  
          FROM document
          WHERE doc_name LIKE ('%" . $search . "%') 
          OR doc_id LIKE ('%" . $search . "%')
          OR box_id LIKE ('%" . $search . "%')
          OR end_date LIKE ('%" . $search . "%')
          OR start_date LIKE ('%" . $search . "%')
          OR end_date LIKE ('%" . $search . "%')
          OR description LIKE ('%" . $search . "%')
          OR query LIKE ('%" . $search . "%')
          OR class_name LIKE ('%" . $search . "%')) 
		  OR
		  (dep.dept_id in (SELECT dept_id  
        FROM department
        WHERE dept_name LIKE ('%" . $search . "%'))))
      ORDER BY doc.end_date limit {$start} , {$perpage}";

    $sql_all = "SELECT dep.dept_name, doc.doc_name, doc.start_date, doc.end_date, doc.doc_id
      FROM document doc, department dep
      WHERE doc.dept_id = dep.dept_id 
      AND (doc.doc_id in (SELECT doc_id  
          FROM document
          WHERE doc_name LIKE ('%" . $search . "%') 
          OR doc_id LIKE ('%" . $search . "%')
          OR box_id LIKE ('%" . $search . "%')
          OR end_date LIKE ('%" . $search . "%')
          OR start_date LIKE ('%" . $search . "%')
          OR end_date LIKE ('%" . $search . "%')
          OR description LIKE ('%" . $search . "%')
          OR query LIKE ('%" . $search . "%')
          OR class_name LIKE ('%" . $search . "%')) 
		  OR
		  (dep.dept_id in (SELECT dept_id  
        FROM department
        WHERE dept_name LIKE ('%" . $search . "%'))))
      ORDER BY doc.end_date";

    $run = mysqli_query($conn,$sql);
    $run_all = mysqli_query($conn,$sql_all);
    $foundnum = mysqli_num_rows($run_all);
      
    // get num of results stored in database
      
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search page</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
</head>
<body>
<form class="form2" method="get" action="search.php">
        <div class="search-main-result-page">
            <div class="search-bar-group">
		        <input class = "search-bar" type="text" placeholder="พิมพ์คำค้นที่ต้องการค้นหา" name="search" aria-label="search" value = "<?php echo $search; ?>" required>
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
<!--<form name="search_form" method="get" action="search.php">
            <div class="search-bar-group">
		        <input class = "search-bar" type="text" placeholder="พิมพ์คำค้นที่ต้องการค้นหา" name="search" aria-label="search" required>
                <button class = "search-bt" name = "submit">
                    <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span> ค้นหา
                </button>
                <input type="hidden" name="submit"></input>
            </div>
            <a class = "under-search-bar" href="advanced_search.php" style = "color: #34607F; ">Advanced search</a>
</form>-->

<div class="content-container">
<?php
  if ($foundnum==0)
  {
    echo "ไม่พบผลการค้นหาของคำค้น'<b> $search </b>'.";
}
else{

echo "<h2 class = 'page-header-search'>ผลการค้นหาทั้งหมด <strong> $foundnum</strong> รายการ <p style = 'text-align : left !important;'>ของคำค้น \"" . "  " .$search. "  " ."\" </p></h2>";      
?>
<div class="containerr">
 <div class="roww">
 <div class="col-lg-12">
<?php
  $query2 = mysqli_query($conn, $sql_all);
  $total_record = mysqli_num_rows($query2);
  $total_page = ceil($total_record / $perpage);

  $endAt = min($start + $perpage, $total_record);
  $startAt = min($total_record, $start + 1);  
  echo "<h4 style='margin-bottom : 10px; color: #444;!important;'>รายการที่ $startAt - $endAt จาก $total_record รายการ</h4>";
?>


<!--<table class = "table search-table" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
	<tr>
		<th style = "border-radius: 20px 0px 0px 0px;">ฝ่ายงาน</th>
		<th style = "border-radius: 0px 0px 0px 0px;">ชื่อแฟ้ม</th>
		<th style = "border-radius: 0px 0px 0px 0px;">ปีเริ่มต้น</th>
    <th style = "border-radius: 0px 0px 0px 0px;">ปีสิ้นสุด</th>
    <th style = "border-radius: 0px 0px 0px 0px;">เลขที่แฟ้ม</th>
		<th style = "border-radius: 0px 0px 0px 0px;">รายละเอียด</th>
    <th style = "border-radius: 0px 20px 0px 0px;">เพิ่มรายการเอกสาร</th>
	</tr>-->
<?php 
  while ($doc = mysqli_fetch_assoc($run)) { ?>
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
    <td style="text-align : left;"><?php echo $doc['dept_name']; ?></td>
		<td style="text-align : left;"><?php echo $doc['doc_name']; ?></td>
		<td><?php echo $doc['start_date']; ?></td>
    <td><?php echo $doc['end_date']; ?></td>
    <td><?php echo $doc['doc_id']; ?></td>
		<td><a href="search_detail.php?doc_id=<?php echo $doc['doc_id']?>" ><i class="fa-solid fa-magnifying-glass-plus"></i></a></td>
    <td><form class = "doc_list_form" action="doc_list_db.php" method = "POST" >
        <div class="put_to_list_bt">
          <button type="submit" name = "put_to_list" class="table-btn btn-info">เพิ่มเอกสาร</button>
          <input type="hidden" value = "เพิ่มรายการเอกสาร" name="put_to_list">
          <input type="hidden" value = <?php //echo $doc['doc_id'] ?> name="doc_id">
          <input type="hidden" value = "<?php //echo $doc['doc_name'] ?>" name= "doc_name" >
        </div> 
        </form>
    </td>
		</tr>
</table>-->
<?php }?>
</div>
</div>

<?php
  $query2 = mysqli_query($conn, $sql_all);
  $total_record = mysqli_num_rows($query2);
  $total_page = ceil($total_record / $perpage);
  
  $startAt = min($total_record, $start + 1); 
  $endAt = min($start + $perpage, $total_record);

  $visible_pages = 5;
  $halfVisible = floor($visible_pages / 2);
  $startPage = max(1, min($current_page - $halfVisible, $total_page - $visible_pages + 1));
  $endPage = min($total_page, $startPage + $visible_pages - 1);
  //$startPage = max(1, $current_page - floor( $visible_pages/ 2));
  //$endPage = min($startPage + $visible_pages - 1, $total_page);
   
  echo "<h4 style='margin-bottom : 10px; color: #444;!important;'>รายการที่ $startAt - $endAt จาก $total_record รายการ</h4>";
 ?>
 <nav>
    <span class="pagination" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
      <li>
        <a href="search.php?search=<?php echo $search; ?>&submit=&page=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
        <?php for ($i = $startPage; $i <= $endPage; $i++){ 
          $activeClass = ($i == $current_page) ? 'active' : '';
          echo "<li>";
          echo "<a href='search.php?search=$search&submit=&page=$i' class='$activeClass' >$i</a>
      </li>";
        }?>
    
      <li>
        <a href="search.php?search=<?php echo $search; ?>&submit=&page=<?php echo $total_page;?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </span>
 </nav>
 </div>
 </div>
 </div>
 <?php } ?>
  <!-- /container -->
  </div>
</body>
</html>