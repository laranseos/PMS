<?php
include('includes/checklogin.php');
check_login();

$category=$_GET['cate_id'];
$_SESSION['cate']=$category;

if(isset($_POST['recordWeight']))
{
  $fowlrun=$_POST['fowlrun'];
  $weight=$_POST['ecount'];
  $tdate=date("Y-m-d");
  $fname=$_SESSION['fname'];
  $age=$_POST['age'];
  $count=$_POST['count'];

  if($weight==""){
    echo '<script>alert("Please fill required field!")</script>';
    echo "<script>window.location.href ='weight.php?cate_id=$category'</script>";
    return false;
  }

  $sql="insert into tblweight(tblweight.fowlrun,tblweight.date,tblweight.weight,tblweight.fname,tblweight.age,tblweight.category,tblweight.count) values(:fowlrun,:tdate,:weight,:fname,:age,:category,:count)";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
  $query->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
  $query->bindParam(':weight',$weight,PDO::PARAM_STR);
  $query->bindParam(':age',$age,PDO::PARAM_STR);
  $query->bindParam(':tdate',$tdate,PDO::PARAM_STR);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':count',$count,PDO::PARAM_STR);
  $query->execute();

  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Chicken weight Successfully Loged!")</script>';
    echo "<script>window.location.href ='weight.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}


if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblweight where id='$cmpid'");
  echo "<script>alert('Weight record deleted.');</script>";   
  echo "<script>window.location.href='weight.php?cate_id=$category'</script>";
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
        <div class="container">
          <div class="row">
              <?php if($_SESSION['Broiler']==1) {?>
              <div class="col-4" style="padding-right:0px; padding-left:0px;">
                <a href="weight.php?cate_id=Broiler"><button class="btn btn-info btn-block custom-blue">Broiler</button></a>
              </div>
              <?php } ?> <?php if($_SESSION['Layer']==1) {?>
              <div class="col-4" style="padding-right:0px; padding-left:0px;">
                <a href="weight.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
              </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
              <div class="col-4" style="padding-right:0px; padding-left:0px;">
                <a href="weight.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red" style="padding-right:2px; padding-left:2px;
                ">Free Range</button></a>
              </div><?php } ?> 
            </div>
        </div>
        <h2 style="text-align: center; margin-top: 20px;"><?php echo $category ?></h2>
        <div class="content-wrapper">
            <div>
              <div class="row" style="margin-bottom: -20px;" >
                <?php
  
                $cate=$_SESSION['cate'];
                $fname=$_SESSION['fname'];
                $sql="SELECT * from tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname ORDER BY tblcategory.CategoryFowlRun ASC";
                
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
                    if($cate=='Free_Range') $c_code = $row->cocks + $row->hews;
                    else $c_code = $row->CategoryCode;

                    $postingDate = new DateTime($row->PostingDate);
                    $today = new DateTime('today');
                    $diff = $postingDate->diff($today);
                    $fdays = $diff->format('%a');

                    $fr=$row->CategoryFowlRun;
                    $dt=date("Y-m-d");

                    $sql1="SELECT * from tblweight where tblweight.fowlrun=:fr and tblweight.date=:dt";
                    
                    $query1 = $dbh -> prepare($sql1);

                    $query1-> bindParam(':fr', $fr, PDO::PARAM_STR);
                    $query1-> bindParam(':dt', $dt, PDO::PARAM_STR);
                    $query1->execute();
                    $result = $query1->fetchAll(PDO::FETCH_ASSOC);
                    $checkweight = 0;
                    if($query1->rowCount() > 0)
                    {
                      $checkweight = 1;
                      foreach($result as $rows){ 
                        $weight = $rows['weight'];
                      }
                    }

                    ?>
                    
                        <div class="col-md-3 stretch-card grid-margin" style="padding-right: 2px;">
                          <div class="card card1" style="min-height: 35vh;">
                            <div class="card-header">
                              <h4><?php  echo htmlentities(date("Y-m-d"));?><i class="mdi mdi-pin mdi-24px float-right"></i></h4>
                              <?php if($checkweight==1){ ?>
                              <h3 class="font-weight-normal mb-3 text-center" style="color: #00008B;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3> <?php } else{?> 
                              <h3 class="font-weight-normal mb-3 text-center" style="color:crimson;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3><?php } ?>
                            </div>
                            <div class="card-body">

                              <form method="post" action="weight.php?cate_id=<?php echo $category?>">
                                <input type="text" class="text-center" name='tdate' readonly="readonly"  value="<?php  echo htmlentities(date("d-m-Y"));?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;   display: none;"></input>
                                <input type="" class="text-center" name='fowlrun' readonly="readonly" value="<?php  echo htmlentities($row->CategoryFowlRun);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                <label for="code" style="color: #aaaaaa;">Chicken Count</label><input type="" class="text-center" name='count' readonly="readonly" value="<?php  echo htmlentities($c_code);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <label for="fpd" style="color: #aaaaaa;">Age(Days)</label><input type="" class="text-center" name='age' readonly="readonly" value="<?php  echo htmlentities($fdays+1);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <?php 
                                if($checkweight==1){  ?>
                                <label for="tfeed" style="color: #aaaaaa;">Weight(Kg)</label><input type="" class="text-center" readonly="readonly" value="<?php  echo htmlentities($weight);?>" id="ecount" name='ecount' placeholder="Enter Chicken weight" style="resize: vertical; width: 100%; border: none; border-color: transparent;" required></input><hr>
                                <?php
                                }
                                else { ?>
                                  <label for="tfeed" style="color: #aaaaaa;">Weight(Kg)</label><input type="" class="text-center" id="ecount" name='ecount' placeholder="Enter Chicken Weight" style="resize: vertical; width: 100%; border: none; border-color: transparent;" required></input><hr>
                                  <div class="text-center">
                                    <a href="#" data-toggle="tooltip" data-original-title="Taken" onclick="return confirm('Do you record weight?');">
                                      <button type="submit" name="recordWeight" class="btn btn-info btn-fw mr-2" style="border-radius: 8px;">Record</button>
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
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Weight</h5>
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
                  </div>
                </div>
              </div>

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
                      <!-- <th class="text-center" style="width: 10%;">Edit</th> -->
                      <th class="text-center" style="width: 10%;">Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $cate=$_SESSION['cate'];
                    $fname=$_SESSION['fname'];
                    $sql="SELECT * from tblweight where tblweight.fname=:fname and tblweight.category=:cate ORDER BY id DESC";
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
                          <td class="text-center"><?php  echo htmlentities($row->weight);?></td>
                          <!-- <td class=" text-center"><a href="#"  class=" edit_data4" id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a></td> -->
                          <td class=" text-center"> <a href="weight.php?del=<?php echo ($row->id);?>&cate_id=<?php echo $cate;?>" data-toggle="tooltip" data-original-title="Delete" onclick="return confirm('Do you really want to delete log?');"> <i class="mdi mdi-delete" style="color: #f05050"></i> </a>
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
      <?php @include("includes/footer.php");?>
    </div>
  </div>
</div>
<?php @include("includes/foot.php");?>
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