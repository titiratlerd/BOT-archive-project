<?php
session_start();
include('server.php');
include('nav_bar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>My Doc List</title>
</head>
<body>
<script>
    function terms_changed(termsCheckBox){
    //If the checkbox has been checked
    if(termsCheckBox.checked){
        //Set the disabled property to FALSE and enable the button.
        document.getElementById("submit_button").disabled = false;
    } else{
        //Otherwise, disable the submit button.
        document.getElementById("submit_button").disabled = true;
    }
}
</script>
<div class="content-container">
    <div class="doc_list-container">
        <h2 class = "page-header">รายการเอกสารที่เลือก</h2>
    </div>

<?php
    $user_id = $_SESSION['user_id'];
    $q_from_waiting_list = "SELECT waitinglist.doc_id, document.doc_name 
    FROM waitinglist, document WHERE waitinglist.user_id = '$user_id' 
    AND document.doc_id = waitinglist.doc_id";
    $from_waiting_list = mysqli_query($conn,$q_from_waiting_list);
    unset($_SESSION['new_docs']);

    while ($doc = mysqli_fetch_assoc($from_waiting_list)) {
        if(!isset($_SESSION['new_docs'])){
            $_SESSION['new_docs'][0] = array('doc_id'=>$doc['doc_id'],
                                        'doc_name'=>$doc['doc_name']);
            $rel_count = count($_SESSION['new_docs']);
        }else{
            $count = count($_SESSION['new_docs']);
            $_SESSION['new_docs'][$count] = array('doc_id'=>$doc['doc_id'],
                                        'doc_name'=>$doc['doc_name']);
            $rel_count = count($_SESSION['new_docs']);
    }}
        //echo "<br>";
        //if(isset($_SESSION['new_docs']))
        ////print_r($_SESSION['new_docs']);
        //$_SESSION['docs_from_db'] = $result;
        //print_r($_SESSION['docs_from_db']);
        //print_r($_SESSION['docs']);

    if(isset($_SESSION['new_docs'])){
        if($_SESSION['new_docs'] != null) { ?>
        <form class = "doc_list_form" action="preview.php" method = "POST">
        <div class="first-row">
            <div class="input-group-obj">
                            <label for="objective">วัตถุประสงค์การขอใช้เอกสาร</label>
                            <select id="objective" type="text" name="objective">
                                <option value="เขียนบทความ">เขียนบทความ</option>
                                <option value="ใช้ในราชการ">ใช้ในราชการ</option>
                                <option value="ทำวิจัย">ทำวิจัย</option>
                                <option value="ทำวิทยานิพนธ์">ทำวิทยานิพนธ์</option>
                                <option value="จัดนิทรรศการ">จัดนิทรรศการ</option>
                                <option value="จัดทำหนังสือ">จัดทำหนังสือ</option>
                                <option value="จัดทำบทละครหรือบทภาพยนตร์">จัดทำบทละครหรือบทภาพยนตร์</option>
                                <option value="ทำสารคดี">ทำสารคดี</option>
                                <option value="ทำรายงาน">ทำรายงาน</option>
                                <option value="ทำสารนิพนธ์">ทำสารนิพนธ์</option>
                                <option value="ค้นประวัติวงศ์ตระกูล">ค้นประวัติวงศ์ตระกูล</option>
                                <option value="ค้นประวัติวงศ์ตระกูล">ทำสื่อการเรียนการสอน</option>
                                </select>
                            </select>
                        </div>
                <div class="put_to_list_bt" style = "margin: 0px !important;">
                    <button type="submit" name = "create_request" class="btn_submit" id ="submit_button" disabled>สร้างคำขอใช้เอกสาร</button>
                </div>
            </div>
        <div class="first-row">
            <div class="det-obj">
            <label for="det_objective"style="padding-bottom:10px;">วัตถุประสงค์โดยละเอียด</label>
            <textarea autofocus="autofocus" id="myTextarea" name="det_objective" style="resize: none; font-size : 16px;" cols="40" rows="5"></textarea>
            </div>
        </div>
        <div class="second-row">
        <button name = 'remove' class='del_btn'><i class='fa-solid fa-trash'></i></button>
                        <input name = 'doc_id' type='hidden' value = '$value[doc_id]'>

        <table class="table fixed-width-table" cellpadding = "10" cellspacing = "1" >
            <tr>
            <th>ชื่อแฟ้ม</th>
            <th>เลขที่แฟ้ม</th>
            <th><label for="checkAll">All</label>
                <input type="checkbox" id="checkAll" onclick = 'terms_changed(this)'></th>
            </tr>
        </thead>
        <tbody>

        <?php }else{
            echo "<div class='no_records'>ตอนนี้ยังไม่มีเอกสารที่เลือกไว้</div>"; } 
    }else{
        echo "<div class='no_records'>ตอนนี้ยังไม่มีเอกสารที่เลือกไว้</div>";
    }
        
?>


    <?php
    //print_r($_SESSION['docs']);
    if(isset($_SESSION['new_docs'])){
    foreach($_SESSION['new_docs'] as $key => $value)
    {
        
        echo"
        <tr>
        <td style = 'text-align: left;'>$value[doc_name]</td>
        <td style = 'text-align: center;'>$value[doc_id]</td>
        <td style = 'text-align: center;'>
            <input class = 'checkbox' type='checkbox' name='select[]' value='$value[doc_id]' onclick = 'terms_changed(this)'>
            <script>
                // JavaScript code will go here
                $(document).ready(function() {
                    // When the 'Check All' checkbox is clicked
                    $('#checkAll').click(function() {
                        // Get the state of the 'Check All' checkbox
                        var isChecked = $(this).prop('checked');
            
                        // Set the state of all checkboxes with the 'checkbox' class to match the 'Check All' checkbox
                        $('.checkbox').prop('checked', isChecked);
                    });
            
                    // When any of the individual checkboxes are clicked
                    $('.checkbox').click(function() {
                        // Check if all checkboxes with the 'checkbox' class are checked
                        var allChecked = $('.checkbox:checked').length === $('.checkbox').length;
            
                        // Update the 'Check All' checkbox based on the state of individual checkboxes
                        $('#checkAll').prop('checked', allChecked);
                    });
                });
            </script>
            </form>
        </td>
        </tr>
        ";
    }
    }  
    ?>
  </tbody>
        </table>
    </div>
<div class="total third-row">
        <h4>
        <?php  if(isset($_SESSION['new_docs']) && $_SESSION['new_docs'] != null){ 
        echo "รวมเอกสารทั้งหมด". " <strong>$rel_count</strong>"."  รายการ";}?></h4>
    </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>