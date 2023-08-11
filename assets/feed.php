<?php
include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;

if(isset($_POST['schedule']))
{

  $fowlrun=$_POST['fowlrun'];

  $sql="SELECT tblcategory.CategoryName, tblcategory.CategoryCode, tblcategory.PostingDate from tblcategory where tblcategory.CategoryFowlRun=:fowlrun";
  $query=$dbh->prepare($sql);
  $query->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_ASSOC);
  if($query->rowCount() > 0)
  {
    foreach ($results as $row) {
        $c_code = $row['CategoryCode']; 
        $c_date = $row['PostingDate'];
    }
  }

  $postingDate = new DateTime($c_date);
  $today = new DateTime('today');
  $diff = $postingDate->diff($today);
  $fdays = $diff->format('%a');
  

  $sql1="SELECT tblfeed.fpd from tblfeed where tblfeed.category=:category  and tblfeed.start<=:fdays and tblfeed.end>=:fdays";
  $query1=$dbh->prepare($sql1);
  $query1->bindParam(':fdays',$fdays,PDO::PARAM_STR);
  $query1->bindParam(':category',$category,PDO::PARAM_STR);
  $query1->execute();
  $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);

  if($query1->rowCount() > 0)
  {  
    foreach ($results1 as $row1) {
      $c_feed =  $row1['fpd'];
    }
  }

}

