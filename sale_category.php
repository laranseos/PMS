<?php
session_start();
include('includes/dbconnection.php');

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$category=$_SESSION['cate'];

if(isset($_POST['insertsa']))
{
    $code = $cocks = $hews = 0;
    $fowlrun=$_POST['fowlrun'];
    $dressed=$_POST['dressed'];
    $weight=$_POST['weight'];

    $cocks=$_POST['cocks'];
    $hews=$_POST['hews'];
    $current_hews=$_SESSION['current_hews'];
    $current_cocks=$_SESSION['current_cocks'];

    $eid = $_SESSION['editbid'];
    $code=$_SESSION['current_count'] - $_POST['codes'];

    $delta_hews=$current_hews-$hews;
    $delta_cocks=$current_cocks-$cocks;


    $sql4="update tblcategory set tblcategory.cocks=:delta_cocks, tblcategory.hews=:delta_hews,tblcategory.CategoryCode=:code where id=:eid";
    $query=$dbh->prepare($sql4);
    $query->bindParam(':code',$code,PDO::PARAM_STR);
    $query->bindParam(':delta_hews',$delta_hews,PDO::PARAM_STR);
    $query->bindParam(':delta_cocks',$delta_cocks,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();

    if ($query->execute())
    {
        echo '<script>alert("Saled  '.$dressed.' '.$weight.'kg '.$_POST['codes'].'chickens from '.$fowlrun.'")</script>';
        echo "<script>window.location.href ='category.php?cate_id=$category'</script>";
    }else{
        echo '<script>alert("Addition failed! try again later")</script>';
    }
}

?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id8'];
    $sql2="SELECT * from tblcategory  where tblcategory.id=:eid";
    $query2 = $dbh -> prepare($sql2);
    $query2-> bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();
    $results=$query2->fetchAll(PDO::FETCH_OBJ);

    if($query2->rowCount() > 0)
    {
        foreach($results as $row)
        {
            $_SESSION['editbid']=$row->id;
            $_SESSION['current_count']=$row->CategoryCode;
            $_SESSION['current_hews']=$row->hews;
            $_SESSION['current_cocks']=$row->cocks;
            
            ?>
            <form class="form-sample"  method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Category</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="category" id="category" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $row->CategoryName;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Fowl Run</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="fowlrun" id="fowlrun" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $row->CategoryFowlRun;?>" required />
                                </div>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="form-group col-md-6 ">
                                <label class="pl-0 pr-0">Weight(Kg)</label>
                                <div class="pl-0 pr-0">
                                    <select id="dressed" style="border-radius: 8px; color:black;" name="dressed" style="color: #495057;" class="form-control" required>
                                        <option value="Dressed">Dressed</option>
                                        <option value="Live Weight">Live Weight</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class=" pl-0 pr-0"></label>
                                <div class=" pl-0 pr-0 mt-1">
                                    <input type="text" style="border-radius: 8px;" name="weight" value="" placeholder="Enter chicken weight..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <?php 
                        if($row->CategoryName == "Free_Range") {?> 
                        <div class="row ">
                            <div class="form-group col-md-4">
                                <label for="exampleInputName1">Quantity</label>
                                <input type="text" style="border-radius: 10px;" name="hews" value="" placeholder="Hens" class="form-control" id="hews" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputName1"> </label>
                                <input type="text" style="border-radius: 10px;" name="cocks" value="" placeholder="Cocks" class="form-control mt-1" id="cocks" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputName1">Total</label>
                                <input type="text" style="border-radius: 10px;" name="total" value="0" class="form-control"   id="total" disabled>
                            </div>
                        </div>
                        <?php }  else { ?> 
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Quantity</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="codes" placeholder="Enter Number of Chickens..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Quantity</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="codes" placeholder="Enter Number of Chickens..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div> -->
                        
                    </div>
                  
                </div>
                <button type="submit" name="insertsa" class="btn btn-info btn-fw mr-2" style="float: left; border-radius: 8px;">Sale</button>
            </form>
            <?php 
        }
    } ?>
</div>
<script>
  var hewsInput = document.getElementById("hews");
  var cocksInput = document.getElementById("cocks");
  var totalInput = document.getElementById("total");

  // Add event listeners to the input fields
  hewsInput.addEventListener("input", calculateTotal);
  cocksInput.addEventListener("input", calculateTotal);

  // Define the calculateTotal function
  function calculateTotal() {
    // Get the values of the hews and cocks inputs
    var hewsValue = parseInt(hewsInput.value) || 0;
    var cocksValue = parseInt(cocksInput.value) || 0;

    // Calculate the total value
    var totalValue = hewsValue + cocksValue;

    // Set the value of the total input
    totalInput.value = totalValue;
  }
</script>