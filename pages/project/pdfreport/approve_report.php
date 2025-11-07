<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
include("tcpdf/class/class_curl.php");
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
       
    }
      // Page footer
      public function Footer()
      {
          $this->SetY(-15);
  
          $this->Line(17, $this->GetY(), 193, $this->GetY());
  
        
          
          // Set font
          $this->SetFont('THSarabun', '', 10);
  
          // Contact
          $this->setX(40);
          $this->Cell(0, 10, 'แบบคำขออนุมัติโครงการ โดย กองแผนงาน มหาวิทยาลัยมหามกุฏราชวิทยาลัย | https://proms.mbu.ac.th', 0, false, 'L');
  
          // Page number
          $this->Cell(0, 10, 'หน้า'.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R');
      }

}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// กำหนดรายละเอียดของไฟล์ pdf
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ninenik');
$pdf->SetTitle('TCPDF table report');
$pdf->SetSubject('TCPDF ทดสอบ');
$pdf->SetKeywords('TCPDF, PDF, ทดสอบ,ninenik, guide');

// กำหนดข้อความส่วนแสดง header
// set header
$pdf->setPrintHeader(false);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->setFooterData(
    array(0,64,0),  // กำหนดสีของข้อความใน footer rgb 
    array(220,44,44)   // กำหนดสีของเส้นคั่นใน footer rgb 
);

// กำหนดฟอนท์ของ header และ footer  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// ำหนดฟอนท์ของ monospaced  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// กำหนดขอบเขตความห่างจากขอบ  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// กำหนดแบ่่งหน้าอัตโนมัติ
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// กำหนดสัดส่วนของรูปภาพ  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// อนุญาตให้สามารถกำหนดรุปแบบ ฟอนท์ย่อยเพิมเติมในหน้าใช้งานได้
$pdf->setFontSubsetting(true);

// กำหนด ฟอนท์
$pdf->SetFont(THSarabun, '', 13);

// เพิ่มหน้า 
$pdf->AddPage();


$path_info = pathinfo($_SERVER['REQUEST_URI']);
$http = ($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']."://":"http://";
$host = $_SERVER['SERVER_NAME'];
$pathDir = $path_info['dirname']."/";
$url = $http.$host.$pathDir;


// เรียกใช้งาน ฟังก์ชั่นดึงข้อมูลไฟล์มาใช้งาน
 // path ไฟล์ 
// ถ้าทดสอบบน server ใช้เป็น http://www.example.com/data_html.php
// ภ้าทดสอบที่เครื่องก็ใช้ http://localhost/data_html.php

require_once 'server/database.php';
require_once 'server/functions.php';
$encoded_id = htmlspecialchars(strip_tags($_GET['project_id']));
$project_id = decrypt(urldecode($encoded_id));

$html1 = curl_get($url."pages/project/pdfreport/approve_data.php?id=$project_id");
$pdf->writeHTML($html1);

$pdf->AddPage();    
$html2 = curl_get($url."pages/project/pdfreport/approve_data_page2.php?id=$project_id");
$pdf->writeHTML($html2);


// $pdf->AddPage();    
// $html2 = curl_get($url."budget.php?project_id=$vid");
// $pdf->writeHTML($html2);



$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);



// แสดงไฟล์ pdf
$pdf->Output('ie_workreport_'.$vid.'.pdf', 'I');
?>