if(isset($_POST['save']))
{
  $fowlrun=$_POST['fowlrun'];
  $code=$_POST['code'];
  $fpd=$_POST['fpd'];
  $tfeed=$_POST['tfeed'];
  $gfeed=$_POST['gfeed'];
  $tdate=$_POST['tdate'];
  
  $sql="insert into tblfeed_log(category,fowlrun,count,fpd,total,feed,posting) values(:category,:fowlrun,:code,:fpd,:tfeed,:gfeed,:tdate)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
  $query->bindParam(':code',$code,PDO::PARAM_STR);
  $query->bindParam(':fpd',$fpd,PDO::PARAM_STR);
  $query->bindParam(':tfeed',$tfeed,PDO::PARAM_STR);
  $query->bindParam(':gfeed',$gfeed,PDO::PARAM_STR);
  $query->bindParam(':tdate',$tdate,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Loged successfully")</script>';
    echo "<script>window.location.href ='feed.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}

if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblfeed_log where id='$cmpid'");
  echo "<script>alert('Feed Log deleted.');</script>";   
  echo "<script>window.location.href='feed.php?cate_id=$category</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <?php @include("includes/header.php");?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <?php @include("includes/sidebar.php");?>
      <!-- partial -->
      <div class="main-panel">
        <div class="container">
          <div class="row">
              <div class="col-4">
                <a href="feed.php?cate_id=Broiler"><button class="btn btn-primary btn-block custom-blue">Broiler</button></a>
              </div>
              <div class="col-4">
                <a href="feed.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
              </div>
              <div class="col-4">
                <a href="feed.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
              </div>
            </div>
          </div>
          <h2 style="text-align: center; margin-top: 20px;"><?php echo $category ?></h2>
          <div class="content-wrapper">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">Add Fowl-Run</h5>
                  <a href="feedplan.php?cate_id=<?php echo $category;?>"><button type="button" class="btn btn-sm btn-info" style="float: right;">View Feed Plan</button></a>
                </div>
                <div class="col-md-12 mt-4">
                  <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="row ">
                      <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Fowl-Run Name</label>
                        <select  name="fowlrun"  class="form-control" required>
                          <option value="<?php  echo $fowlrun;?>"><?php  echo $fowlrun;?></option>
                          <?php
                          $cate=$_SESSION['cate'];
                          $sql="SELECT * from  tblcategory where tblcategory.CategoryName='$cate'";
                          $query = $dbh -> prepare($sql);
                          $query->execute();
                          $results=$query->fetchAll(PDO::FETCH_OBJ);
                          if($query->rowCount() > 0)
                          {
                            foreach($results as $row)
                            {
                              ?> 
                              <option value="<?php  echo $row->CategoryFowlRun;?>"><?php  echo $row->CategoryFowlRun;?></option>
                              <?php 
                            }
                          } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                        <br>
                        <button type="submit" name="schedule" class="btn btn-primary btn-block mr-2 md-2">Schedule</button>
                      </div>
                    </div>
                    <div class="row ">
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Chicken Count</label>
                        <input type="text" name="code" readonly="readonly" value="<?php echo $c_code; ?>" placeholder="Enter count..." class="form-control" id="code" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Date of Birth</label>
                        <input type="date" name="birth" readonly="readonly" placeholder="Enter Date of Birth..." class="form-control" id="birth" value="<?php echo $c_date;?>" required>
                      </div>
                    </div>
                    <div class="row ">
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Feed Per day(Kg)</label>
                        <input type="text" name="fpd" readonly="readonly" value="<?php echo $c_feed; ?>" placeholder="Expected feed..." class="form-control" id="fpd" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Total Feed(Kg)</label>
                        <input type="text" name="tfeed" readonly="readonly" value="<?php echo $c_feed*$c_code; ?>" placeholder="Total Amount..." class="form-control" id="tfeed" required>
                      </div>
                    </div>
                    <div class="row ">
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Date</label>
                        <input type="date" name="tdate" readonly="readonly" placeholder="Today..." class="form-control" id="tdate" value="<?php echo date('Y-m-d');?>" required>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputName1">Taken Feed</label>
                        <input type="text" name="gfeed" value="" placeholder="Enter count..." class="form-control" id="gfeed">
                      </div>
                    </div>
                    <button type="submit" style="float: left;" name="save" class="btn btn-primary mr-2 mb-4">Take</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <!--  start  modal -->
                <div id="editData4" class="modal fade">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Category details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update4">
                        <?php @include("edit_feed.php");?>
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->
                <!--  start  modal -->
                <div id="editData5" class="modal fade">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">View category details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update5">
                        <?php @include("view_category.php");?>
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                    <thead>
                      <tr>
                        <th class="text-center">No</th>
                        <th>Category</th>
                        <th class="text-center">FowlRun</th>
                        <th class="text-center">Chicken Count</th>
                        <th class="text-center">Feed per day</th>
                        <th class="text-center">Total Feed</th>
                        <th class="text-center">Taken Feed</th>
                        <th class="text-center">Posting Date</th>
                        <th class=" Text-center" style="width: 15%;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $cate=$_SESSION['cate'];
                      $sql="SELECT tblfeed_log.id, tblfeed_log.category,tblfeed_log.fowlRun,tblfeed_log.count,tblfeed_log.fpd,tblfeed_log.total,tblfeed_log.feed,tblfeed_log.posting from tblfeed_log where tblfeed_log.category=:cate ORDER BY id DESC";
                      
                      $query = $dbh -> prepare($sql);
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
                            <td class="text-center"><?php  echo htmlentities($row->category);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->fowlRun);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->fpd);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->total);?></td>
                            <td class="text-center"><?php  echo htmlentities($row->feed);?></td>
                            <td class="text-center"><?php  echo htmlentities(date("d-m-Y", strtotime($row->posting)));?></td>
                            <td class=" text-center"><a href="#"  class=" edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a>
                              <a href="feed.php?del=<?php echo $row->id;?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete"></i> </a>
                            </td>
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
      <!-- content-wrapper ends -->
      <!-- partial:../../partials/_footer.html -->
      <?php @include("includes/footer.php");?>
      <!-- partial -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php @include("includes/foot.php");?>
<!-- End custom js for this page -->
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_feed.php",
        type:"post",
        data:{edit_id4:edit_id4},
        success:function(data){
          $("#info_update4").html(data);
          $("#editData4").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_category.php",
        type:"post",
        data:{edit_id5:edit_id5},
        success:function(data){
          $("#info_update5").html(data);
          $("#editData5").modal('show');
        }
      });
    });
  });
</script>

</body>
</html>