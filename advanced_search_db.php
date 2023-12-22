<?php
    session_start();
    include('server.php');
    include('nav_bar.php');
    $errors = array();
    $button = $_GET['adv_search_sub'];
    $search = $_GET['adv_search_keyword'];
    $search_option = $_GET['search_option'];

    //get 
    if(isset($_GET['dept_search'])){
      $search_by_dept = $_GET['dept_search'];
    }else{
      $search_by_dept = 'all';
    }
    $from_year = $_GET['start_date'];
    $untill_year = $_GET['end_date'];

    //create pagination
    $perpage = 20;
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }
    $start = ($page - 1) * $perpage; 
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

    //generate query
    $myquery  =  "SELECT dep.dept_name, doc.doc_name, doc.start_date, doc.end_date, doc.doc_id
    FROM document doc, department dep
    WHERE doc.dept_id = dep.dept_id";

    $myquery_noti = "";
    $group_search = array('class_name','box_id');

  // check if search is empty
  if (!empty($search) && !empty($search_option)) { 
    if($search_option != 'all'){

      if($search_option == 'doc_name'){
      $myquery .= " AND $search_option LIKE  ('%" . $search . "%')";
      $myquery_noti .= " การค้นหาด้วย ". "\" ชื่อแฟ้ม \"" ." โดยใช้คำค้น " . "\" $search \"";}

      elseif($search_option == 'query'){
        $myquery .= " AND $search_option LIKE  ('%" . $search . "%')";
        $myquery_noti .= " การค้นหาด้วย ". "\" คำสำคัญ \"" ." โดยใช้คำค้น " . "\" $search \"";
      }

      elseif($search_option == 'description'){
        $myquery .= " AND $search_option LIKE  ('%" . $search . "%')";
        $myquery_noti .= " การค้นหาด้วย ". "\" คำอธิบายแฟ้ม \"" ." โดยใช้คำค้น " . "\" $search \"";
      }

      elseif($search_option == 'class_name'){
        $myquery = "SELECT class_name, COUNT(DISTINCT doc_id) AS amount FROM document doc, department dep
        WHERE dep.dept_id = doc.dept_id AND class_name LIKE ('%" . $search . "%')"; 
        $myquery_noti .= " การค้นหาด้วย ". "\" หมวดเอกสาร \"" ." โดยใช้คำค้น " . "\" $search \"";
      }

      elseif($search_option == 'start_date'){
        $myquery .= " AND $search_option LIKE  ('%" . $search . "%')";
        $myquery_noti .= " การค้นหาด้วย ". "\" ปีเริ่มต้นเอกสาร \"" ." โดยใช้คำค้น " . "\" $search \"";
      }

      elseif($search_option == 'end_date'){
        $myquery .= " AND $search_option LIKE  ('%" . $search . "%')";
        $myquery_noti .= " การค้นหาด้วย ". "\" ปีสิ้นสุดเอกสาร \"" ." โดยใช้คำค้น " . "\" $search \"";
      }

      elseif($search_option == 'box_id'){
        //$myquery .= " AND box_id LIKE  ('%" . $search . "%')";
        $myquery = "SELECT box_id, COUNT(DISTINCT doc_id) AS amount FROM document doc, department dep
        WHERE dep.dept_id = doc.dept_id AND box_id LIKE ('%" . $search . "%')"; 
        $myquery_noti .= " การค้นหาด้วย ". "\" เลขที่กล่อง \"" ." โดยใช้คำค้น " . "\" $search \"";
      }
    }
    else{
      $myquery_noti .= " โดยใช้คำค้น " . "\" $search \"";
      $myquery .= " AND (doc.doc_id in (SELECT doc_id  
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
      WHERE dept_name LIKE ('%" . $search . "%'))))";
      }}


  // check if search by field is empty
  if($search_by_dept != 'all'){
    $myquery .= " AND doc.dept_id = '$search_by_dept'";
    $sqll = "SELECT dept_name FROM department where dept_id = '$search_by_dept';";
    $doc_name = mysqli_query($conn,$sqll);
    $doccu = mysqli_fetch_assoc($doc_name);
    mysqli_free_result($doc_name);
    $myquery_noti .= " ที่เป็นเอกสารของ " . $doccu['dept_name'];
  }
  if (!empty($from_year) && empty($untill_year)) { 
    $myquery .= " AND doc.end_date >= $from_year";
    $myquery_noti .= " ตั้งแต่ ปี " . "\" $from_year \"";
    $_SESSION['date_query'] = "AND doc.end_date >= $from_year";
  }
  elseif (empty($from_year) && !empty($untill_year)) {
    $myquery .= " AND doc.end_date <= $untill_year";
    $myquery_noti .= " จนถึง ปี " . "\" $untill_year \"";
    $_SESSION['date_query'] = " AND doc.end_date <= $untill_year";
  }
  elseif (!empty($from_year) && !empty($untill_year)) {
    $_SESSION['date_query'] = " AND doc.end_date <= $untill_year AND doc.end_date >= $from_year ";
    $myquery_date = $_SESSION['date_query'];
    $myquery .= $myquery_date;
    $myquery_noti .= " ตั้งแต่ ปี " . "\" $from_year \""." จนถึง ปี " . "\" $untill_year \"";
  }
  if(in_array($search_option,$group_search)){
    $myquery .= " GROUP BY ".$search_option;
  }
  $myquery .= " ORDER BY doc.end_date";

  $q_limit = $myquery . " limit {$start} , {$perpage}";
  $result_per_page = mysqli_query($conn,$q_limit);
  $result_all = mysqli_query($conn,$myquery);
  $foundnum = mysqli_num_rows($result_all);
  

  //echo  $myquery;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search page</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="content-container">
