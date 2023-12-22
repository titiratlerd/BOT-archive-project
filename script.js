let selectStatus = document.querySelector("#req-status");
let heading = document.querySelector(".right-col h2");
let container = document.querySelector(".req-wrapper");
let req_count = document.querySelector(".right-col h3");

selectStatus.addEventListener("change",function(){
    let statusName = this.value;
    heading.innerHTML = this[this.selectedIndex].text;

    let http = new XMLHttpRequest();


    http.onload = function(){
        if(this.readyState == 4 && this.status == 200){
            let response = JSON.parse(this.responseText);
            let req_num = 0;
            let out = "";
            for (let item of response){
                req_num += 1;
                out += `
                <div class="all-req-info">
                    <div class="req-element">
                        <div class="req-list">
                            <div class="left-side">
                                <h4>เลขที่คำขอ : ${item.request_id}</h4>
                                <h3>${item.name} ${item.surname}</h3>
                        <div class="obj-group">
                            <h5>วัตถุประสงค์ : ${item.objective}</h5>
                            <p>รายละเอียดวัตถุประสงค์ : ${item.det_obj}</p>
                        </div>
                    </div>
                    <div class="right-side">
                        <h5>รายการเอกสาร ${item.count_req_doc} รายการ</h5>`;
                if (item.req_status == 'pending') {
                    out += `
                    <h4>คำขอรอดำเนินการ</h4>
                    `;
                }else if (item.req_status == 'success') {
                    out += `
                    <h4 style='border-color: #59D47B !important;'>ส่งเอกสารเสร็จสิ้น</h4>
                    `;
                }else {
                    out += `
                    <h4 style='border-color: black !important;'>พิจารณาแล้วไม่สามารถส่งเอกสารได้</h4>
                    `;
                }
                out += `
                    <a href="req_detail.php?request_id=${item.request_id}">ดูรายละเอียด</a>
                    </div>
                    </div>
                <hr>
                </div>
                </div>
                `;
            }
            req_count.innerHTML = `<h3>ทั้งหมด ${req_num} รายการ</h3>`;
            container.innerHTML = out;
        }}

    http.open('POST', "script.php");
    http.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    http.send("req_status="+statusName);

});


