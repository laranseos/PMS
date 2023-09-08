<?php 
include('includes/checklogin.php');
include_once('libs/fpdf.php');
check_login();

class PDF extends FPDF
{
  private $headerText;

  // Set the header text
  public function setHeaderText($text)
  {
      $this->headerText = $text;
  }
// Page header
function Header()
{
    // Logo
    $this->Image('companyimages/poultrylogo.png',10,5,50);
    $this->SetFont('Arial','B',12);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(80,10,$this->headerText,1,0,'C');
    // Line break
    $this->Ln(20);
}
 
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

if(isset($_GET['download']))
{

    $did=$_GET['download'];
    if($did=='broiler_mortality') {
      $today = date("Y-m-d");
      $filename = "Broiler_Mortality(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $fname=$_SESSION['fname'];
      $cate='Broiler';
      $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate  ORDER BY id DESC";

      $query = $dbh -> prepare($sql);
      $query->bindParam(':fname',$fname,PDO::PARAM_STR);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10), "Age(weeks)",str_pad("Record Date",10), "Quantity", "Cause of Death"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        {
            fputcsv($fh, array(
            str_pad($row->CategoryFowlRun, 10),
            str_pad((intval($row->age/7)+1),10),
            str_pad($row->CategoryDate,10),
            str_pad($row->CategoryCount, 10),
            str_pad($row->CategoryDescription, 10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='broiler_feed'){
      $today = date("Y-m-d");
      $filename = "Broiler_Feed(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Broiler';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10),str_pad("Age(Weeks)",10), "Quantity", str_pad("Record Date",10), "Total Feed"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            if($row->count=='0') $row->count='-';
            if($row->total=='0') $row->total='-';
            fputcsv($fh, array(
            str_pad($row->fowlRun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count,10),
            str_pad(date("Y-m-d", strtotime($row->posting)),10),
            str_pad($row->total,10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='broiler_weight'){
      $today = date("Y-m-d");
      $filename = "Broiler_Weight(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Broiler';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblweight where tblweight.category=:cate and tblweight.fname=:fname ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10),str_pad("Age(Weeks)",10),"Quantity", str_pad("Record Date",10), "Weight"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            fputcsv($fh, array(
            str_pad($row->fowlrun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count, 10),
            str_pad(date("Y-m-d", strtotime($row->date)),10),
            str_pad($row->weight,10),
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='broiler_vaccination'){
      $today = date("Y-m-d");
      $filename = "Broiler_Vaccination(".$today.").pdf";		 
      

      $display_heading = array('No', 'Fowl Run', 'Age', 'Quantity', 'Disease', 'Vaccination', 'Dose','Method');
      $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);

      $pdf = new PDF();
      $pdf->setHeaderText($today);
      //header
      $pdf->AddPage();
      //foter page
      $pdf->AliasNbPages();
      $pdf->SetFont('Arial','B',12);
      // Set the header
      foreach ($display_heading as $key => $heading) {
        $pdf->Cell($cellWidths[$key], 10, $heading, 1, 0, 'C');
      }


      $fname=$_SESSION['fname'];
      $cate='Broiler';
      
      $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
      
      $currentfowl = $row->CategoryFowlRun;
      $query = $dbh -> prepare($sql);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query->execute();
      $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
      $cnt=1;
      if($query->rowCount() > 0)
      {  
         foreach($resulta as $rowa)
         { 
           $fowl = $rowa['fowlrun'];
           $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
           $query = $dbh -> prepare($sql);
           $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);

           $query->execute();
           $resultb=$query->fetchAll(PDO::FETCH_OBJ);
           if($query->rowCount() > 0)
           {
             foreach($resultb as $rowb){
               $c_date = $rowb->PostingDate;
               $postingDate = new DateTime($c_date);
               $today = new DateTime('today');
               $diff = $postingDate->diff($today);

               $age = $diff->format('%a');
               $quantity = $rowb->CategoryCode;
             }
           }

           $vacid = $rowa['vacid'];
           
           $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
           $query1=$dbh->prepare($sql1);
           $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
           $query1->execute();
           $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
         
           if($query1->rowCount() > 0)
           {  
             foreach ($resultc as $rowc) {
               $disease = $rowc['disease'];
               $dose = $rowc['dose'];
               $method = $rowc['method'];
               $vaccination = $rowc['vaccination'];
             }
           }
           $cells = array($cnt, $fowl, $age, $quantity, $disease, $vaccination, $dose, $method);
           $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);
           $pdf->Ln();
           $pdf->SetFont('Arial','',10);
           foreach ($cells as $key => $cell) {
             $pdf->Cell($cellWidths[$key], 10, $cell, 1, 0, 'C');
            }
           $cnt=$cnt+1;
          }
      }

      // foreach($result as $row) {
      // $pdf->Ln();
      // foreach($row as $column)
      // $pdf->Cell(20,12,$column,1);
      // }

      $pdf->Output('D', $filename);
    }

    if($did=='layer_mortality') {
      $today = date("Y-m-d");
      $filename = "Layer_Mortality(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $fname=$_SESSION['fname'];
      $cate='Layer';
      $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate  ORDER BY id DESC";

      $query = $dbh -> prepare($sql);
      $query->bindParam(':fname',$fname,PDO::PARAM_STR);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10), "Age(weeks)",str_pad("Record Date",10), "Quantity", "Cause of Death"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        {
            fputcsv($fh, array(
            str_pad($row->CategoryFowlRun, 10),
            str_pad((intval($row->age/7)+1),10),
            str_pad($row->CategoryDate,10),
            str_pad($row->CategoryCount, 10),
            str_pad($row->CategoryDescription, 10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='layer_feed'){
      $today = date("Y-m-d");
      $filename = "Layer_Feed(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Layer';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10),str_pad("Age(Weeks)",10), "Quantity", str_pad("Record Date",10), "Total Feed"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            if($row->count=='0') $row->count='-';
            if($row->total=='0') $row->total='-';
            fputcsv($fh, array(
            str_pad($row->fowlRun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count,10),
            str_pad(date("Y-m-d", strtotime($row->posting)),10),
            str_pad($row->total,10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='layer_weight'){
      $today = date("Y-m-d");
      $filename = "Layer_Weight(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Layer';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblweight where tblweight.category=:cate and tblweight.fname=:fname ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10),str_pad("Age(Weeks)",10),"Quantity", str_pad("Record Date",10), "Weight"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            fputcsv($fh, array(
            str_pad($row->fowlrun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count, 10),
            str_pad(date("Y-m-d", strtotime($row->date)),10),
            str_pad($row->weight,10),
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='eggs'){
      $today = date("Y-m-d");
      $filename = "Eggs(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblproducts where tblproducts.fname=:fname ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array(str_pad("Fowl Run",10),str_pad("Age(Weeks)",10),"Layers", str_pad("Record Date",10), "Eggs"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            fputcsv($fh, array(
            str_pad($row->Layer_runName, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->quantity, 10),
            str_pad(date("Y-m-d", strtotime($row->Eggdate)),10),
            str_pad($row->Eggcount,10),
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='layer_vaccination'){
      $today = date("Y-m-d");
      $filename = "Layer_Vaccination(".$today.").pdf";		 
      

      $display_heading = array('No', 'Fowl Run', 'Age', 'Quantity', 'Disease', 'Vaccination', 'Dose','Method');
      $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);

      $pdf = new PDF();
      $pdf->setHeaderText($today);
      //header
      $pdf->AddPage();
      //foter page
      $pdf->AliasNbPages();
      $pdf->SetFont('Arial','B',12);
      // Set the header
      foreach ($display_heading as $key => $heading) {
        $pdf->Cell($cellWidths[$key], 8, $heading, 1, 0, 'C');
      }


      $fname=$_SESSION['fname'];
      $cate='Layer';
      
      $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
      
      $currentfowl = $row->CategoryFowlRun;
      $query = $dbh -> prepare($sql);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query->execute();
      $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
      $cnt=1;
      if($query->rowCount() > 0)
      {  
         foreach($resulta as $rowa)
         { 
           $fowl = $rowa['fowlrun'];
           $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
           $query = $dbh -> prepare($sql);
           $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);

           $query->execute();
           $resultb=$query->fetchAll(PDO::FETCH_OBJ);
           if($query->rowCount() > 0)
           {
             foreach($resultb as $rowb){
               $c_date = $rowb->PostingDate;
               $postingDate = new DateTime($c_date);
               $today = new DateTime('today');
               $diff = $postingDate->diff($today);

               $age = $diff->format('%a');
               $quantity = $rowb->CategoryCode;
             }
           }

           $vacid = $rowa['vacid'];
           
           $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
           $query1=$dbh->prepare($sql1);
           $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
           $query1->execute();
           $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
         
           if($query1->rowCount() > 0)
           {  
             foreach ($resultc as $rowc) {
               $disease = $rowc['disease'];
               $dose = $rowc['dose'];
               $method = $rowc['method'];
               $vaccination = $rowc['vaccination'];
             }
           }
           $cells = array($cnt, $fowl, $age, $quantity, $disease, $vaccination, $dose, $method);
           $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);
           $pdf->Ln();
           $pdf->SetFont('Arial','',10);
           foreach ($cells as $key => $cell) {
             $pdf->Cell($cellWidths[$key], 8, $cell, 1, 0, 'C');
            }
           $cnt=$cnt+1;
          }
      }

      // foreach($result as $row) {
      // $pdf->Ln();
      // foreach($row as $column)
      // $pdf->Cell(20,12,$column,1);
      // }

      $pdf->Output('D', $filename);
    }

    if($did=='freerange_mortality') {
      $today = date("Y-m-d");
      $filename = "FreeRange_Mortality(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $fname=$_SESSION['fname'];
      $cate='Free_Range';
      $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate  ORDER BY id DESC";

      $query = $dbh -> prepare($sql);
      $query->bindParam(':fname',$fname,PDO::PARAM_STR);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array("Fowl Run", "Age(weeks)",str_pad("Record Date",10), "Quantity", "Cause of Death"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        {
            fputcsv($fh, array(
            str_pad($row->CategoryFowlRun, 10),
            str_pad((intval($row->age/7)+1),10),
            str_pad($row->CategoryDate,10),
            str_pad($row->CategoryCount, 10),
            str_pad($row->CategoryDescription, 10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='freerange_feed'){
      $today = date("Y-m-d");
      $filename = "FreeRange_Feed(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Free_Range';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array("Fowl Run",str_pad("Age(Weeks)",10), "Quantity", str_pad("Record Date",10), "Total Feed"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            if($row->count=='0') $row->count='-';
            if($row->total=='0') $row->total='-';
            fputcsv($fh, array(
            str_pad($row->fowlRun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count,10),
            str_pad(date("Y-m-d", strtotime($row->posting)),10),
            str_pad($row->total,10)
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='freerange_weight'){
      $today = date("Y-m-d");
      $filename = "FreeRange_Weight(".$today.").csv";		 
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Expires: 0");
      $fh = fopen( 'php://output', 'w' );

      $cate='Free_Range';
      $fname=$_SESSION['fname'];
      $sql="SELECT * from tblweight where tblweight.category=:cate and tblweight.fname=:fname ORDER BY id DESC";
      
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      fputcsv($fh, array("Fowl Run",str_pad("Age(Weeks)",10),"Quantity", str_pad("Record Date",10), "Weight"));
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        { 
            fputcsv($fh, array(
            str_pad($row->fowlrun, 10),
            str_pad(intval($row->age/7)+1, 10),
            str_pad($row->count, 10),
            str_pad(date("Y-m-d", strtotime($row->date)),10),
            str_pad($row->weight,10),
        )); 
        }
        $cnt=$cnt+1;
      }
      fclose($fh);
      exit();   
    }
    if($did=='freerange_vaccination'){
      $today = date("Y-m-d");
      $filename = "FreeRange_Vaccination(".$today.").pdf";		 
      

      $display_heading = array('No', 'Fowl Run', 'Age', 'Quantity', 'Disease', 'Vaccination', 'Dose','Method');
      $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);

      $pdf = new PDF();
      $pdf->setHeaderText($today);
      //header
      $pdf->AddPage();
      //foter page
      $pdf->AliasNbPages();
      $pdf->SetFont('Arial','B',12);
      // Set the header
      foreach ($display_heading as $key => $heading) {
        $pdf->Cell($cellWidths[$key], 8, $heading, 1, 0, 'C');
      }


      $fname=$_SESSION['fname'];
      $cate='Free_Range';
      
      $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
      
      $currentfowl = $row->CategoryFowlRun;
      $query = $dbh -> prepare($sql);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
      $query->execute();
      $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
      $cnt=1;
      if($query->rowCount() > 0)
      {  
         foreach($resulta as $rowa)
         { 
           $fowl = $rowa['fowlrun'];
           $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
           $query = $dbh -> prepare($sql);
           $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);

           $query->execute();
           $resultb=$query->fetchAll(PDO::FETCH_OBJ);
           if($query->rowCount() > 0)
           {
             foreach($resultb as $rowb){
               $c_date = $rowb->PostingDate;
               $postingDate = new DateTime($c_date);
               $today = new DateTime('today');
               $diff = $postingDate->diff($today);

               $age = $diff->format('%a');
               $quantity = $rowb->CategoryCode;
             }
           }

           $vacid = $rowa['vacid'];
           
           $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
           $query1=$dbh->prepare($sql1);
           $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
           $query1->execute();
           $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
         
           if($query1->rowCount() > 0)
           {  
             foreach ($resultc as $rowc) {
               $disease = $rowc['disease'];
               $dose = $rowc['dose'];
               $method = $rowc['method'];
               $vaccination = $rowc['vaccination'];
             }
           }
           $cells = array($cnt, $fowl, $age, $quantity, $disease, $vaccination, $dose, $method);
           $cellWidths = array(10, 30, 10, 20, 25, 45, 20, 30);
           $pdf->Ln();
           $pdf->SetFont('Arial','',10);
           foreach ($cells as $key => $cell) {
             $pdf->Cell($cellWidths[$key], 8, $cell, 1, 0, 'C');
            }
           $cnt=$cnt+1;
          }
      }

      $pdf->Output('D', $filename);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">

    <?php @include("includes/header.php");?>

    <div class="container-fluid page-body-wrapper">

      <?php @include("includes/sidebar.php");?>

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Broiler -->
          <div class="row">
            <div class="modal fade" id="viewLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Mortality Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">No</th>
                                  <th>Update Date</th>
                                  <th class="text-center">Category Name</th>
                                  <th class="text-center">FowlRun Name</th>
                                  <th class="text-center">Quantity</th>
                                  <th class="text-center">Cause of Death</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $fname=$_SESSION['fname'];
                                $cate='Broiler';

                                $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate ORDER BY id DESC";

                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':fname',$fname,PDO::PARAM_STR);
                                $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                      <td class="text-cente"><?php  echo htmlentities($row->CategoryDate);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryName);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryFowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryCount);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryDescription);?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                               
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="feedLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Feed Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">FowlRun</th>
                                  <th class="text-center">Chicken Count</th>
                                  <th class="text-center">Total Feed</th>
                                  <th class="text-center">Feed Date</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $cate='Broiler';
                                $fname=$_SESSION['fname'];
                                $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
                                
                                $query = $dbh -> prepare($sql);
                                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                                $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php  echo htmlentities($row->fowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                      <td class="text-center"><?php echo number_format($row->total, 2, '.', '');?></td>
                                      <td class="text-center"><?php  echo htmlentities(date("Y-m-d", strtotime($row->posting)));?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="weightLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Weight Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Posting Date</th>
                                <th class="text-center">Weight</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $cate='Broiler';
                              $fname=$_SESSION['fname'];
                              $sql="SELECT * from tblweight where tblweight.fname=:fname and tblweight.category=:cate  ORDER BY id DESC";
                              $query = $dbh -> prepare($sql);
                              $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                              $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                              $query->execute();
                              $results=$query->fetchAll(PDO::FETCH_OBJ);
                              $cnt=1;
                              if($query->rowCount() > 0)
                              {
                                foreach($results as $row)
                                { 
                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->fowlrun);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->date);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->weight);?></td>                                  </tr>
                                  <?php 
                                  $cnt=$cnt+1;
                                }
                              } ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="vacLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Vaccination Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Disease</th>
                                <th class="text-center">Vaccination</th>
                                <th class="text-center">Dose</th>
                                <th class="text-center">Method</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                               $fname=$_SESSION['fname'];
                               $cate='Broiler';
                               
                               $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
                               
                               $currentfowl = $row->CategoryFowlRun;
                               $query = $dbh -> prepare($sql);
                               $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                               $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                               $query->execute();
                               $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
                               $cnt=1;
                               if($query->rowCount() > 0)
                               {  
                                  foreach($resulta as $rowa)
                                  { 
                                    $fowl = $rowa['fowlrun'];
                                    $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
                                    $query = $dbh -> prepare($sql);
                                    $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);
              
                                    $query->execute();
                                    $resultb=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0)
                                    {
                                      foreach($resultb as $rowb){
                                        $c_date = $rowb->PostingDate;
                                        $postingDate = new DateTime($c_date);
                                        $today = new DateTime('today');
                                        $diff = $postingDate->diff($today);

                                        $age = $diff->format('%a');
                                        $quantity = $rowb->CategoryCode;
                                      }
                                    }

                                    $vacid = $rowa['vacid'];
                                    
                                    $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
                                    $query1=$dbh->prepare($sql1);
                                    $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                    $query1->execute();
                                    $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    if($query1->rowCount() > 0)
                                    {  
                                      foreach ($resultc as $rowc) {
                                        $disease = $rowc['disease'];
                                        $dose = $rowc['dose'];
                                        $method = $rowc['method'];
                                        $vaccination = $rowc['vaccination'];
                                      }
                                    }

                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($fowl);?></td>
                                    <td class="text-center"><?php  echo htmlentities($age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($quantity);?></td>
                                    <td class="text-center"><?php  echo htmlentities($disease);?></td>
                                    <td class="text-center"><?php  echo htmlentities($vaccination);?></td>
                                    <td class="text-center"><?php  echo htmlentities($dose);?></td> 
                                    <td class="text-center"><?php  echo htmlentities($method);?></td>   
                                  </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                    }
                                  }
                              ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <!-- Layer -->
          <div class="row">
            <div class="modal fade" id="layer_viewLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Mortality Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">No</th>
                                  <th>Update Date</th>
                                  <th class="text-center">Category Name</th>
                                  <th class="text-center">FowlRun Name</th>
                                  <th class="text-center">Quantity</th>
                                  <th class="text-center">Cause of Death</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $fname=$_SESSION['fname'];
                                $cate='Layer';

                                $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate ORDER BY id DESC";

                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':fname',$fname,PDO::PARAM_STR);
                                $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                      <td class="text-cente"><?php  echo htmlentities($row->CategoryDate);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryName);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryFowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryCount);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryDescription);?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                               
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="layer_feedLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Feed Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">FowlRun</th>
                                  <th class="text-center">Chicken Count</th>
                                  <th class="text-center">Total Feed</th>
                                  <th class="text-center">Feed Date</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $cate='Layer';
                                $fname=$_SESSION['fname'];
                                $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
                                
                                $query = $dbh -> prepare($sql);
                                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                                $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php  echo htmlentities($row->fowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                      <td class="text-center"><?php echo number_format($row->total, 2, '.', '');?></td>
                                      <td class="text-center"><?php  echo htmlentities(date("Y-m-d", strtotime($row->posting)));?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="layer_weightLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Weight Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Posting Date</th>
                                <th class="text-center">Weight</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $cate='Layer';
                              $fname=$_SESSION['fname'];
                              $sql="SELECT * from tblweight where tblweight.fname=:fname and tblweight.category=:cate  ORDER BY id DESC";
                              $query = $dbh -> prepare($sql);
                              $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                              $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                              $query->execute();
                              $results=$query->fetchAll(PDO::FETCH_OBJ);
                              $cnt=1;
                              if($query->rowCount() > 0)
                              {
                                foreach($results as $row)
                                { 
                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->fowlrun);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->date);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->weight);?></td>                                  </tr>
                                  <?php 
                                  $cnt=$cnt+1;
                                }
                              } ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="eggLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Egg Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Layers</th>
                                <th class="text-center">Posting Date</th>
                                <th class="text-center">Eggs</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $fname=$_SESSION['fname'];
                              $sql="SELECT * from tblproducts where tblproducts.fname=:fname  ORDER BY id DESC";
                              $query = $dbh -> prepare($sql);
                              $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                              $query->execute();
                              $results=$query->fetchAll(PDO::FETCH_OBJ);
                              $cnt=1;
                              if($query->rowCount() > 0)
                              {
                                foreach($results as $row)
                                { 
                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->Layer_runName);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->quantity);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->Eggdate);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->Eggcount);?></td>                                  </tr>
                                  <?php 
                                  $cnt=$cnt+1;
                                }
                              } ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="layer_vacLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Vaccination Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Disease</th>
                                <th class="text-center">Vaccination</th>
                                <th class="text-center">Dose</th>
                                <th class="text-center">Method</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                               $fname=$_SESSION['fname'];
                               $cate='Layer';
                               
                               $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
                               
                               $currentfowl = $row->CategoryFowlRun;
                               $query = $dbh -> prepare($sql);
                               $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                               $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                               $query->execute();
                               $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
                               $cnt=1;
                               if($query->rowCount() > 0)
                               {  
                                  foreach($resulta as $rowa)
                                  { 
                                    $fowl = $rowa['fowlrun'];
                                    $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
                                    $query = $dbh -> prepare($sql);
                                    $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);
              
                                    $query->execute();
                                    $resultb=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0)
                                    {
                                      foreach($resultb as $rowb){
                                        $c_date = $rowb->PostingDate;
                                        $postingDate = new DateTime($c_date);
                                        $today = new DateTime('today');
                                        $diff = $postingDate->diff($today);

                                        $age = $diff->format('%a');
                                        $quantity = $rowb->CategoryCode;
                                      }
                                    }

                                    $vacid = $rowa['vacid'];
                                    
                                    $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
                                    $query1=$dbh->prepare($sql1);
                                    $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                    $query1->execute();
                                    $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    if($query1->rowCount() > 0)
                                    {  
                                      foreach ($resultc as $rowc) {
                                        $disease = $rowc['disease'];
                                        $dose = $rowc['dose'];
                                        $method = $rowc['method'];
                                        $vaccination = $rowc['vaccination'];
                                      }
                                    }

                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($fowl);?></td>
                                    <td class="text-center"><?php  echo htmlentities($age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($quantity);?></td>
                                    <td class="text-center"><?php  echo htmlentities($disease);?></td>
                                    <td class="text-center"><?php  echo htmlentities($vaccination);?></td>
                                    <td class="text-center"><?php  echo htmlentities($dose);?></td> 
                                    <td class="text-center"><?php  echo htmlentities($method);?></td>   
                                  </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                    }
                                  }
                              ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>

          <!-- Free Range -->
          <div class="row">
            <div class="modal fade" id="freerange_viewLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Mortality Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">No</th>
                                  <th>Update Date</th>
                                  <th class="text-center">Category Name</th>
                                  <th class="text-center">FowlRun Name</th>
                                  <th class="text-center">Quantity</th>
                                  <th class="text-center">Cause of Death</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $fname=$_SESSION['fname'];
                                $cate='Free_Range';

                                $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate ORDER BY id DESC";

                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':fname',$fname,PDO::PARAM_STR);
                                $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                      <td class="text-cente"><?php  echo htmlentities($row->CategoryDate);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryName);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryFowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryCount);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->CategoryDescription);?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                                
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="freerange_feedLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Feed Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                              <thead>
                                <tr>
                                  <th class="text-center">FowlRun</th>
                                  <th class="text-center">Chicken Count</th>
                                  <th class="text-center">Total Feed</th>
                                  <th class="text-center">Feed Date</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $cate='Free_Range';
                                $fname=$_SESSION['fname'];
                                $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
                                
                                $query = $dbh -> prepare($sql);
                                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                                $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php  echo htmlentities($row->fowlRun);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                      <td class="text-center"><?php echo number_format($row->total, 2, '.', '');?></td>
                                      <td class="text-center"><?php  echo htmlentities(date("Y-m-d", strtotime($row->posting)));?></td>
                                    </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                  }
                                } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="freerange_weightLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Weight Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Posting Date</th>
                                <th class="text-center">Weight</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $cate='Free_Range';
                              $fname=$_SESSION['fname'];
                              $sql="SELECT * from tblweight where tblweight.fname=:fname and tblweight.category=:cate  ORDER BY id DESC";
                              $query = $dbh -> prepare($sql);
                              $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                              $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                              $query->execute();
                              $results=$query->fetchAll(PDO::FETCH_OBJ);
                              $cnt=1;
                              if($query->rowCount() > 0)
                              {
                                foreach($results as $row)
                                { 
                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->fowlrun);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->date);?></td>
                                    <td class="text-center"><?php  echo htmlentities($row->weight);?></td>                                  </tr>
                                  <?php 
                                  $cnt=$cnt+1;
                                }
                              } ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="freerange_vacLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Vaccination Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Disease</th>
                                <th class="text-center">Vaccination</th>
                                <th class="text-center">Dose</th>
                                <th class="text-center">Method</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                               $fname=$_SESSION['fname'];
                               $cate='Free_Range';
                               
                               $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
                               
                               $currentfowl = $row->CategoryFowlRun;
                               $query = $dbh -> prepare($sql);
                               $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                               $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                               $query->execute();
                               $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
                               $cnt=1;
                               if($query->rowCount() > 0)
                               {  
                                  foreach($resulta as $rowa)
                                  { 
                                    $fowl = $rowa['fowlrun'];
                                    $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
                                    $query = $dbh -> prepare($sql);
                                    $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);
              
                                    $query->execute();
                                    $resultb=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0)
                                    {
                                      foreach($resultb as $rowb){
                                        $c_date = $rowb->PostingDate;
                                        $postingDate = new DateTime($c_date);
                                        $today = new DateTime('today');
                                        $diff = $postingDate->diff($today);

                                        $age = $diff->format('%a');
                                        $quantity = $rowb->CategoryCode;
                                      }
                                    }

                                    $vacid = $rowa['vacid'];
                                    
                                    $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
                                    $query1=$dbh->prepare($sql1);
                                    $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                    $query1->execute();
                                    $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    if($query1->rowCount() > 0)
                                    {  
                                      foreach ($resultc as $rowc) {
                                        $disease = $rowc['disease'];
                                        $dose = $rowc['dose'];
                                        $method = $rowc['method'];
                                        $vaccination = $rowc['vaccination'];
                                      }
                                    }

                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($fowl);?></td>
                                    <td class="text-center"><?php  echo htmlentities($age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($quantity);?></td>
                                    <td class="text-center"><?php  echo htmlentities($disease);?></td>
                                    <td class="text-center"><?php  echo htmlentities($vaccination);?></td>
                                    <td class="text-center"><?php  echo htmlentities($dose);?></td> 
                                    <td class="text-center"><?php  echo htmlentities($method);?></td>   
                                  </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                    }
                                  }
                              ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>
          <div class="row">
            <div class="modal fade" id="freerange_vacLog">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Vaccination Log</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead>
                              <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Fowl Run</th>
                                <th class="text-center">Age</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Disease</th>
                                <th class="text-center">Vaccination</th>
                                <th class="text-center">Dose</th>
                                <th class="text-center">Method</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                               $fname=$_SESSION['fname'];
                               $cate='Free_Range';
                               
                               $sql="SELECT * from tblvaccination_log where tblvaccination_log.category=:cate and tblvaccination_log.fname=:fname";
                               
                               $currentfowl = $row->CategoryFowlRun;
                               $query = $dbh -> prepare($sql);
                               $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                               $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                               $query->execute();
                               $resulta = $query->fetchAll(PDO::FETCH_ASSOC);
                               $cnt=1;
                               if($query->rowCount() > 0)
                               {  
                                  foreach($resulta as $rowa)
                                  { 
                                    $fowl = $rowa['fowlrun'];
                                    $sql="SELECT * from tblcategory where tblcategory.CategoryFowlRun=:fowl";
                                    $query = $dbh -> prepare($sql);
                                    $query-> bindParam(':fowl', $fowl, PDO::PARAM_STR);
              
                                    $query->execute();
                                    $resultb=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0)
                                    {
                                      foreach($resultb as $rowb){
                                        $c_date = $rowb->PostingDate;
                                        $postingDate = new DateTime($c_date);
                                        $today = new DateTime('today');
                                        $diff = $postingDate->diff($today);

                                        $age = $diff->format('%a');
                                        $quantity = $rowb->CategoryCode;
                                      }
                                    }

                                    $vacid = $rowa['vacid'];
                                    
                                    $sql1="SELECT * from tblvaccination where tblvaccination.id=:vacid";
                                    $query1=$dbh->prepare($sql1);
                                    $query1->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                    $query1->execute();
                                    $resultc = $query1->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    if($query1->rowCount() > 0)
                                    {  
                                      foreach ($resultc as $rowc) {
                                        $disease = $rowc['disease'];
                                        $dose = $rowc['dose'];
                                        $method = $rowc['method'];
                                        $vaccination = $rowc['vaccination'];
                                      }
                                    }

                                  ?>
                                  <tr>
                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                    <td class="text-center"><?php  echo htmlentities($fowl);?></td>
                                    <td class="text-center"><?php  echo htmlentities($age);?></td>
                                    <td class="text-center"><?php  echo htmlentities($quantity);?></td>
                                    <td class="text-center"><?php  echo htmlentities($disease);?></td>
                                    <td class="text-center"><?php  echo htmlentities($vaccination);?></td>
                                    <td class="text-center"><?php  echo htmlentities($dose);?></td> 
                                    <td class="text-center"><?php  echo htmlentities($method);?></td>   
                                  </tr>
                                    <?php 
                                    $cnt=$cnt+1;
                                    }
                                  }
                              ?>
                            </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
          </div>


          <div class="text-left ml-4"><h2 style="color:#FF1493">Broiler</h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">
                <div class="card-body" style="background-color:#009999; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Weights<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#weightLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=broiler_weight" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #33cccc; color:antiquewhite" >
                  <h5 class="card-title" style="color:white">Feed Usage<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#feedLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=broiler_feed" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #0099ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Mortality<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=broiler_mortality" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #3366ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Vaccination<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#vacLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                     <a href="report.php?download=broiler_vaccination" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="text-left ml-4"><h2 style="color:#FF1493">Layer</h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-2">
              <div class="card info-card sales-card" style="min-height: 160px;">
                <div class="card-body" style="background-color: #339966; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Weights<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#layer_weightLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=layer_weight" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-2">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #00cc66; color:antiquewhite" >
                  <h5 class="card-title" style="color:white">Feed Usage<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#layer_feedLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=layer_feed" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-2">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #33cc33; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Mortality<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#layer_viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                  <a href="report.php?download=layer_mortality" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>

                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-2">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #99cc00; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Eggs<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#eggLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                  <a href="report.php?download=eggs" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-2">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #669900; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Vaccination<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#layer_vacLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                     <a href="report.php?download=layer_vaccination" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="text-left ml-4"><h2 style="color:#FF1493">Free Range</h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">
                <div class="card-body" style="background-color:#3366ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Weights<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#freerange_weightLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=freerange_weight" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #0099cc; color:antiquewhite" >
                  <h5 class="card-title" style="color:white">Feed Usage<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#freerange_feedLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <a href="report.php?download=freerange_feed" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #0066cc; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Mortality<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#freerange_viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                  <a href="report.php?download=freerange_mortality" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #3333cc; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Vaccination<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#freerange_vacLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                     <a href="report.php?download=freerange_vaccination" title="Download"><i class="mdi mdi-download mdi-36px float-center" style="color:white;"></i></a>
                  </div>
                </div>

              </div>
            </div>
          </div>
          
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php @include("includes/footer.php");?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <?php @include("includes/foot.php");?>
  <script >
    $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
      {
        label               : 'Digital Goods',
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : false,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [28, 48, 40, 19, 86, 27, 90]
      },
      {
        label               : 'Electronics',
        backgroundColor     : 'rgba(200, 150, 30, 1)',
        borderColor         : 'rgba(210, 214, 222, 1)',
        pointRadius         : false,
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : [66, 59, 80, 81, 56, 55, 41]
      },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas, { 
      type: 'bar',
      data: areaChartData, 
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    var lineChartData = jQuery.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, { 
      type: 'line',
      data: lineChartData, 
      options: lineChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    
    var donutData        = {
      labels: [
      'Chrome', 
      'IE',
      'FireFox', 
      'Safari', 
      'Opera', 
      'Navigator', 
      ],
      datasets: [
      {
        data: [700,500,400,600,300,100],
        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
      }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions      
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
// $(document).ready(function () {
//   showGraph();
// });


// function showGraph()
// {
//   {
//     $.post("data.php",
//       function (data)
//       {
//         console.log(data);
//         var name = [];
//         var marks = [];

//         for (var i in data) {
//           name.push(data[i].ServiceName);
//           marks.push(data[i].population);
//         }
//         var barChartOptions = {
//           responsive              : true,
//           maintainAspectRatio     : false,
//           datasetFill             : false,
//           scales:{
//             yAxes:[{
//                 ticks:{
//                     beginAtZero: true
//                 }
//             }]
//           }
//         }

//           var chartdata = {
//             labels: name,
//             datasets: [
//             {
//               label: 'Student Marks',
//               backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
//               borderColor: '#46d5f1',
//               hoverBackgroundColor: '#CCCCCC',
//               hoverBorderColor: '#666666',
//               data: marks
//             }
//             ]
//           };


//           var graphTarget = $("#graphCanvas");

//           var barGraph = new Chart(graphTarget, {
//             type: 'bar',
//             data: chartdata,
//             options: barChartOptions
//           });
//         });
//   }
// }


$(document).ready(function(){
  $.ajax({
    url: "data.php",
    method: "GET",
    success: function(data){
      console.log(data);
      var name = [];
      var marks = [];

      for (var i in data){
        name.push(data[i].Sector);

        marks.push(data[i].total);
      }
      var chartdata = {
        labels: name,
        datasets: [{
          label: 'student marks',
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
          borderColor: 'rgba(134, 159, 152, 1)',
          hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
          hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
          data: marks

        }]
      };
      var graphTarget = $("#graphCanvas");
      var barGraph = new Chart(graphTarget, {
        type: 'bar',
        data: chartdata,
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });
    },
    error: function(data) {
      console.log(data);
    }

  });
});

$(document).ready(function () {
  showGraph2();
});
function showGraph2()
{
  {
    $.post("data.php",
      function (data)
      {
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data) {
          name.push(data[i].Sector);
          marks.push(data[i].total);
        }

        var chartdata = {
          labels: name,
          datasets: [
          {
            label: 'Student Marks',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            // borderColor: '#46d5f1',
            hoverBackgroundColor: '#CCCCCC',
            hoverBorderColor: '#666666',
            data: marks
          }
          ]
        };

        var graphTarget = $("#graphCanvas2");

        var pieChart = new Chart(graphTarget, {
          type: 'pie',
          data: chartdata
        });
      });
  }
}

</script>

<script >
  $(document).ready(function(){
    $.ajax({
      url: "data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data){
          name.push(data[i].Sector);

          marks.push(data[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'student marks',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });



  $(document).ready(function(){
    $.ajax({
      url: "data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data){
          name.push(data[i].Sector);

          marks.push(data[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of Bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas3");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });





  $(document).ready(function(){
    $.ajax({
      url: "data1.php",
      method: "GET",
      success: function(data1){
        console.log(data1);
        var name = [];
        var marks = [];

        for (var i in data1){
          name.push(data1[i].Status);

          marks.push(data1[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas4");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });




  $(document).ready(function(){
    $.ajax({
      url: "data2.php",
      method: "GET",
      success: function(data2){
        console.log(data2);
        var name = [];
        var marks = [];

        for (var i in data2){
          name.push(data2[i].Source);

          marks.push(data2[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas5");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });



  $(document).ready(function(){
    $.ajax({
      url: "data3.php",
      method: "GET",
      success: function(data3){
        console.log(data3);
        var name = [];
        var marks = [];

        for (var i in data3){
          name.push(data3[i].Newspaper);

          marks.push(data3[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of Bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas6");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });

</script>
</body>
</html>


