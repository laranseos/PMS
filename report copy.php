;<?php 
include('includes/checklogin.php');
check_login();

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

      $fname=$_SESSION['fname'];
      $cate='Broiler';
      $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname and tblcategory_log.CategoryName=:cate  ORDER BY id DESC";

      $query = $dbh -> prepare($sql);
      $query->bindParam(':fname',$fname,PDO::PARAM_STR);
      $query->bindParam(':cate',$cate,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      $fh = fopen( 'php://output', 'w' );
      fputcsv($fh, array("Fowl Run", "Age(weeks)",str_pad("Record Date",10), "Quantity", "Cause of Death"));
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
      fputcsv($fh, array("Fowl Run",str_pad("Age(Weeks)",10),"Layers", str_pad("Record Date",10), "Eggs"));
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
                  <!-- <div class="d-flex align-items-center">
                    <div class="ps-3">
                     <?php
                      $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory");
                      $row=mysqli_fetch_array($query);
                      $total = $row['total'];
                      
                      $query1=mysqli_query($con,"select sum(tblcategory_log.CategoryCount) as total from tblcategory_log");
                      $row1=mysqli_fetch_array($query1);
                      $deaths = $row1['total'];
                      
                      $m_rate=($deaths*100)/($deaths+$total);
                      ?>
                      <h2><?php echo number_format($m_rate, 2, '.', '');?>%</h2>
                    </div>
                  </div> -->
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #3366ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Vaccination Takens<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i><i class="mdi mdi-download mdi-24px float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                          $sql1="SELECT * from tblvaccination_log";
                          $query1=$dbh->prepare($sql1);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          $cnt = $query1->rowCount();
                      ?>
                      <h2><?php echo $cnt?></h2>
                    </div>
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
                  <h5 class="card-title" style="color:white;">Vaccination<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i><i class="mdi mdi-download mdi-24px float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                          $sql1="SELECT * from tblvaccination_log";
                          $query1=$dbh->prepare($sql1);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          $cnt = $query1->rowCount();
                      ?>
                      <h2><?php echo $cnt?></h2>
                    </div>
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
                  <h5 class="card-title" style="color:white;">Vaccination Takens<i class="mdi mdi-dots-vertical-circle-outline mdi-24px ml-4 float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i><i class="mdi mdi-download mdi-24px float-right" style="color:white;" data-toggle="modal" data-target="#viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                          $sql1="SELECT * from tblvaccination_log";
                          $query1=$dbh->prepare($sql1);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          $cnt = $query1->rowCount();
                      ?>
                      <h2><?php echo $cnt?></h2>
                    </div>
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
</body>
</html>


