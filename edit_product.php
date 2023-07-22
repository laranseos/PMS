<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    $eib= $_SESSION['editbid'];
    $category=$_POST['layer_run'];
    $product=$_POST['eggdate'];
    $price=$_POST['eggcount'];

    $sql4="update tblproducts set Layer_runName=:category,Eggdate=:product,Eggcount=:price where id=:eib";
    $query=$dbh->prepare($sql4);

    $query->bindParam(':category',$category,PDO::PARAM_STR);
    $query->bindParam(':product',$product,PDO::PARAM_STR);
    $query->bindParam(':price',$price,PDO::PARAM_STR);
    $query->bindParam(':eib',$eib,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute())
    {
        echo '<script>alert("updated successfuly")</script>';
    }else{
        echo '<script>alert("update failed! try again later")</script>';
    }
}
?>
<div class="card-body">
    <?php
    $eid=$_POST['edit_id4'];
    $sql2="SELECT tblproducts.id,tblproducts.Layer_runName,tblproducts.Eggdate,tblproducts.Eggcount from tblproducts where tblproducts.id=:eid";
   
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
                    <div class="form-group col-md-12">
                        <label class="col-sm-12 pl-0 pr-0">Layer Run Name</label>
                        <div class="col-sm-12 pl-0 pr-0">
                            <input type="text" name="layer_run" id="layer_run" readonly="readonly" class="form-control" value="<?php  echo $row->Layer_runName;?>" required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-sm-12 pl-0 pr-0">Posting Date</label>
                        <div class="col-sm-12 pl-0 pr-0">
                            <input type="text" name="eggdate" class="form-control" id="eggdate" readonly="readonly" placeholder="Enter Product Date" value="<?php  echo $row->Eggdate;?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-md-12 ">
                        <label class="col-sm-12 pl-0 pr-0">Egg Count</label>
                        <div class="col-sm-12 pl-0 pr-0">
                            <input type="text" name="eggcount" value="<?php  echo $row->Eggcount;?>" placeholder="Enter egg count" class="form-control" id="eggcount" required>
                        </div>
                    </div>
                </div>
                <button type="submit" name="insert" class="btn btn-primary btn-fw mr-2" style="float: left;">Update</button>
            </form>
            <?php 
        }
    } ?>
</div>