<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    $fname=$_SESSION['fname'];
    $code = $cocks = $hews = 0;

    $eib= $_SESSION['editbid'];
    $current_hews=$_SESSION['current_hews'];
    $current_cocks=$_SESSION['current_cocks'];
    $current_count=$_SESSION['current_count'];
    $category=$_POST['category'];
    $fowlrun=$_POST['fowlrun'];

    $code = $cocks = $hews = 0;
    $code=$_POST['code'];
    $cocks=$_POST['cocks'];
    $hews=$_POST['hews'];
    

    $date=$_POST['up_date'];
    $age=$_POST['age'];
    $description=$_POST['reason'];
    $details=$_POST['details'];

    $delta_hews=$current_hews-$hews;
    $delta_cocks=$current_cocks-$cocks;
    $delta_count=$current_count-$code;
    if($code==0) $code = $cocks + $hews;
    
    if ($delta_hews < 0 && $delta_cocks < 0 && $delta_count<0) {
        echo '<script>alert("Update failed! Please Enter Correct Quantity!")</script>';
    } 
    else {
        if ($hews < 0 && $cocks <0 && $code<0 ) {
            echo '<script>alert("Update failed! Please Enter Correct Quantity!")</script>';
            return false;
        } 
    
        if($description == 'disease') $description = $details.' '.$description;

        if($code == 0) {
            if($hews ==1 && $cocks ==1) $description = $hews.'hen and '.$cocks.'cock died with ' . $description;
            if($hews ==1 && $cocks ==0) $description = $hews.'hen died with ' . $description;
            if($hews ==0 && $cocks ==1) $description = $cocks.'cock died with ' . $description;
            if($hews ==0 && $cocks ==0) $description = 'Please enter correct count.';
            if($hews ==1 && $cocks > 1) $description = $hews.'hen and '.$cocks.'cocks died with ' . $description;
            if($hews >1 && $cocks ==1) $description = $hews.'hens and '.$cocks.'cock died with ' . $description;
            if($hews ==0 && $cocks > 1) $description = $cocks.'cocks died with ' . $description;
            if($hews >1 && $cocks ==0) $description = $hews.'hens died with ' . $description;
            if($hews >1 && $cocks >1) $description = $hews.'hens and '.$cocks.'cocks died with ' . $description;
        }
        else $descriptions = $code.'chicken(s) died with ' . $description;
        
        $sql4="update tblcategory set tblcategory.cocks=:delta_cocks, tblcategory.hews=:delta_hews, tblcategory.CategoryCode=:delta_count where id=:eib";
        $query=$dbh->prepare($sql4);

        $query->bindParam(':delta_hews',$delta_hews,PDO::PARAM_STR);
        $query->bindParam(':delta_cocks',$delta_cocks,PDO::PARAM_STR);
        $query->bindParam(':delta_count',$delta_count,PDO::PARAM_STR);
        $query->bindParam(':eib',$eib,PDO::PARAM_STR);

        $sql_log="insert into tblcategory_log(CategoryName,CategoryFowlRun,CategoryCount,CategoryDate,CategoryDescription,fname,age)values(:category,:fowlrun,:code,:date,:description,:fname,:age)";
        $query_log=$dbh->prepare($sql_log);

        $query_log->bindParam(':fname',$fname,PDO::PARAM_STR);
        $query_log->bindParam(':age',$age,PDO::PARAM_STR);
        $query_log->bindParam(':category',$category,PDO::PARAM_STR);
        $query_log->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
        $query_log->bindParam(':code',$code,PDO::PARAM_STR);
        $query_log->bindParam(':date',$date,PDO::PARAM_STR);
        $query_log->bindParam(':description',$description,PDO::PARAM_STR);

        $query->execute();
        $query_log->execute();
        
        if ($query->execute())
        {
            echo '<script>alert("' . $descriptions . '");</script>';
            echo "<script>window.location.href ='category.php?cate_id=$category'</script>";
            
        }else{
            echo '<script>alert("Update failed! Try again later")</script>';
        }
    }
}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id4'];
    $sql2="SELECT * from tblcategory  where tblcategory.id=:eid";
    $query2 = $dbh -> prepare($sql2);
    $query2-> bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();
    $results=$query2->fetchAll(PDO::FETCH_OBJ);
    if($query2->rowCount() > 0)
    {
        foreach($results as $row)
        {
            $postingDate = new DateTime($row->PostingDate);
            $today = new DateTime('today');
            $diff = $postingDate->diff($today);
            $fdays = $diff->format('%a')+1;

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
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Age(Days)</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="age" id="age" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $fdays;?>" required />
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
                                    <input type="text" style="border-radius: 8px;" name="code" placeholder="Enter Number of Chickens..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Quantity</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="code" value="0" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Date</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="date" style="border-radius: 8px;" name="up_date" placeholder="Enter Date..." style="min-width:160px;" class="datepicker form-control" id="up_date" value="<?php echo date('Y-m-d');?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Cause of Death</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <select id="reason" style="border-radius: 8px;" name="reason" style="color: #495057;" class="form-control" required>
                                        <option value="" selected disabled hidden>Select Reason</option>
                                        <option value="disease">Disease</option>
                                        <option value="parasites">Parasites</option>
                                        <option value="heatstress">Heat stress</option>
                                        <option value="predation">Predation</option>
                                        <option value="accidents">Accidents</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php 
                            if($row->CategoryName=='Layer'||$row->CategoryName=='Free_Range') {
                        ?>
                            <div class="row b_detail-row" style="display: none;">
                                <div class="form-group col-md-12 ">
                                    <label class="col-sm-12 pl-0 pr-0">Name of Disease</label>
                                    <div class="col-sm-12 pl-0 pr-0">
                                        <select style="border-radius: 8px;" name="details" style="color: #495057;" class="form-control" required>
                                            <option value="Egg peritonitis/egg bound">Egg peritonitis/egg bound</option>
                                            <option value="Fowl coryza">Fowl coryza</option>
                                            <option value="Fowl pox">Fowl pox</option>
                                            <option value="Newcastle">Newcastle disease</option>
                                            <option value="Malnutrition">Malnutrition</option>
                                            <option value="Coccidiosis">Coccidiosis</option>
                                            <option value="Worm infestation">Worm infestation</option>
                                            <option value="Gumboro">Gumboro disease</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        <?php
                            }
                            if($row->CategoryName=='Broiler') {
                        ?>
                       
                        <div class="row b_detail-row" style="display: none;">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Name of Disease</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <select style="border-radius: 8px;" name="details" style="color: #495057;" class="form-control" required>
                                        <option value=" Yolk sac infection"> Yolk sac infection</option>
                                        <option value="Respiratory">Respiratory disease</option>
                                        <option value="Ascites">Ascites</option>
                                        <option value="Newcastle">Newcastle disease</option>
                                        <option value="Enteritis/ E coli infection">Enteritis/ E coli infection</option>
                                        <option value="Malnutrition">Malnutrition</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- <div class="col-6">
                        <div class="row">
                            <label class="col-sm-12 pl-0 pr-0">Cause of Death</label>
                            <textarea type="text" name="reason" id="reason" class="form-control" rows="22" required/>
                        </div>
                    </div> -->
                </div>
                <button type="submit" name="insert" class="btn btn-info btn-fw mr-2" style="float: left; border-radius: 8px;">Record</button>
            </form>
            <?php 
        }
    } ?>
</div>

<script>
    // Get the select elements
var reasonSelect = document.getElementById("reason");
var fDetailRow = document.querySelector(".f_detail-row");
var bDetailRow = document.querySelector(".b_detail-row");
console.log(bDetailRow);
// Add event listener to the reason select element
reasonSelect.addEventListener("change", function() {
  // Check if "reason" disease is selected
  if (this.value === "disease") {
    // Show the f_detail and b_detail rows
    bDetailRow.style.display = "block";
  } else {
    // Hide the f_detail and b_detail rows
    bDetailRow.style.display = "none";
  }
});
</script>

<script>
    var dec_id = document.getElementById("reason");
    console.log(dec_id.value);
    dec_id.addEventListener("change", function() {
    if (dec_id.selectedIndex === 0) {
        dec_id.style.color = "gray";  
    } else {
        dec_id.style.color = "#495057";
    }
    });
</script>

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