<form name="form2" method="get" action="advanced_search_db.php">
        <div class="search-main-result-page" style= "width: 90%!important; padding: 10px!important;">
            <fieldset>
                <div class="search-group">
                    <select class = "select-op"id="search-option" type="text" name="search_option" value="<?php echo $search_option; ?>">
                        <option value="all">-ทั้งหมด-</option>
                        <option value="doc_name">ชื่อแฟ้ม</option>
                        <option value="query">คำสำคัญ</option>
                        <option value="description">คำอธิบายแฟ้ม</option>
                        <option value="class_name">หมวดเอกสาร</option>
                        <option value="start_date">ปีเริ่มต้นเอกสาร</option>
                        <option value="end_date">ปีสิ้นสุดเอกสาร</option>
                        <option value="box_id">เลขที่กล่อง</option>
                    </select>

                <input class = "search-bar" id = "adv_search_bar" type="text" placeholder="กรุณากรอกคำค้น" 
                name="adv_search_keyword" aria-label="search" value = "<?php echo $search; ?>" require>
            </div>
            </fieldset>
            <div class="second-part">
            <fieldset>
                <div class="search_by_dept_name">
                    <div class="search-group">
                        <?php
                            $mysql = "SELECT * from department ORDER BY dept_name;";
                            $all_dept = mysqli_query($conn,$mysql);
                        ?>
                            <label> ฝ่ายงานเจ้าของเอกสาร </label>
                            <select name = "dept_search">
                            <option value='all'>-ทั้งหมด-</option>
                        <?php
                            while($dept = mysqli_fetch_array($all_dept,MYSQLI_ASSOC)):;
                        ?>
                            <option value= "<?php echo $dept["dept_id"]?>">
                                
                            <?php echo $dept["dept_name"];?></option>
                            <?php endwhile; ?>"
                        </select>
                    </div>
                </div>
            </fieldset>
                <fieldset>
                <div class="search_by_start_date">
                    <div class="search-group">
                    <?php
                            $min_query = "SELECT MIN(end_date) from document;";
                            $min_year = mysqli_query($conn,$min_query);
                            $min = mysqli_fetch_assoc($min_year);
                            mysqli_free_result($min_year);
                            /*print_r($min);
                            /*echo $min["MIN(end_date)"];*/
                    ?>
                        <label for="start_date">ตั้งแต่ ปี</label>
                        <input class = "date-search-bar" type="int" name = "start_date" value = "<?php echo $min["MIN(end_date)"];?>">
                    <?php
                            $max_query = "SELECT MAX(end_date) from document;";
                            $max_year = mysqli_query($conn,$max_query);
                            $max = mysqli_fetch_assoc($max_year);
                            mysqli_free_result($max_year);
                    ?>
                        <label for="end_date">ถึง ปี  </label>
                        <input class = "date-search-bar" type="int" name = "end_date" value = "<?php echo $max["MAX(end_date)"];?>">
                    </div>
                    </fieldset>
                    </div>
                    <div class="adv_search">
                    <button type = "submit" name = "adv_search_sub" class = "search-bt big-bt">
                        <span class="icon"><i class="fa-solid fa-magnifying-glass"></i></span> ค้นหา
                    </button>
                    </div>
                    <div class="under-search-bar no-margin">
                        <a style = " max-width: fit-content;" href="index.php">Basic search</a>
                    </div>
            </div>
	    </form>
  <?php
    if ($foundnum==0)
    {
      echo "<h2/>ไม่พบผลการค้นหา<p>$myquery_noti</p></h2>";
    }
    else{

    echo "<h2 class = 'page-header-search'> ผลการค้นหาทั้งหมด <strong>$foundnum</strong> รายการ <p style = 'text-align : left !important;'> $myquery_noti </p></h2>";      
    $getquery = mysqli_query($conn,$myquery);
    $docs = mysqli_fetch_all($getquery, MYSQLI_ASSOC);
    mysqli_free_result($getquery);
    //mysqli_close($conn);
    }

    $total_record = $foundnum;
    $total_page = ceil($total_record / $perpage);
  
    $endAt = min($start + $perpage, $total_record);
    $startAt = min($total_record, $start + 1);
    if($foundnum!=0) 
      echo "รายการที่ $startAt - $endAt จาก $total_record รายการ<br>";

  ?>
  <div class="containerr">

