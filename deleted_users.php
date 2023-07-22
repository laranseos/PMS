<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_GET['restoreid']))
{
    $rid=intval($_GET['restoreid']);
    $sql="update tbladmin set Status='1' where ID=:rid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute()){
        echo "<script>alert('User Restored!');</script>"; 
        echo "<script>window.location.href = 'userregister.php?modal_block=true'</script>";
    }else{
        echo '<script>alert("update failed! try again later")</script>';
    }
    
}
?>
<div class="card-body table-responsive p-3">
    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Email</th>
                <th class="">Name</th>
                <th class="text-center">Mobile number</th>
                <th class="text-center">Country</th>
                <th class="">Category Subscribed</th>
                <th class=" text-center">Date registered</th>
                <th class="text-center" style="width: 15%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql="SELECT * from tbladmin where Status='0' ";
            $query = $dbh -> prepare($sql);
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
                        <td class="text-center"><?php  echo htmlentities($row->Email);?></td>
                        <td><?php  echo htmlentities($row->FirstName);?>&nbsp;<?php  echo htmlentities($row->LastName);?></td>
                        <td class="text-center">0<?php  echo htmlentities($row->MobileNumber);?></td>
                        <td><?php  echo htmlentities($row->Country);?></td>
                        <td><?php  
                        $role='';
                        if($row->UserRole%10 ==1 ) {
                            $role.='@Broiler';
                        }
                        if(($row->UserRole/10)%10 ==1 ) {
                            $role.=' @Layer';
                        }
                        if($row->UserRole/100 >=1 ) {
                            $role.=' @Free_Range';
                        }
                        echo htmlentities($role);?></td>
                        <td class="text-center">
                            <span ><?php  echo htmlentities(date("d-m-Y", strtotime($row->AdminRegdate)));?></span>
                        </td>
                            <td class="text-center"><a href="deleted_users.php?restoreid=<?php echo ($row->ID);?>" onclick="return confirm('Do you really want to Restore user ?');" title="Restore this User"><i class="mdi mdi-backup-restore" data-toggle="tooltip" data-placement="right" title="Restore this user"></i></a></td>
                        </tr>
                        <?php $cnt=$cnt+1;
                    }
                } ?>
        </tbody>
    </table>
</div>