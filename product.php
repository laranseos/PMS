<?php
include('includes/checklogin.php');
check_login();

if(isset($_POST['getEgg']))
{
  $fowlrun=$_POST['fowlrun'];
  $count=$_POST['ecount'];
  $age=$_POST['age'];
  $quantity=$_POST['quantity'];
  $fname=$_SESSION['fname'];
  $tdate=date("Y-m-d");
  if($count==""){
    echo '<script>alert("Please fill egg count field!")</script>';
    echo "<script>window.location.href ='product.php'</script>";
    return false;
  }

  $sql="insert into tblproducts(tblproducts.Layer_runName,tblproducts.Eggdate,tblproducts.Eggcount, tblproducts.fname, tblproducts.age,tblproducts.quantity) values(:fowlrun,:tdate,:count,:fname,:age,:quantity)";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
  $query->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
  $query->bindParam(':count',$count,PDO::PARAM_STR);
  $query->bindParam(':age',$age,PDO::PARAM_STR);
  $query->bindParam(':quantity',$quantity,PDO::PARAM_STR);
  $query->bindParam(':tdate',$tdate,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Egg Count Successfully Logged!")</script>';
    echo "<script>window.location.href ='product.php'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}


if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblproducts where id='$cmpid'");
  echo "<script>alert('Product record deleted.');</script>";   
  echo "<script>window.location.href='product.php'</script>";
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
        <div class="content-wrapper">
            <div>
              <div class="row" style="margin-bottom: -20px;" >
                <?php

                $fname=$_SESSION['fname'];
  
                $sql="SELECT * from tblcategory where tblcategory.CategoryName='Layer' and tblcategory.fname=:fname order by tblcategory.CategoryFowlRun ASC";
                
                $query = $dbh -> prepare($sql);
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
                    $fdays_string = $fdays.'/'.(intval(($fdays-1)/7+1));
                    $fr=$row->CategoryFowlRun;
                    $dt=date("Y-m-d");

                    $sql1="SELECT * from tblproducts where tblproducts.Layer_runName=:fr and tblproducts.Eggdate=:dt";
                    
                    $query1 = $dbh -> prepare($sql1);
                    $query1-> bindParam(':fr', $fr, PDO::PARAM_STR);
                    $query1-> bindParam(':dt', $dt, PDO::PARAM_STR);
                    $query1->execute();
                    $result = $query1->fetchAll(PDO::FETCH_ASSOC);
                    $checkegg = 0;
                    if($query1->rowCount() > 0)
                    {
                      $checkegg = 1;
                      foreach($result as $rows){ 
                        $cnt = $rows['Eggcount'];
                      }
                    }

                    ?>
                    
                        <div class="col-md-3 stretch-card grid-margin" style="padding-right: 2px;">
                          <div class="card card1" style="min-height: 35vh;">
                            <div class="card-header">
                              <h4><?php  echo htmlentities(date("Y-m-d"));?><i class="mdi mdi-pin mdi-24px float-right"></i></h4>
                              <?php if($checkegg==1){ ?>
                              <h3 class="font-weight-normal mb-3 text-center" style="color: #00008B;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3> <?php } else{?> 
                              <h3 class="font-weight-normal mb-3 text-center" style="color:crimson;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3><?php } ?>
                            </div>
                            <div class="card-body">

                              <form method="post" action="product.php">
                                <input type="text" class="text-center" name='tdate' readonly="readonly"  value="<?php  echo htmlentities(date("d-m-Y"));?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;   display: none;"></input>
                                <input type="" class="text-center" name='fowlrun' readonly="readonly" value="<?php  echo htmlentities($row->CategoryFowlRun);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                <label for="code" style="color: #aaaaaa;">Chicken Count</label><input type="text" class="text-center" name='quantity' readonly="readonly" value="<?php  echo htmlentities($row->CategoryCode);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <label for="fpd" style="color: #aaaaaa;">Age(Days/Weeks)</label><input type="text" class="text-center" name='ages' readonly="readonly" value="<?php  echo htmlentities($fdays_string);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <input type="text" style="display:none;" class="text-center" name='age' readonly="readonly" value="<?php  echo htmlentities($fdays);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input>
                                <?php 
                                if($checkegg==1){  ?>
                                <label for="tfeed" style="color: #aaaaaa;">Egg Count</label><input type="text" class="text-center" readonly="readonly" value="<?php  echo htmlentities($cnt);?>" id="ecount" name='ecount' placeholder="Enter Egg Count" style="resize: vertical; width: 100%; border: none; border-color: transparent;" required></input><hr>
                                <?php
                                }
                                else { ?>
                                  <label for="tfeed" style="color: #aaaaaa;">Egg Count</label><input type="text" class="text-center" id="ecount" name='ecount' placeholder="Enter Egg Count" style="resize: vertical; width: 100%; border: none; border-color: transparent;" required></input><hr>
                                  <div class="text-center">
                                    <a href="#" data-toggle="tooltip" data-original-title="Taken" onclick="return confirm('Record Egg Count?');">
                                      <!-- <input type="checkbox" name="getEgg" style="width: 1.8em; height:1.8em;" class="getEgg align-items-center" onchange="this.form.submit()"/>&nbsp; -->
                                      <button type="submit" name="getEgg" class="btn btn-info btn-fw mr-2" style="border-radius: 8px;">Record</button>
                                    </a>
                                  </div>
                                    <?php
                                  }
                                ?>
                              </form>
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

          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <!--  start  modal -->
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Product details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="info_update4">
                      <h2>Hello</h2>
                      <?php @include("edit_product.php");?>
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
              
              <!--   end modal -->
              <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th class="text-center">Layer Run Name</th>
                      <th class="text-center">Age</th>
                      <th class="text-center">Layers</th>
                      <th class="text-center">Posting Date</th>
                      <th class="text-center">Egg Count</th>
                      <th class="text-center" style="width: 10%;">Edit</th>
                      <th class="text-center" style="width: 10%;">Cancel</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $fname=$_SESSION['fname'];
                    $sql="SELECT * from tblproducts where tblproducts.fname=:fname ORDER BY id DESC";
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
                          <td class="text-center"><?php  echo htmlentities($row->Eggcount);?></td>
                          <td class=" text-center"><a href="#"  class=" edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a></td>
                          <td class=" text-center"> <a href="product.php?del=<?php echo ($row->id);?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete" style="color: #f05050"></i> </a>
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
        url:"edit_product.php",
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
        url:"view_product.php",
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
<script>
function ValidateEggs() {
  var eggCountInput = document.getElementById("ecount");
  if (eggCountInput.value.trim() === "") {
    alert("Please enter the egg count.");
    return false; // prevent form submission
  }
  return true; // allow form submission
}
</script>
<script>
    const selects = document.getElementById("fowlrun");

    selects.addEventListener("change", function() {
    if (selects.selectedIndex === 0) {
        selects.style.color = "gray";  
    } else {
        selects.style.color = "black";
    }
    });
</script>
<style>
    option {
    color: gray; 
    }

    option:checked {
    color: black;
    }
</style>
</body>
</html>