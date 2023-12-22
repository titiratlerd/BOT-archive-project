<?php
    session_start();
    include('server.php');
    include('nav_bar.php');

    $user_id = $_SESSION['user_id'];
    //echo $user_id;
    $q_have_his = "SELECT req.request_id FROM request req
    WHERE req.user_id = '$user_id';";
    $have_his = mysqli_query($conn,$q_have_his);
    $have = mysqli_fetch_array($have_his);
    $submittime_arry = array();
    $req_id_arry = array();
    $indent50 = 'style = " margin-left: 50; "';
    $indent100 = 'style = " margin-left: 100; "';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
<div class="content-container">
        <div class="header-group">
            <h2 href="history.php" class = "page-header">ประวัติการขอเอกสาร<i class="fa-solid fa-clock-rotate-left nav-icon"></i></h2>
        </div>
    <?php

    //if($res != null){
        if ($have != null){
            $get_user_info = "SELECT req.request_id, SUBSTRING(req.submit_time, 1, 10) AS submit_date, req_doc.doc_id, doc.doc_name 
            FROM request req, request_doc req_doc, document doc
            WHERE req.user_id = '$user_id'AND req_doc.request_id = req.request_id AND doc.doc_id = req_doc.doc_id;";
            $result = mysqli_query($conn,$get_user_info);
            while($his_rows = mysqli_fetch_assoc($result)){
                $Subtime = $his_rows['submit_date'];
                $Reqid = $his_rows['request_id']; 
                // Check whether the Company Table is Created f No Create the Table
                if(!in_array($Subtime,$submittime_arry)){
                    array_push($submittime_arry,$Subtime);
                    array_push($req_id_arry,$Reqid); 
                    // Create a table?>
                    <h4><?php 
                    $newDate = date("d/m/Y", strtotime($Subtime));  
                    echo $newDate;?></h4>

                        <div class="his-list">
                            <h5><?php echo 'เลขที่คำขอ : ' . $Reqid ?></h5>
                            <table class = "fixed-width-table" style = "border : 1px red !important; margin : 0px 40px 20px!important;">                           
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อแฟ้ม</th>
                                    <th>เลขที่แฟ้ม</th>                                      
                                </tr>

                            <?php                                        
                            //-----------------Add Row into the Selected Docs --------------------------------
                                                                    
                            $selectDoc_Query = "SELECT req.request_id, req_doc.doc_id, doc.doc_name 
                            FROM request req, request_doc req_doc, document doc
                            WHERE req.user_id = '$user_id'AND req_doc.request_id = req.request_id 
                            AND doc.doc_id = req_doc.doc_id AND SUBSTRING(req.submit_time, 1, 10) = '$Subtime'
                            AND req.request_id = '$Reqid'; ";
                            $selectDoc_Query_result = mysqli_query($conn,$selectDoc_Query);
                            $arrayDoc = array();
                            while($doc_rows = mysqli_fetch_assoc($selectDoc_Query_result)){                                     
                                $arrayDoc[] = $doc_rows; 
                            }                                                         
                            foreach($arrayDoc as $data){   
                            //echo '<h4>'.$data['request_id'] .'</h4>';                                                         
                            echo'<tr>';
                            // Search through the array print out value if see the Key  eg: 'id', 'firstname ' etc.
                            echo'<td>'.array_search($data,$arrayDoc) +1 .'</td>';
                            echo'<td style="text-align : left; width : 900px;">'.$data['doc_name'].'</td>';
                            echo'<td style="min-width : 150px;">'.$data['doc_id'].'</td>';
                            ?>
                            </tr>
                            
                            <?php
                            } ?>                                                                     
                        </table>
                        
                        </div>   
                <?php       
                        
                }else{
                    //echo "mai mee";
                    if(!in_array($Reqid,$req_id_arry)){
                        array_push($req_id_arry,$Reqid);
                        ?> 
                    
                
                        <h5><?php echo 'เลขที่คำขอ : ' . $Reqid ?></h5>
                            <table class = "fixed-width-table" style = " margin :0px 40px 20px!important;";>                           
                            <tr>
                                        
                                <th >ลำดับ</th>
                                <th>ชื่อแฟ้ม</th>
                                <th>เลขที่แฟ้ม</th>                                      
                                </tr>
                                <?php                                       
                                //-----------------Add Row into the Selected Docs --------------------------------
                                                                        
                                $selectDoc_Query = "SELECT req.request_id, req_doc.doc_id, doc.doc_name 
                                FROM request req, request_doc req_doc, document doc
                                WHERE req.user_id = '$user_id'AND req_doc.request_id = req.request_id 
                                AND doc.doc_id = req_doc.doc_id AND SUBSTRING(req.submit_time, 1, 10) = '$Subtime'
                                AND req.request_id = '$Reqid'; ";
                                $selectDoc_Query_result = mysqli_query($conn,$selectDoc_Query);
                                $arrayDoc = array();
                                while($doc_rows = mysqli_fetch_assoc($selectDoc_Query_result)){                                     
                                    $arrayDoc[] = $doc_rows; 
                                }
                                //print_r($arrayDoc);                                                         
                                foreach($arrayDoc as $data){   
                                //echo '<h4>'.$data['request_id'] .'</h4>';                                                         
                                echo'<tr>';
                                // Search through the array print out value if see the Key  eg: 'id', 'firstname ' etc.
                                echo'<td>'.array_search($data,$arrayDoc) +1 .'</td>';
                                echo'<td style="text-align : left; width : 900px;">'.$data['doc_name'].'</td>';
                                echo'<td style="min-width : 150px;">'.$data['doc_id'].'</td>';

                                ?>
                                </tr>
                                <?php
                                } 
                            }                                       
                        } ?>
                        </table>
                        
                    
                        
                <?php }
            }else{
                echo "<div class='no_records'>ตอนนี้คุณยังไม่มีประวัติการขอเอกสาร</div>";
            }?>
</div>
</body>
</html>