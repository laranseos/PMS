<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_GET['allowid']))
{
    $rid=intval($_GET['allowid']);
    $sql="update tbladmin set Status='1' where ID=:rid ";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute()){
        echo "<script>alert('User Allowed!');</script>"; 
        echo "<script>window.location.href = 'userregister.php?modal_pending=true'</script>";
    }else{
        echo '<script>alert("Allow failed! try again later")</script>';
    }
}

if(isset($_GET['blockid']))
{
    $bid=intval($_GET['blockid']);
    $sql="delete from tbladmin where ID=:bid ";
    $query=$dbh->prepare($sql);
    $query->bindParam(':bid',$bid,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute()){
        echo "<script>alert('User Denied!');</script>"; 
        echo "<script>window.location.href = 'userregister.php?modal_pending=true'</script>";
    }else{
        echo '<script>alert("Allow failed! try again later")</script>';
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
                <th class="text-center" style="width: 10%;">Allow</th>
                <th class="text-center" style="width: 10%;">Deny</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql="SELECT * from tbladmin where Status='2' ";
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
                            <td class="text-center"><?php  echo htmlentities($row->Country);?></td>
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
                            <td class="text-center"><a href="pending_users.php?allowid=<?php echo ($row->ID);?>" onclick="return confirm('Do you allow this user?');" title="Allow this User"><i class="mdi mdi-checkbox-marked-circle-outline"></i></a></td>
                            <td class="text-center"><a href="pending_users.php?blockid=<?php echo ($row->ID);?>" onclick="return confirm('Do you want to deny this user?');" title="Deny this User"><i class="mdi mdi-cancel" style="color: red;"></i></a>
                            </td>
                        </tr>
                        <?php $cnt=$cnt+1;
                    }
                } ?>
        </tbody>
    </table>
</div>