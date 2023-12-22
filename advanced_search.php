<?php 
    session_start();
    include('server.php');
    include('nav_bar.php'); 
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <!-- <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>advanced search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="homeheader">
    </div>
    <div class="content">        
        <form name="form2" method="get" action="advanced_search_db.php">
        <div class="search-main">
            <div class="search-option">
                <fieldset>
                    <div class="search-group">
                        <select class = "select-op"id="search-option" type="text" name="search_option">
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
                    name="adv_search_keyword" aria-label="search" >
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
        </div>

        
    </div>
    </div>
</body>
</html>