<?php
    session_start();
    include('server.php');
    include('nav_bar.php');

    $style = "";
    if(isset($_GET['doc_id'])){
        //echo "doc_id moratta!";
        //echo $_GET['doc_id'];
        $id = $_GET['doc_id'];
        
        $mysql = "SELECT doc.doc_name,doc.start_date, doc.end_date, doc.class_name, 
        dep.dept_name, doc.query,doc.description,doc.doc_id
        FROM document doc, department dep
        WHERE doc.dept_id = dep.dept_id 
        AND doc.doc_id = '$id';";
        $resalt = mysqli_query($conn,$mysql);
        $docs = mysqli_fetch_assoc($resalt);
        $_SESSION['docs_array'] = $docs;
        mysqli_free_result($resalt);
        mysqli_close($conn);
    }else{
      echo "<h1 class = 'text-danger' <?php echo $style;?>>ขออภัยในความไม่สะดวกจ้า</h1>";
    }
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
    <form class = "doc_list_form" action="doc_list_db.php" method = "POST" >
            <div class="put_to_list_bt">
                <button type="submit" name = "put_to_list" class="btn btn-info">เพิ่มรายการเอกสาร</button>
                <input type="hidden" value = "เพิ่มรายการเอกสาร" name="put_to_list">
                <input type="hidden" value = <?php echo $docs['doc_id'] ?> name="doc_id">
                <input type="hidden" value = "<?php echo $docs['doc_name'] ?>" name= "doc_name" >
            </div> 
        </form>
    <div class="detail">
            <div class="doc_name">
                <h2><?php echo $docs['doc_name'] ?></h2>
            </div>
            <hr>

        <div class="search-det">
                <div class="row">
                    <p style="width: 250px;">ชื่อแฟ้ม</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $docs['doc_name'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">เลขที่แฟ้ม</p>
                    <p>:</p>
                    <p><?php echo $docs['doc_id'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">ปีเริ่มต้นเอกสาร</p>
                    <p>:</p>
                    <p><?php echo $docs['start_date'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">ปีสิ้นสุดเอกสาร</p>
                    <p>:</p>
                    <p><?php echo $docs['end_date'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">หมวดเอกสาร</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $docs['class_name'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">ฝ่ายงาน</p>
                    <p>:</p>
                    <p><?php echo $docs['dept_name'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">คำสำคัญ</p>
                    <p>:</p>
                    <p class="long-det" ><?php echo $docs['query'] ;?></p>
                </div>
                <div class="row">
                    <p style="width: 250px;">คำอธิบายแฟ้ม</p>
                    <p>:</p>
                    <p class="long-det"><?php echo $docs['description'] ;?></p>
                </div>
    </div>
    </div>
    </div>


</div>
</body>

</html>  


