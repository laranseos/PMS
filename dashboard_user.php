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
            <div class="modal fade" id="mortality_view">
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

                                $sql="SELECT * from tblcategory_log where tblcategory_log.fname=:fname ORDER BY id DESC";

                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':fname',$fname,PDO::PARAM_STR);
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

          <div class="row" style="margin-bottom: 50px;">
            <div class="col-xxl-4 col-md-3">
              <a href="category.php?cate_id=Broiler" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color: #0099ff; color:antiquewhite">
                    <h5 class="card-title">Fowl Runs</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php
                        $fname = $_SESSION['fname'];
                        $sql=mysqli_query($con,"select id from tblcategory where fname='$fname'");
                        $listedproduct=mysqli_num_rows($sql);
                        ?>
                        <h2><?php echo $listedproduct;?></h2>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-3">
              <a href="category.php?cate_id=Broiler" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color: #0DCEF0; color:antiquewhite">
                    <h5 class="card-title">Chickens</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php 
                        $query=mysqli_query($con,"SELECT SUM(tblcategory.CategoryCode)+SUM(tblcategory.hews)+SUM(tblcategory.cocks) AS total FROM tblcategory");
                        $row=mysqli_fetch_array($query);
                        ?>
                        
                        <h2><?php echo $row['total'];?></h2>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-3">
              <a href="vaccination.php?cate_id=Broiler" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color: #00FA9A; color:antiquewhite; padding-bottom : 10px; padding-left:20px; padding-right:20px;">
                    <h5 class="card-title">Next Vaccination Date</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php
                        $cateList=['Broiler','Layer', 'Free_Range'];

                        foreach ($cateList as $cate) {
                    
                        $comming = 9999;
                        $fname=$_SESSION['fname'];
                        $sql="SELECT * from tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname order by tblcategory.CategoryFowlRun";
        
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
        
                            $c_date = $row->PostingDate;
                            $postingDate = new DateTime($c_date);
                            $today = new DateTime('today');
                            $diff = $postingDate->diff($today);
        
                            $fdays = $diff->format('%a');
                          
                            $sql1="SELECT * from tblvaccination where tblvaccination.category=:cate order by tblvaccination.age ASC";
                            $query1=$dbh->prepare($sql1);
                            $query1->bindParam(':cate',$cate,PDO::PARAM_STR);
                            $query1->execute();
                            $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                            if($query1->rowCount() > 0)
                            {  
                              $cnt = 0;
                              foreach ($results1 as $row1) {
                                $left = $row1['age'] - $fdays;
                                $vacid = $row1['id'];
                                if($left<-3) continue;
                                if($left>-4) $cnt++;
                                
                                $sql="SELECT * from tblvaccination_log where tblvaccination_log.fowlrun=:currentfowl and tblvaccination_log.vacid=:vacid and tblvaccination_log.fname=:fname";
                                
                                $currentfowl = $row->CategoryFowlRun;
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':currentfowl',$currentfowl,PDO::PARAM_STR);
                                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                                $query->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                $query->execute();
                                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                                
                                
                              
                                if($query->rowCount() > 0)
                                {
                                  continue;
                                } else if($cnt == 1) {
                                  if($left < $comming) {
                                    $comming = $left;
                                    $fowl = $row->CategoryFowlRun;
                                  }
                                  // echo $left;
                                }
          
                              }


                            }
                          }
                        }
                        if($comming==9999) continue;
                        ?>
                        <h5 style="margin-bottom: 0px; color:black;">
                          <?php echo $cate?> : <?php echo date('Y-m-d', strtotime("+$comming days"));?>: 
                          <?php if($comming>=0) { echo $comming?>Day(s) left <?php } ?>
                          <?php if($comming<0) { echo -$comming?>Day(s) passed <?php } ?>
                        </h5>
                        <?php
                      }

                        ?>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-3">
              <a href="#" style="text-decoration:none; " data-toggle="modal" data-target="#mortality_view">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color: #00ffff; color:antiquewhite">
                    <h5 class="card-title">Mortality</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                      <?php
                        $fname = $_SESSION['fname'];
                        $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory where fname='$fname'");
                        $row=mysqli_fetch_array($query);
                        $total = $row['total'];
                        
                        $query1=mysqli_query($con,"select sum(tblcategory_log.CategoryCount) as total from tblcategory_log where fname='$fname'");
                        $row1=mysqli_fetch_array($query1);
                        $deaths = $row1['total'];
                        
                        $m_rate=($deaths*100)/($deaths+$total);
                        ?>
                        <h2><?php echo number_format($m_rate, 2, '.', '');?> %</h2>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
          </div>

          <div class="text-left ml-4"><h2 style="color:#FF1493">Today is <?php echo date('Y-m-d');?></h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-3">
              <a href="feed.php?cate_id=Broiler" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color:#FF1493; color:antiquewhite">
                    <h5 class="card-title" style="color:white">Feed Needed Today</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php
                        $fname = $_SESSION['fname'];
                        $sql="SELECT * from tblcategory where tblcategory.fname=:fname";
                        
                        $query = $dbh -> prepare($sql);
                        $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
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
              </a>
            </div>
            <div class="col-xxl-4 col-md-3">
              <a href="product.php" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color:#6666ff; color:antiquewhite">
                    <h5 class="card-title" style="color:white;">Eggs Collected</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php 
                            $eggs = 0;
                            $date = date('Y-m-d');
                            $query=mysqli_query($con,"select sum(tblproducts.Eggcount) as total from tblproducts where tblproducts.Eggdate = '$date' and tblproducts.fname='$fname' ");
                            $cnt=mysqli_fetch_array($query);
                            if($cnt['total']!='') $eggs = $cnt['total'];
                        ?>
                        <h2><?php echo $eggs?></h2>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card card1" style="min-height: 180px; background-color: #ff9900;">
                <a href="weight.php" style="text-decoration:none;">
                  <div class="card-body" style="background-color: #ff9900; color:antiquewhite; padding-bottom : 10px;">
                    <h5 class="card-title" style="color:white">FCR</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                      <?php
                        $cateList=['Broiler','Layer', 'Free_Range'];

                        foreach ($cateList as $cate) {
                        $query=mysqli_query($con,"select sum(tblfeed_log.total) as total_feed from tblfeed_log where tblfeed_log.fname='$fname' and tblfeed_log.category='$cate'");
                        $row=mysqli_fetch_array($query);
                        $total_feed = $row['total_feed'];
                        $query1=mysqli_query($con,"SELECT SUM(weight) AS weight FROM tblweight WHERE (fowlrun, DATE) IN ( SELECT fowlrun, MAX(DATE) FROM tblweight WHERE tblweight.fname='$fname' AND tblweight.category='$cate' GROUP BY fowlrun)");
                        
                        $row1=mysqli_fetch_array($query1);
                        $c_weight = $row1['weight'];
                        if($c_weight!=0) $fcr_rate=$total_feed/$c_weight;
                        else $fcr_rate = 0;
                        ?>
                        <h5 style="margin-bottom: 0px;"><?php echo number_format($fcr_rate, 2, '.', '');?> % : <span style="color: #FF1493;"><?php echo $cate?></span></h5>
                        <?php

                        }
                        
                      
                        ?>

                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <a href="product.php" style="text-decoration:none;">
                <div class="card info-card sales-card card1" style="min-height: 180px;">

                  <div class="card-body" style="background-color:#3366ff; color:antiquewhite">
                    <h5 class="card-title" style="color:white;">Laying %</h5>
                    <hr>
                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <?php 
                            $date = date('Y-m-d');
                            $query=mysqli_query($con,"select sum(tblproducts.Eggcount) as total from tblproducts where tblproducts.Eggdate = '$date' and tblproducts.fname='$fname' ");
                            $cnt=mysqli_fetch_array($query);

                            $query1=mysqli_query($con,"select sum(tblcategory.hews) as hens from tblcategory where tblcategory.fname='$fname'");
                            $row1=mysqli_fetch_array($query1);
                            $hens=$row1['hens'];
                            if($hens!=0) $lay_p = number_format(($cnt['total']*100)/$hens, 2, '.', '');
                            else $lay_p = 0;

                        ?>
                        <h2><?php echo $lay_p?> %</h2>
                      </div>
                    </div>
                  </div>

                </div>
              </a>
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