<table class = "table center-table" <?php if(($foundnum == 0) || ($search_option != 'box_id')) echo 'style="display:none;"'; ?>>
	<tr>
		<th>เลขที่กล่อง</th>
		<th>จำนวนเอกสาร</th>
	</tr>
  <?php 
 if($foundnum != 0)
	foreach($docs as $doc): ?>
		<tr>
        <td style="text-align : left; text-decoration :none;"><a href="group_search_result.php?box_id=<?php echo $doc['box_id']?>"><?php echo $doc['box_id']; ?></a></td>
		<td><?php echo $doc['amount']; ?></td>
        </div> 
        </form>
    </td>
		</tr>
<?php endforeach; ?>
</table>

<table class = "table center-table"  <?php if(($foundnum == 0) || ($search_option != 'class_name')) echo 'style="display:none;"'; ?>>
	<tr>
		<th>หมวดเอกสาร</th>
		<th>จำนวนเอกสาร</th>
	</tr>
  <?php 
 if($foundnum != 0)
	foreach($docs as $doc): ?>
		<tr>
        <td style="text-align : left; text-decoration :none !important;"><a href="group_search_result.php?class_name=<?php echo $doc['class_name']?>"><?php echo $doc['class_name']; ?></a></td>
		<td><?php echo $doc['amount']; ?></td>
        </div> 
        </form>
    </td>
		</tr>
<?php endforeach; ?>
</table>


<!--<table class = "table margin-20-table" <?php if(($foundnum == 0) || (in_array($search_option,$group_search)))  echo 'style="display:none;"'; ?>>
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
  while ($doc = mysqli_fetch_assoc($result_per_page)) {?>
     <div class="req-element" <?php if(($foundnum == 0) || (in_array($search_option,$group_search)))  echo 'style="display:none;"'; ?>>
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
      <!--<tr>
        <td style="text-align : left;"><?php echo $doc['dept_name']; ?></td>
        <td style="text-align : left;"><?php echo $doc['doc_name']; ?></td>
        <td><?php echo $doc['start_date']; ?></td>
        <td><?php echo $doc['end_date']; ?></td>
        <td><?php echo $doc['doc_id']; ?></td>
        <td><a href="search_detail.php?doc_id=<?php echo $doc['doc_id']?>"><i class="fa-solid fa-circle-info"></i></a></td>
        <td>
          <form class = "doc_list_form" action="doc_list_db.php" method = "POST" >
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
  $query2 = mysqli_query($conn, $myquery);
  $total_record = mysqli_num_rows($query2);
  $total_page = ceil($total_record / $perpage);
  
  $startAt = min($total_record, $start + 1); 
  $endAt = min($start + $perpage, $total_record);

  $visible_pages = 5;
  $halfVisible = floor($visible_pages / 2);
  $startPage = max(1, min($current_page - $halfVisible, $total_page - $visible_pages + 1));
  $endPage = min($total_page, $startPage + $visible_pages - 1);
  
  if($foundnum!=0) 
  echo "รายการที่ $startAt - $endAt จาก $total_record รายการ<br>";
 ?>
 <nav>
    <span class="pagination" <?php if($foundnum==0) echo 'style="display:none;"'; ?>>
      <li>
        <a href="advanced_search_db.php?search_option=<?php echo $search_option; ?>&adv_search_keyword=<?php echo $search; ?>&dev_search=<?php echo $search_by_dept; ?>&start_date=<?php echo $from_year; ?>&end_date=<?php echo $untill_year; ?>&adv_search_sub=&page=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
        <?php for ($i = $startPage; $i <= $endPage; $i++){ 
          $activeClass = ($i == $current_page) ? 'active' : '';
          echo "<li>";
          echo "<a href='advanced_search_db.php?search_option=$search_option&adv_search_keyword=$search&dev_search=$search_by_dept&start_date=$from_year&end_date=$untill_year&adv_search_sub=&page=$i' class='$activeClass' >$i</a>
      </li>";
        }?>
    
      <li>
        <a href="advanced_search_db.php?search_option=<?php echo$search_option; ?>&adv_search_keyword=<?php echo $search; ?>&dev_search=<?php echo $search_by_dept; ?>&start_date=<?php echo $from_year; ?>&end_date=<?php echo $untill_year; ?>&adv_search_sub=&page=<?php echo $total_page;?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </span>
 </nav>
 </div>
      </div>
</body>
</html>