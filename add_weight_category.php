<?php
session_start();
include('includes/dbconnection.php');

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$category=$_SESSION['cate'];

if(isset($_POST['insertsw']))
{
    $fowlrun=$_POST['fowlrun'];
    $date=$_POST['date'];
    $eid = $_SESSION['editbid'];
    $weight=$_POST['weight'];

    $sql4="update tblcategory set tblcategory.weight=:weight, tblcategory.weightDate=:date where id=:eid";
    $query=$dbh->prepare($sql4);
    $query->bindParam(':weight',$weight,PDO::PARAM_STR);
    $query->bindParam(':date',$date,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();

    if ($query->execute())
    {
        // echo '<script>alert("Added '.$_POST['weight'].'chickens to '.$fowlrun.'")</script>';
        echo "<script>window.location.href ='category.php?cate_id=$category'</script>";
    }else{
        echo '<script>alert("Addition failed! try again later")</script>';
    }

}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id5'];
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
            $_SESSION['current_weight']=$row->weight;
            
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
                                <label for="exampleInputName1">Date</label>
                                <input type="date" style="border-radius: 10px;" name="date" placeholder="Enter Date..." class="datepicker form-control" id="birth" value="<?php echo date('Y-m-d');?>" required>
                                </div>
                            </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Weight(Kg)</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" style="border-radius: 8px;" name="weight" value=<?php echo $row->weight ?> placeholder="Enter chicken weight..." style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                  
                </div>
                <button type="submit" name="insertsw" class="btn btn-info btn-fw mr-2" style="float: left; border-radius: 8px;">Update</button>
            </form>
            <?php 
        }
    } ?>
</div>