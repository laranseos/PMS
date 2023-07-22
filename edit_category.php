<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    
    $eib= $_SESSION['editbid'];
    $current_count=$_SESSION['current_count'];
    $category=$_POST['category'];
    $fowlrun=$_POST['fowlrun'];
    $code=$_POST['code'];
    $date=$_POST['up_date'];
    $description=$_POST['description'];

    $delta_count=$current_count-$code;

    if ($delta_count < 0) {
        echo '<script>alert("Update failed! Please Enter Correct Quantity!")</script>';
    } 
    else {
        if ($code <= 0) {
            echo '<script>alert("Update failed! Please Enter Correct Quantity!")</script>';
            return false;
        } 
        
        $description = $code.' chicken(s) died with ' . $description;
        
        $sql4="update tblcategory set CategoryCode=:delta_count where id=:eib";
        $query=$dbh->prepare($sql4);

        $query->bindParam(':delta_count',$delta_count,PDO::PARAM_STR);
        $query->bindParam(':eib',$eib,PDO::PARAM_STR);

        $sql_log="insert into tblcategory_log(CategoryName,CategoryFowlRun,CategoryCount,CategoryDate,CategoryDescription)values(:category,:fowlrun,:code,:date,:description)";
        $query_log=$dbh->prepare($sql_log);
        
        $query_log->bindParam(':category',$category,PDO::PARAM_STR);
        $query_log->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
        $query_log->bindParam(':code',$code,PDO::PARAM_STR);
        $query_log->bindParam(':date',$date,PDO::PARAM_STR);
        $query_log->bindParam(':description',$description,PDO::PARAM_STR);

        $query->execute();
        $query_log->execute();
        
        if ($query->execute())
        {
            echo '<script>alert("' . $description . '");</script>';
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
    $sql2="SELECT tblcategory.id,tblcategory.CategoryName,tblcategory.CategoryFowlRun,tblcategory.CategoryCode,tblcategory.PostingDate from tblcategory  where tblcategory.id=:eid";
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
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Quantity</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="code" value="0" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
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
                                    <select id="description" style="border-radius: 8px;" name="description" style="color: #495057;" class="form-control" required>
                                        <option value="disease">Disease</option>
                                        <option value="parasites">Parasites</option>
                                        <option value="heatstress">Heat stress</option>
                                        <option value="poornutrition">Poor nutrition</option>
                                        <option value="predation">Predation</option>
                                        <option value="envfactors">Environmental factors</option>
                                        <option value="accidents">Accidents</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-6">
                        <div class="row">
                            <label class="col-sm-12 pl-0 pr-0">Cause of Death</label>
                            <textarea type="text" name="description" id="description" class="form-control" rows="22" required/>
                        </div>
                    </div> -->
                </div>
                <button type="submit" name="insert" class="btn btn-info btn-fw mr-2" style="float: left; border-radius: 8px;">Record</button>
            </form>
            <?php 
        }
    } ?>
</div>