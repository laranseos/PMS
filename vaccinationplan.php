<?php
include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;
if(isset($_POST['save']))
{
  $age=$_POST['age'];
  $disease=$_POST['disease'];
  $vaccination=$_POST['vaccination'];
  $method=$_POST['method'];
  $dose=$_POST['dose'];
  $sql="insert into tblvaccination(category,age,vaccination,dose,method,disease)values(:category,:age,:vaccination,:dose, :method,:disease)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':age',$age,PDO::PARAM_STR);
  $query->bindParam(':disease',$disease,PDO::PARAM_STR);
  $query->bindParam(':vaccination',$vaccination,PDO::PARAM_STR);
  $query->bindParam(':method',$method,PDO::PARAM_STR);
  $query->bindParam(':dose',$dose,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Vaccination plan added successfully")</script>';
    echo "<script>window.location.href ='vaccinationplan.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblvaccination where id='$cmpid'");
  echo "<script>alert('Vaccination Plan deleted.');</script>";   
  echo "<script>window.location.href='vaccinationplan.php?cate_id=$category</script>";
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
              <a href="vaccinationplan.php?cate_id=Broiler"><button class="btn btn-primary btn-block custom-blue">Broiler</button></a>
            </div>
            <?php } ?> <?php if($_SESSION['Layer']==1) {?>
            <div class="col-4">
              <a href="vaccinationplan.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
            </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
            <div class="col-4">
              <a href="vaccinationplan.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
            </div><?php } ?> 
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
                      <label for="exampleInputName1">Age(Date)</label>
                      <input type="text" style="border-radius: 10px;" name="age" value="" placeholder="Enter Age..." class="form-control" id="start"required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Disease</label>
                      <input type="text" style="border-radius: 10px;" name="disease" value="" placeholder="Enter Disease..." class="form-control" id="end" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Vaccination</label>
                      <input type="text" style="border-radius: 10px;" name="vaccination" value="" placeholder="Enter Vaccination..." class="form-control" id="end" required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Dose</label>
                      <input type="text" style="border-radius: 10px;" name="dose" placeholder="Enter Dose..." class="form-control" id="fpd" value="" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Method</label>
                      <input type="text" style="border-radius: 10px;" name="method" placeholder="Enter Method..." class="form-control" id="fpd" value="" required>
                    </div>
                  </div>
                  <button type="submit" style="float: left; border-radius: 10px;" name="save" class="btn btn-info mr-2 mb-4">Add</button>
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
                      <?php @include("edit_vaccinationplan.php");?>
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
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Category</th>
                      <th class="text-center">Age</th>
                      <th class="text-center">Disease</th>
                      <th class="text-center">Vaccination</th>
                      <th class="text-center">Dose</th>
                      <th class="text-center">Method</th>
                      <th class="text-center" style="width: 10%;">Edit</th>
                      <th class="text-center" style="width: 10%;">Remove</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $cate=$_SESSION['cate'];
                    $sql="SELECT * from tblvaccination where tblvaccination.category=:cate ORDER BY tblvaccination.age ASC";
                    
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
                          <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->disease);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->vaccination);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->dose);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->method);?></td>
                          <td class="text-center"><a href="#"  class="edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a></td>
                          <td class="text-center"> <a href="vaccinationplan.php?del=<?php echo $row->id;?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete" style="color: #f05050"></i> </a>
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
        url:"edit_vaccinationplan.php",
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