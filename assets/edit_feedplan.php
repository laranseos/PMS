<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    
    $eib= $_SESSION['editbid'];

    $start=$_POST['start'];
    $end=$_POST['end'];
    $fpd=$_POST['fpd'];
    

    $sql4="update tblfeed set start=:start,end=:end,fpd=:fpd where tblfeed.id=:eib";
    $query=$dbh->prepare($sql4);

    $query->bindParam(':start',$start,PDO::PARAM_STR);
    $query->bindParam(':end',$end,PDO::PARAM_STR);
    $query->bindParam(':fpd',$fpd,PDO::PARAM_STR);
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
    $sql2="SELECT tblfeed.id,tblfeed.start,tblfeed.end,tblfeed.fpd,tblfeed.category from tblfeed  where tblfeed.id=:eid";
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
                                <label class="col-sm-12 pl-0 pr-0">Start Day</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="start" id="start" class="form-control" style="min-width:160px;" value="<?php  echo $row->start;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">End Day</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="end" value="<?php  echo $row->end;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Feed Per Day</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                <input type="text" name="fpd" value="<?php  echo $row->fpd;?>" style="min-width:160px;" class="form-control" id="fpd" required>
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
