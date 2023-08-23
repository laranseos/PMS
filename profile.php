<?php
include('includes/checklogin.php');
check_login();
if(isset($_POST['submit']))
{
  $adminid=$_SESSION['odmsaid'];
  $current_farmname=$_SESSION['current_farmname'];
  $farmname=$_POST['farmname'];
  $fName=$_POST['firstname'];
  $lName=$_POST['lastname'];
  $mobno=$_POST['mobilenumber'];
  $email=$_POST['email'];
  $sql="update tbladmin set FirstName=:firstname,LastName=:lastname,MobileNumber=:mobilenumber,Email=:email where ID=:aid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':firstname',$fName,PDO::PARAM_STR);
  $query->bindParam(':lastname',$lName,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':mobilenumber',$mobno,PDO::PARAM_STR);
  $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
  $query->execute();

  $sql1="update tbladmin set FarmName=:farmname where FarmName=:current_farmname";
  $query1 = $dbh->prepare($sql1);
  $query1->bindParam(':farmname',$farmname,PDO::PARAM_STR);
  $_SESSION['fname']=$farmname;
  $query1->bindParam(':current_farmname',$current_farmname,PDO::PARAM_STR);
  $query1->execute();
  
  echo '<script>alert("Profile has been updated")</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php @include("includes/header.php");?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php @include("includes/sidebar.php");?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $adminid=$_SESSION['odmsaid'];
                                    $sql="SELECT * from  tbladmin where ID=:aid";
                                    $query = $dbh -> prepare($sql);
                                    $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                    {
                                        foreach($results as $row)
                                        {  
                                            $_SESSION['current_farmname']=$row->FarmName;
                                            ?>
                                            <form method="post">
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-username">Permision:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="adminname" value="<?php  echo $row->AdminName;?>" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">Farm Name:</label>
                                                    <div class="col-12">
                                                    <?php   if($row->AdminName == 'User') {?>
                                                        <input type="text" class="form-control" name="farmname" value="<?php  echo $row->FarmName;?>" required='true' readonly >
                                                    <?php } else { ?>
                                                        <input type="text" class="form-control" name="farmname" value="<?php  echo $row->FarmName;?>" required='true' >
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">First Name:
                                                    </label>
                                                    <div class="col-12">
                                                     <input type="text" class="form-control" name="firstname" value="<?php  echo $row->FirstName;?>" required='true' >
                                                 </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-12" for="register1-email">Last Name:</label>
                                                <div class="col-12">
                                                   <input type="text" class="form-control" name="lastname" value="<?php  echo $row->LastName;?>" required='true' >
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                            <label class="col-12" for="register1-password">Email:</label>
                                            <div class="col-12">
                                              <input type="email" class="form-control" name="email" value="<?php  echo $row->Email;?>" required='true' >
                                          </div>
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-12" for="register1-password">Contact Number:</label>
                                        <div class="col-12">
                                         <input type="text" class="form-control" name="mobilenumber" value="0<?php  echo $row->MobileNumber;?>" required='true' maxlength='10'>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                  <label class="col-12" for="register1-password">Registration Date:</label>
                                  <div class="col-12">
                                   <input type="text" class="form-control" id="" name="" value="<?php  echo $row->AdminRegdate;?>" readonly="true">
                               </div>
                           </div>
                           <div class="control-group">
                            <label class="control-label" for="basicinput">Profile Image</label>
                            <div class="controls">
                              <?php if($row->Photo=="avatar15.jpg"){ ?>
                               <img class="" src="assets/img/avatars/avatar15.jpg" alt="" width="100" height="100">
                               <?php 
                           } else { ?>
                              <img src="profileimages/<?php  echo $row->Photo;?>" width="150" height="150">
                              <?php 
                          } ?>  
                          <a href="update_image.php?id=<?php echo $adminid;?>">Change Image</a>
                      </div>
                  </div>       
                  <?php 
              }
          } ?>
          <br>
          <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: left;">update</button>
      </form>
  </div>
</div>
</div>
</div>
</div>
<!-- content-wrapper ends -->
<!-- partial:../../partials/_footer.html -->
<?php @include("includes/footer.php");?>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<?php @include("includes/foot.php");?>
</body>
</html>