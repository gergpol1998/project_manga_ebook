function check_telephone() {
  var telephone = document.getElementById("txt_telephone");
  if (telephone.value == "") {
    alert("กรุณากรอกเบอร์โทรศัพท์");
    telephone.focus();
    return false;
  }
  if (telephone.value.length > 10 || telephone.value.length < 10) {
    alert("กรุณากรอกเบอร์โทรศัพท์จำนวน 10 หลัก");
    telephone.focus();
    return false;
  }
}

function check_bankaccount() {
  var bankacc = document.getElementById("txt_bankaccount");
  if (bankacc.value == "") {
    alert("กรุณากรอกเลขบัญชี");
    bankacc.focus();
    return false;
  }
  if (bankacc.value.length > 10 || bankacc.value.length < 10) {
    alert("กรุณากรอกเลขบัญชี 10 หลัก");
    bankacc.focus();
    return false;
  }
}

function sweetalerts(title, type, text, location) {
  setTimeout(function () {
    swal(
      {
        title: title, //ข้อความ เปลี่ยนได้ เช่น บันทึกข้อมูลสำเร็จ!!
        type: type, //success, warning, danger
        text: text,
        timer: 2000, //ระยะเวลา redirect 3000 = 3 วิ เพิ่มลดได้
        showConfirmButton: false, //ปิดการแสดงปุ่มคอนเฟิร์ม ถ้าแก้เป็น true จะแสดงปุ่ม ok ให้คลิกเหมือนเดิม
      },
      function () {
        window.location.href = location; //หน้าเพจที่เราต้องการให้ redirect ไป อาจใส่เป็นชื่อไฟล์ภายในโปรเจคเราก็ได้ครับ เช่น admin.php
      }
    );
  }, 1000);
}

