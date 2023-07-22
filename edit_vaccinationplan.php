<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    
    $eib= $_SESSION['editbid'];

    $age=$_POST['age'];
    $vaccination=$_POST['vaccination'];
    $disease=$_POST['disease'];
    $dose=$_POST['dose'];
    $method=$_POST['method'];
    

    $sql4="update tblvaccination set age=:age,vaccination=:vaccination, disease=:disease, dose=:dose, method=:method where tblvaccination.id=:eib";
    $query=$dbh->prepare($sql4);

    $query->bindParam(':age',$age,PDO::PARAM_STR);
    $query->bindParam(':vaccination',$vaccination,PDO::PARAM_STR);
    $query->bindParam(':disease',$disease,PDO::PARAM_STR);
    $query->bindParam(':dose',$dose,PDO::PARAM_STR);
    $query->bindParam(':method',$method,PDO::PARAM_STR);
    $query->bindParam(':eib',$eib,PDO::PARAM_STR);

    $query->execute();

    
    if ($query->execute())
    {
        echo '<script>alert("Updated successfully!");</script>';
        
    }else{
        echo '<script>alert("Update failed! Try again later")</script>';
    }
    
}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id4'];
    $sql2="SELECT * from tblvaccination  where tblvaccination.id=:eid";
    $query2 = $dbh -> prepare($sql2);
    $query2-> bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();
    $results=$query2->fetchAll(PDO::FETCH_OBJ);
    if($query2->rowCount() > 0)
    {
        foreach($results as $row)
        {
            $_SESSION['editbid']=$row->id;
            ?>
            <form class="form-sample"  method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Category</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="category" id="category" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $row->category;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Age</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="age" class="form-control" style="min-width:160px;" value="<?php  echo $row->age;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Disease</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="disease" value="<?php  echo $row->disease;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Vaccination</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                <input type="text" name="vaccination" value="<?php  echo $row->vaccination;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Dose</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                <input type="text" name="dose" value="<?php  echo $row->dose;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Method</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                <input type="text" name="method" value="<?php  echo $row->method;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" name="insert" class="btn btn-primary btn-fw mr-2" style="float: left;">Update</button>
            </form>
            <?php 
        }
    } ?>
</div>
