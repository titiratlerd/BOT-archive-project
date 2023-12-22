<?php
    session_start();
    include('server.php');
    include('nav_bar.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <script src="https://kit.fontawesome.com/f4c9eae73b.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
</head>
<body>
<div class="content-container">
<h2 class = "page-header">คำถามที่พบบ่อย<i class="fa-regular fa-circle-question nav-icon"></i></h2>
<div class="accor_group">
  <button class="accordion">ขั้นตอนวิธีการใช้เอกสารจดหมายเหตุ หลังจากสร้างคำขอใช้เอกสารแล้ว จะต้องดำเนินการอย่างไรต่อ?</button>
  <div class="panel">
    <p style="font-size : 16px !important; text-align : left;">หลังจากที่ท่านได้สร้างคำขอเอกสารผ่านระบบสืบค้นนี้เรียบร้อยแล้ว ขั้นตอนต่อไปมีดังนี้
      <br>
      <br>
    1. เจ้าหน้าที่ปฏิบัติการจดหมายเหตุจะนำรายชื่อเอกสารที่ท่านต้องการ ไปขออนุญาตการใช้งานจากฝ่ายบริหารข้อมูลและดาต้าอนาไลติกส์ ธนาคารแห่งประเทศไทย
    <br>
    2. หากเอกสารที่ท่านต้องการได้รับอนุญาตให้ใช้งานเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับไปทางอีเมลที่ท่านใช้สมัครสมาชิกไว้ เพื่อนัดวันเวลาเข้ามาใช้งานเอกสารต่อไป
    <br>
    <br>
    โดยระยะเวลาที่ดำเนินการขึ้นอยู่กับจำนวนเอกสารที่ท่านส่งคำขอเข้ามา
    </p>
  </div>

  <button class="accordion">การขอใช้บริการเอกสารจดหมายเหตุ มีค่าใช้จ่ายหรือไม่</button>
  <div class="panel">
    <p>การใช้งานเอกสารจดหมายเหตุ "ไม่มีค่าใช้จ่าย"
    <br>
    <br>
      ในกรณีที่ท่านต้องการทำสำเนาจดหมายเหตุ ทางหอจดหมายเหตุธนาคารแห่งประเทศไทย มีบริการทำสำเนาเอกสาร
      <br>
      อัตราค่าบริการ หน้าละ 1 บาท 
      รับชำระผ่านบัญชีธนาคารเท่านั้น ไม่รับเงินสด
      และชำระที่ธนาคารแห่งประเทศไทยเท่านั้น 
      <br>
      <br>
      โดยหากท่านต้องการใช้บริการทำสำเนา สามารถแจ้งเจ้าหน้าที่ได้ในวันที่นัดเข้ามาเพื่อดูเอกสาร
    </p>
  </div>

  <button class="accordion">หากมีคำถามจะสามารถติดต่อได้ทางช่องทางใด</button>
  <div class="panel">
    <p>หากมีข้อสงสัยติดต่อที่ อีเมล archives@bot.or.th</p>
  </div>
</div>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
</div>
</body>
</html>