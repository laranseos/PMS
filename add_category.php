<?php
session_start();
include('includes/dbconnection.php');

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$category=$_SESSION['cate'];

if(isset($_POST['insertss']))
{
    $fowlrun=$_POST['fowlrun'];
    $eid = $_SESSION['editbid'];

    $breed="";
    $cocks=0;
    $hews=0;
    
    $birth=$_POST['birth'];

    if(isset($_POST['code'])) $code=$_POST['code'] + $_SESSION['current_count'];
    if(isset($_POST['breed'])) $breed=$_POST['breed'];
    if(isset($_POST['cocks'])) $cocks=$_POST['cocks'] + $_SESSION['current_cocks'];
    if(isset($_POST['hews'])) $hews=$_POST['hews'] + $_SESSION['current_hews'];

    $sql4="update tblcategory set tblcategory.cocks=:cocks, tblcategory.hews=:hews, tblcategory.breed=:breed, tblcategory.CategoryCode=:code  where id=:eid";
    $query=$dbh->prepare($sql4);

    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->bindParam(':code',$code,PDO::PARAM_STR);
    $query->bindParam(':breed',$breed,PDO::PARAM_STR);
    $query->bindParam(':cocks',$cocks,PDO::PARAM_STR);
    $query->bindParam(':hews',$hews,PDO::PARAM_STR);
    $query->execute();

    if ($query->execute())
    {
        echo '<script>alert("Added '.$_POST['code'].'chickens to '.$fowlrun.'")</script>';
        echo "<script>window.location.href ='category.php?cate_id=$category'</script>";
    }else{
        echo '<script>alert("Addition failed! try again later")</script>';
    }

}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id6'];
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
            $_SESSION['current_cocks']=$row->cocks;
            $_SESSION['current_hews']=$row->hews;
            
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
                        
                        <?php 
                        if($row->CategoryName == "Free_Range") {?> 
                        <div class="row">
                        <div class="form-group col-md-12">
                            <label for="breed">Breed</label>
                            <select id="breed" name="breed" style="border-radius: 8px;" class="form-control" required>
                                <option value="" selected disabled hidden>Select Breed</option>
                                <option value="Ayam cemani">Ayam cemani</option>
                                <option value="Barred Plymouth rock">Barred Plymouth rock </option>
                                <option value="Black australorp">Black australorp</option>
                                <option value="Black (swat) orpington">Black (swat) orpington</option>
                                <option value="Blue laced wyandotte">Blue laced wyandotte</option>
                                <option value="Blue orpington">Blue orpington</option>
                                <option value="Blue Buff Brahma">Blue Buff Brahma</option>
                                <option value="Boschveld">Boschveld</option>
                                <option value="Buff Columbia Brahma">Buff Columbia Brahma</option>
                                <option value="Buff orpington">Buff orpington</option>
                                <option value="Buff Orpington bantam">Buff Orpington bantam</option>
                                <option value="Columbian light brahma">Columbian light brahma</option>
                                <option value="Crested Legbar">Crested Legbar</option>
                                <option value="Frizzle">Frizzle</option>
                                <option value="Golden Laced Orpington">Golden Laced Orpington</option>
                                <option value="Gold laced wyandotte">Gold laced wyandotte</option>
                                <option value="Gold laced Orpington">Gold laced Orpington</option>
                                <option value="Gold partridge brahma">Gold partridge brahma</option>
                                <option value="Jubilee Orpington">Jubilee Orpington</option>
                                <option value="Lavender orpington">Lavender orpington</option>
                                <option value="Lemon Pyle Brahma">Lemon Pyle Brahma</option>
                                <option value="Light Columbia Bra">Light Columbia Bra</option>
                                <option value="Light Columbia wyandotte">Light Columbia wyandotte</option>
                                <option value="Light sussex">Light sussex</option>
                                <option value="Mixed breed">Mixed breed</option>
                                <option value="Naked Neck">Naked Neck</option>
                                <option value="Orpington koekoek">Orpington koekoek</option>
                                <option value="Perkin bantam">Perkin bantam</option>
                                <option value="Polish bantam">Polish bantam</option>
                                <option value="Potchefstroom koekoek">Potchefstroom koekoek</option>
                                <option value="Rhode Island red">Rhode Island red</option>
                                <option value="Rhode island white">Rhode island white</option>
                                <option value="Sasso">Sasso</option>
                                <option value="Silver laced wyandotte">Silver laced wyandotte</option>
                                <option value="Silver patridge brahma">Silver patridge brahma</option>
                                <option value="Speckled sussex">Speckled sussex</option>
                                <option value="Splash Orpington">Splash Orpington</option>
                                <option value="Venda motle">Venda motle</option>
                                <option value="White leghorn">White leghorn</option>
                                <option value="White Plymouth Rock">White Plymouth Rock</option>
                            </select>
                        </div>
                        </div>
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
                        <!-- <div class="row">
                            <div class="form-group col-md-4">
                            <label for="exampleInputName1">Age</label>
                            <input type="text" style="border-radius: 10px;" name="age" value="0" placeholder="age" class="form-control" id="age" required disabled>
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
                        </div> -->
                        <?php }  else { ?> 
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Quantity</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="code" placeholder="Enter Number of Chickens..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- <?php if($category=='Broiler') { ?>
                                 <div class="row" style="display: none;">
                                <?php } else { ?> 
                                  <div class="row">
                                <?php }
                                 ?>
                                  <div class="form-group col-md-4">
                                    <label for="exampleInputName1">Age</label>
                                    <input type="text" style="border-radius: 10px;" name="age" value="0" placeholder="age" class="form-control" id="age" required disabled>
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
                                </div> -->
                                
                        <?php } ?>
                        
                    </div>
                  
                </div>
                <button type="submit" name="insertss" class="btn btn-info btn-fw mr-2" style="float: left; border-radius: 8px;">Add</button>
            </form>
            <?php 
        }
    } ?>
</div>

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
  ageInput.value = "0";
  unitSelect.disabled = this.checked;
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
