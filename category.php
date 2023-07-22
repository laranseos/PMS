<?php

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;


if(isset($_POST['save']))
{
  $frcode=$_POST['frcode'];
  $code=$_POST['code'];
  $birth=$_POST['birth'];
  $sql="insert into tblcategory(CategoryName,CategoryFowlRun,CategoryCode,PostingDate)values(:category,:frcode,:code,:birth)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':frcode',$frcode,PDO::PARAM_STR);
  $query->bindParam(':code',$code,PDO::PARAM_STR);
  $query->bindParam(':birth',$birth,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Registered successfully")</script>';
    echo "<script>window.location.href ='category.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblcategory where id='$cmpid'");
  echo "<script>alert('Category record deleted.');</script>";   
  echo "<script>window.location.href='category.php?cate_id=$category</script>";
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
            <?php if($_SESSION['Broiler']==1) {?>
            <div class="col-4">
              <a href="category.php?cate_id=Broiler"><button class="btn btn-info btn-block custom-blue">Broiler</button></a>
            </div>
            <?php } ?> <?php if($_SESSION['Layer']==1) {?>
            <div class="col-4">
              <a href="category.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
            </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
            <div class="col-4">
              <a href="category.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
            </div><?php } ?> 
          </div>
        </div>
        <h2 style="text-align: center; margin-top: 10px;"><?php echo $category ?>
          <div style="float: right;">
            <button type="button" style="border-radius: 12px; margin-right: 10px;" class="btn btn-info" data-toggle="modal" data-target="#add" id='pbtn'>Add Fowl Run
            </button>
          </div>
        </h2>
          
        <div class="content-wrapper">

          <div>
            <div class="row" style="margin-bottom: -20px;" >
            <?php
              $cate=$_SESSION['cate'];
              $sql="SELECT tblcategory.id,tblcategory.CategoryName,tblcategory.CategoryFowlRun,tblcategory.CategoryCode,tblcategory.PostingDate from tblcategory where tblcategory.CategoryName=:cate ORDER BY tblcategory.CategoryFowlRun ASC";
              
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
                  <div class="col-md-3 stretch-card grid-margin" style="padding-right: 2px;">
                    <div class="card card1" style="min-height: 35vh;">
                      <div class="card-header">
                        <h3 class="font-weight-normal mb-3 text-center" style="color: #00008B;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3>
                      </div>
                      <div class="card-body">
                          <label for="code" style="color: #aaaaaa;">Chicken Quantity</label><input type="" class="text-center" name='code' readonly="readonly" value="<?php  echo htmlentities($row->CategoryCode);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                          <label for="fpd" style="color: #aaaaaa;">Birth of Date</label><input type="" class="text-center" name='fpd'readonly="readonly" value="<?php  echo htmlentities(date("Y-m-d", strtotime($row->PostingDate)));?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                          <a href="#"  class=" edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-info auth-form-btn" style="border-radius: 16px;">Manage Deaths</button></a><hr>
                          <a href="#"  class=" edit_data6" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-success auth-form-btn" style="border-radius: 16px;">Add Chickens</button></a><hr>
                          <a href="category.php?del=<?php echo $row->id;?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <button name="login" class="btn btn-block btn-dark auth-form-btn" style="border-radius: 16px;">Remove</button></a>
                      </div>
                    </div>
                  </div>

                  <?php 
                  $cnt=$cnt+1;
                }
              } ?>
            </div>
          </div>

          <div class="row">

          <div class="modal fade" id="add">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h4 class="modal-title">Add Fowl Run</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                              <div class="col-md-12 mt-4">
                              <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row ">
                                  <div class="form-group col-md-12">
                                    <label for="exampleInputName1">Fowl-Run Name</label>
                                    <input type="text" style="border-radius: 10px;" name="frcode" value="" placeholder="Enter Fowl Run Name..." class="form-control" id="frcode"required>
                                  </div>
                                </div>
                                <div class="row ">
                                  <div class="form-group col-md-12">
                                    <label for="exampleInputName1">Chicken Count</label>
                                    <input type="text" style="border-radius: 10px;" name="code" value="" placeholder="Enter count..." class="form-control" id="code" required>
                                  </div>
                                </div>
                                <div class="row ">
                                  <div class="form-group col-md-12">
                                    <label for="exampleInputName1">Birth of Date</label>
                                    <input type="date" style="border-radius: 10px;" name="birth" placeholder="Enter Birth of Date..." class="datepicker form-control" id="birth" value="<?php echo date('Y-m-d');?>" required>
                                  </div>
                                </div>
                                <button type="submit" style="float: left; border-radius: 10px" name="save" class="btn btn-info mr-2 mb-4">Save</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>

          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <!--  start  modal -->
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Manage Deaths</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update4">
                      <?php @include("edit_category.php");?>
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
              
            </div>
          </div>
   
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <!--  start  modal -->
              <div id="editData6" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Chickens</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update6">
                      <?php @include("add_category.php");?>
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
        url:"edit_category.php",
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
    $(document).on('click','.edit_data6',function(){
      var edit_id6=$(this).attr('id');
      $.ajax({
        url:"add_category.php",
        type:"post",
        data:{edit_id6:edit_id6},
        success:function(data){
          $("#info_update6").html(data);
          $("#editData6").modal('show');
        }
      });
    });
  });
</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>
</html>