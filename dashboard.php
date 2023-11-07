<?php 
include('includes/checklogin.php');
check_login();
error_reporting(0);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
          
          <div class="row">
            <div class="modal fade" id="farm_view">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" style="color: #0DCEF0;">Farms Info</h2>
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
                                  <th>Farm Name</th>
                                  <th class="text-center">Users</th>
                                  <th class="text-center">Broilers</th>
                                  <th class="text-center">Layers</th>
                                  <th class="text-center">Free Ranges</th>
                                </tr>
                              </thead>
                              <tbody>
                                  
                                <?php
                                $sql="SELECT tbladmin.FarmName as fname, COUNT(*) as count from tbladmin GROUP BY tbladmin.FarmName";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                
                                if($query->rowCount() > 0)
                                {
                                  foreach($results as $row)
                                  { 
                                    $broilers = $layers = $freeranges =0;
                                    $fname = $row->fname;
                                    if($fname==" ") continue;

                                    $query2=mysqli_query($con,"select sum(tblcategory.CategoryCode) as broilers from tblcategory where tblcategory.fname='$fname' and tblcategory.CategoryName='Broiler' ");
                                    $cnt_broiler=mysqli_fetch_array($query2);
                                    if($cnt_broiler['broilers']!='') $broilers = $cnt_broiler['broilers'];

                                    $query3=mysqli_query($con,"select sum(tblcategory.CategoryCode) as layers from tblcategory where tblcategory.fname='$fname' and tblcategory.CategoryName='Layer' ");
                                    $cnt_layer=mysqli_fetch_array($query3);
                                    if($cnt_layer['layers']!='') $layers = $cnt_layer['layers'];

                                    $query4=mysqli_query($con,"select sum(tblcategory.hews)+sum(tblcategory.cocks) as freeranges from tblcategory where tblcategory.fname='$fname' and tblcategory.CategoryName='Free_Range' ");
                                    $cnt_freerange=mysqli_fetch_array($query4);
                                    if($cnt_freerange['freeranges']!='') $freeranges = $cnt_freerange['freeranges'];
                                    
                                    ?>
                                    <tr>
                                      <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                      <td class="text-cente"><?php  echo htmlentities($row->fname);?></td>
                                      <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                                      <td class="text-center"><?php  echo htmlentities($broilers);?></td>
                                      <td class="text-center"><?php  echo htmlentities($layers);?></td>
                                      <td class="text-center"><?php  echo htmlentities($freeranges);?></td>
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
                </div>
            </div>
          </div>
          <div class="row" style="margin-bottom: 50px;">
            <div class="col-xxl-4 col-md-4">
              <a href="userregister.php" style="text-decoration:none;">
                <div class="card info-card card1" style="min-height: 150px;">

                  <div class="card-body" style="background-color: #0DCEF0; color:antiquewhite">
                    <h5 class="card-title">Users</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php 
                        $sql ="SELECT ID from tbladmin where Status='1'";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $totalunreadquery=$query->rowCount();
                        ?>
                        
                        <h2><?php echo htmlentities($totalunreadquery);?></h2>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-4">
              <a href="#" style="text-decoration:none; " data-toggle="modal" data-target="#farm_view">
                <div class="card info-card card1" style="min-height: 150px;">
                  <div class="card-body" style="background-color: #00FA9A; color:antiquewhite">
                    <h5 class="card-title">Farms</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php
                        $sql=mysqli_query($con,"SELECT DISTINCT FarmName FROM tbladmin");
                        $farms=mysqli_num_rows($sql) - 1;
                        // $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory");
                        // $row=mysqli_fetch_array($query);
                        ?>
                        <h2><?php echo $farms?></h2>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card card1" style="min-height: 150px; cursor : default">

                <div class="card-body" style="background-color: #0099ff; color:antiquewhite">
                  <h5 class="card-title">Fowl Runs</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $sql=mysqli_query($con,"select id from tblcategory");
                      $listedproduct=mysqli_num_rows($sql);
                      ?>
                      <h2><?php echo $listedproduct;?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="text-left ml-4"><h2 style="color:#FF1493">Today is <?php echo date('Y-m-d');?></h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card card1" style="min-height: 160px; cursor : default">

                <div class="card-body" style="background-color:#FF1493; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Feed Needed Today</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $sql="SELECT * from tblcategory";
                      
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $cnt=1;
                      $c_feed = 0;
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        { 

                          $c_code = $row->CategoryCode; 
                          if($c_code==0) $c_code = $row->hews + $row->cocks;
                          $c_date = $row->PostingDate;
                          $c_fowl = $row->CategoryName;

                          $postingDate = new DateTime($c_date);
                          $today = new DateTime('today');
                          $diff = $postingDate->diff($today);

                          $fdays = $diff->format('%a');
                          
                          $sql1="SELECT tblfeed.fpd from tblfeed where tblfeed.category=:fowl and tblfeed.start<=:fdays and tblfeed.end>=:fdays";
                          $query1=$dbh->prepare($sql1);
                          $query1->bindParam(':fdays',$fdays,PDO::PARAM_STR);
                          $query1->bindParam(':fowl',$c_fowl,PDO::PARAM_STR);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);

                          if($query1->rowCount() > 0)
                          {  
                            foreach ($results1 as $row1) {
                              $c_feed +=  $row1['fpd']*$c_code;
                            }
                          }
                        }
                      }
                      ?>
                      <h2><?php echo number_format($c_feed, 2, '.', '');?> Kg</h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card card1" style="min-height: 160px; cursor : default">

                <div class="card-body" style="background-color: #ff9900; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Mortality</h5>
                  <hr>
                  <div class="d-flex align-items-center">
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
                      <h2><?php echo number_format($m_rate, 2, '.', '');?> %</h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card info-card card1" style="min-height: 160px; cursor : default">

                <div class="card-body" style="background-color:#3366ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Eggs Collected</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                           $eggs = 0;
                           $date = date('Y-m-d');
                           $query=mysqli_query($con,"select sum(tblproducts.Eggcount) as total from tblproducts where tblproducts.Eggdate = '$date'");
                           $cnt=mysqli_fetch_array($query);
                           if( $cnt['total']!='') $eggs = $cnt['total'];
                      ?>
                      <h2><?php echo $eggs?></h2>
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


