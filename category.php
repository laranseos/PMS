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
  $fname=$_SESSION['fname'];
  $code=0;
  $breed="Not set";
  $cocks=0;
  $hews=0;
  
  $birth=$_POST['birth'];

  $sql="insert into tblcategory(CategoryName,CategoryFowlRun,CategoryCode,PostingDate,breed,cocks,hews,fname) values(:category,:frcode,:code,:birth,:breed,:cocks,:hews,:fname)";
  $query=$dbh->prepare($sql);

  $query->bindParam(':fname',$fname,PDO::PARAM_STR);

  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':frcode',$frcode,PDO::PARAM_STR);
  $query->bindParam(':code',$code,PDO::PARAM_STR);
  $query->bindParam(':birth',$birth,PDO::PARAM_STR);

  $query->bindParam(':breed',$breed,PDO::PARAM_STR);
  $query->bindParam(':cocks',$cocks,PDO::PARAM_STR);
  $query->bindParam(':hews',$hews,PDO::PARAM_STR);

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
            <div class="col-4" style="padding-right:0px; padding-left:0px;">
              <a href="category.php?cate_id=Broiler"><button class="btn btn-info btn-block custom-blue">Broiler</button></a>
            </div>
            <?php } ?> <?php if($_SESSION['Layer']==1) {?>
            <div class="col-4" style="padding-right:0px; padding-left:0px;">
              <a href="category.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
            </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
            <div class="col-4" style="padding-right:0px; padding-left:0px;">
              <a href="category.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red" style="padding-right:2px; padding-left:2px;
              ">Free Range</button></a>
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
              $fname=$_SESSION['fname'];
              $sql="SELECT * from tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname  ORDER BY tblcategory.CategoryFowlRun ASC";
              
              $query = $dbh -> prepare($sql);
              $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
              $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
              $query->execute();
              $results=$query->fetchAll(PDO::FETCH_OBJ);
              $cnt=1;
              if($query->rowCount() > 0)
              {
                foreach($results as $row)
                { 
                  $postingDate = new DateTime($row->PostingDate);
                  $today = new DateTime('today');
                  $diff = $postingDate->diff($today);
                  $fdays = $diff->format('%a');
                  $_SESSION['current_weight'] = $row->weight;
                  ?>
                  <div class="col-md-3 stretch-card grid-margin" style="padding-right: 2px;">
                    <div class="card card1" style="min-height: 35vh;">
                      <div class="card-header">
                        <h3 class="font-weight-normal mb-3 text-center" style="color: #00008B;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3>
                      </div>
                      <div class="card-body">
                          
                          <?php if($category=="Free_Range") { ?>
                            <div class="row">
                                <div class="col-md-6">
                                  <label for="code" style="color: #aaaaaa;">Hens</label><input type="" class="text-center" name='hewss' readonly="readonly" value="<?php  echo htmlentities($row->hews);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                                </div>
                                <div class="col-md-6">
                                  <label for="code" style="color: #aaaaaa;">Cocks</label><input type="" class="text-center" name='cockss' readonly="readonly" value="<?php  echo htmlentities($row->cocks);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                                </div>
                            </div>
                            <hr style="margin-top: 6px; margin-bottom:6px;">

                            <label for="fpd" style="color: #aaaaaa;">Breed</label><input type="" class="text-center" name='breeds'readonly="readonly" value="<?php  echo htmlentities($row->breed);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                            <hr style="margin-top: 6px; margin-bottom:6px;">
                          <?php } else { ?> 
                            <label for="code" style="color: #aaaaaa;">Chicken Quantity</label><input type="" class="text-center" name='code' readonly="readonly" value="<?php  echo htmlentities($row->CategoryCode);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                            <hr style="margin-top: 6px; margin-bottom:6px;">  
                          <?php } ?>

                          <label for="fpd" style="color: #aaaaaa;">Age (days)</label><input type="" class="text-center" name='fpd'readonly="readonly" value="<?php  echo htmlentities($fdays+1);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                          <hr style="margin-top: 6px; margin-bottom:6px;">

                          <!-- <label for="fpd" style="color: #aaaaaa;">Weight(Kg) : <span style="color:darkmagenta"><?php  echo htmlentities($row->weightDate);?></span></label><input type="" class="text-center" name='fpd'readonly="readonly" value="<?php  echo htmlentities($row->weight);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                          <hr style="margin-top: 6px; margin-bottom:6px;"> -->

                          <a href="#"  class=" edit_data6" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-success auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Add Chickens</button></a><hr style="margin-top: 6px; margin-bottom:6px;">
                          <!-- <a href="#"  class=" edit_data5" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-success auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Update Weight</button></a><hr style="margin-top: 6px; margin-bottom:6px;"> -->
                          
                          <a href="#"  class=" edit_data7" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-info auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Cull</button></a>
                          <hr style="margin-top: 6px; margin-bottom:6px;">
                          <a href="#"  class=" edit_data8" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-info auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Sale</button></a>

                          <hr style="margin-top: 6px; margin-bottom:6px;">
                          <a href="#"  class=" edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><button name="login" class="btn btn-block btn-info auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Record Mortality</button></a><hr style="margin-top: 6px; margin-bottom:6px;">
                          <!-- <a href="category.php?del=<?php echo $row->id;?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <button name="login" class="btn btn-block btn-dark auth-form-btn" style="border-radius: 16px; padding-right:5px; padding-left:5px;">Remove Fowl</button></a> -->
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


                                <?php if($category=='Broiler') { ?>
                                 <div class="row" style="display: none;">
                                <?php } else { ?> 
                                  <div class="row">
                                <?php }
                                 ?>
                                  <div class="form-group col-md-4">
                                    <label for="exampleInputName1">Age</label>
                                    <input type="text" style="border-radius: 10px;" name="age" value="1" placeholder="age" class="form-control" id="age" required disabled>
                                  </div>
                                  <div class="form-group col-md-4">
                                  <label for="unit"></label>
                                  <select id="unit" name="unit" style="border-radius: 8px; color:black;" class="form-control mt-1" required disabled>
                                      <option value="days" selected>days</option>
                                      <option value="weeks">weeks</option>
                                  </select>                                  </div>
                                  <div class="form-group col-md-4">
                                    <label for="exampleInputName1"> </label>
                                    <div class="row align-items-center mt-1"><input type="checkbox" checked name="dayold" id="dayold" style="width: 20px; height:20px;" class="form-control mr-1 mt-2"><label for="dayold" class="mt-3"> Day Old</label></div>
                                  </div>
                                </div>
                                
                               

                                <div class="row" style="display: none;">
                                  <div class="form-group col-md-12">
                                    <label for="exampleInputName1">Date of Birth</label>
                                    <input type="date" style="border-radius: 10px;" name="birth" placeholder="Enter Date of Birth..." class="datepicker form-control" id="birth" value="<?php echo date('Y-m-d');?>" required>
                                  </div>
                                </div>

                                <button type="submit" style="float: left; border-radius: 10px" name="save" class="btn btn-info mr-2 mb-4">Add</button>
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
                      <h5 class="modal-title">Record Mortality</h5>
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
              <div id="editData5" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Update Weight</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update5">
                      <?php @include("add_weight_category.php");?>
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

          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <!--  start  modal -->
              <div id="editData7" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Culling Chickens</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update7">
                      <?php @include("remove_category.php");?>
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
              <div id="editData8" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Sell Chickens</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update8">
                      <?php @include("sale_category.php");?>
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
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"add_weight_category.php",
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
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data7',function(){
      var edit_id7=$(this).attr('id');
      $.ajax({
        url:"remove_category.php",
        type:"post",
        data:{edit_id7:edit_id7},
        success:function(data){
          $("#info_update7").html(data);
          $("#editData7").modal('show');
        }
      });
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data8',function(){
      var edit_id8=$(this).attr('id');
      $.ajax({
        url:"sale_category.php",
        type:"post",
        data:{edit_id8:edit_id8},
        success:function(data){
          $("#info_update8").html(data);
          $("#editData8").modal('show');
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
<script>
    const selects = document.getElementById("breed");

    selects.addEventListener("change", function() {
    if (selects.selectedIndex === 0) {
        selects.style.color = "gray";  
    } else {
        selects.style.color = "#495057";
    }
    });
</script>

<script>
  // Get the checkbox element
const checkbox = document.getElementById("dayold");

// Get the age and unit elements
const ageInput = document.getElementById("age");
const unitSelect = document.getElementById("unit");

// Add event listener to the checkbox
checkbox.addEventListener("change", function() {
  // Toggle the disabled state of age and unit elements
  ageInput.disabled = this.checked;
  ageInput.value = "1";
  unitSelect.disabled = this.checked;
});
</script>

<script>
const ageInputs = document.getElementById("age");
const unitSelects = document.getElementById("unit");
const birthInput = document.getElementById("birth");

// Add event listeners to listen for changes
ageInputs.addEventListener("input", calculateOutput);
unitSelects.addEventListener("change", calculateOutput);

function calculateOutput() {
  let age = parseInt(ageInputs.value) || 0;
  let selectedOption = unitSelects.value; // Get the selected option value

  let output;
  const today = new Date();
  if (selectedOption === "days") {
    output = age;
  } else if (selectedOption === "weeks") {
    output = age * 7;
  }

    
  today.setDate(today.getDate() - output);
  const birthDate = today.toISOString().split("T")[0];

  // Set the birth value in the input field
  birthInput.value = birthDate;

  console.log(output); // This will display the calculated output value
  console.log(birthInput.value);
}

</script>

</body>
</html>