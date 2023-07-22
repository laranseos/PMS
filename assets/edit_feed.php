<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['insert']))
{
    
    $eib= $_SESSION['editbid'];

    $gfeed=$_POST['gfeed'];
    $posting=$_POST['posting'];

    $sql4="update tblfeed_log set feed=:gfeed, posting=:posting where id=:eib";
    $query=$dbh->prepare($sql4);


    $query->bindParam(':posting',$posting,PDO::PARAM_STR);
    $query->bindParam(':gfeed',$gfeed,PDO::PARAM_STR);
    $query->bindParam(':eib',$eib,PDO::PARAM_STR);

    $query->execute();

    if ($query->execute())
    {
        echo '<script>alert("Updated Successfully!");</script>';
        
    }else{
        echo '<script>alert("Update failed! Try again later")</script>';
    }

}
?>

<div class="card-body">
    <?php
    $eid=$_POST['edit_id4'];
    $sql2="SELECT tblfeed_log.id,tblfeed_log.category,tblfeed_log.fowlrun,tblfeed_log.count,tblfeed_log.fpd, tblfeed_log.total, tblfeed_log.feed,tblfeed_log.posting from tblfeed_log  where tblfeed_log.id=:eid";
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
                                    <input type="text" name="category" id="category" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $row->category;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-sm-12 pl-0 pr-0">Fowl Run</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="fowlrun" id="fowlrun" class="form-control" readonly="readonly" style="min-width:160px;" value="<?php  echo $row->fowlrun;?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Count</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="count" value="<?php  echo $row->count;?>" readonly="readonly" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Feed Per Day</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="fpd" value="<?php  echo $row->fpd;?>" readonly="readonly" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Total Feed</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="tfeed" value="<?php  echo $row->total;?>" readonly="readonly" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Taken Feed</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                    <input type="text" name="gfeed" value="<?php  echo $row->feed;?>" style="min-width:160px;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label class="col-sm-12 pl-0 pr-0">Date</label>
                                <div class="col-sm-12 pl-0 pr-0">
                                   <input type="date" name="posting"  style="min-width:160px;" class="form-control" value="<?php echo $row->posting;?>" required>
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
