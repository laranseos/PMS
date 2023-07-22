<?php
include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;
if(isset($_POST['save']))
{
  $start=$_POST['start'];
  $end=$_POST['end'];
  $fpd=$_POST['fpd'];
  $sql="insert into tblfeed(start,end,fpd,category)values(:start,:end,:fpd,:category)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':start',$start,PDO::PARAM_STR);
  $query->bindParam(':end',$end,PDO::PARAM_STR);
  $query->bindParam(':fpd',$fpd,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Added successfully")</script>';
    echo "<script>window.location.href ='feedplan.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblfeed where id='$cmpid'");
  echo "<script>alert('Category record deleted.');</script>";   
  echo "<script>window.location.href='feedplan.php?cate_id=$category</script>";
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
              <a href="feedplan.php?cate_id=Broiler"><button class="btn btn-primary btn-block custom-blue">Broiler</button></a>
            </div>
            <div class="col-4">
              <a href="feedplan.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
            </div>
            <div class="col-4">
              <a href="feedplan.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
            </div>
          </div>
        </div>
        <h2 style="text-align: center; margin-top: 20px;"><?php echo $category ?></h2>
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
               <div class="modal-header">
                <h5 class="modal-title" style="float: left;">Add Plan</h5>
              </div>
              <div class="col-md-12 mt-4">
                <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Start Day</label>
                      <input type="text" name="start" value="" placeholder="Enter Start Day..." class="form-control" id="start"required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">End Day</label>
                      <input type="text" name="end" value="" placeholder="Enter End Day..." class="form-control" id="end" required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Feed per day(Kg)</label>
                      <input type="text" name="fpd" placeholder="Enter Feed per day..." class="form-control" id="fpd" value="" required>
                    </div>
                  </div>
                  <button type="submit" style="float: left;" name="save" class="btn btn-primary mr-2 mb-4">Add</button>
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
                      <h5 class="modal-title">Edit Feed details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update4">
                      <?php @include("edit_feedplan.php");?>
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
                      <th class="text-center">Start Day</th>
                      <th class="text-center">End Day</th>
                      <th class="text-center">Feed Per Day</th>
                      <th class=" Text-center" style="width: 15%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $cate=$_SESSION['cate'];
                    $sql="SELECT tblfeed.id,tblfeed.category,tblfeed.start,tblfeed.end,tblfeed.fpd from tblfeed where tblfeed.category=:cate ORDER BY start ASC";
                    
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
                          <td class="text-center"><?php  echo htmlentities($row->start);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->end);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->fpd);?></td>
                          <td class=" text-center"><a href="#"  class="edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a>
                            <a href="feedplan.php?del=<?php echo $row->id;?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete"></i> </a>
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
        url:"edit_feedplan.php",
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


</body>
</html>