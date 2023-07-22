
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
  { 
    $password1=($_POST['newpassword']); 
    $password2=($_POST['confirmpassword']); 
   if($password1 != $password2)
    {
      echo "<script>alert('Password and Confirm Password Field do not match!');</script>";
    }else
    {
      $email=$_POST['email'];
      $mobile=$_POST['mobile'];
      $newpassword=md5($_POST['newpassword']);
      $sql ="SELECT Email FROM tbladmin WHERE Email=:email and MobileNumber=:mobile";
      $query= $dbh -> prepare($sql);
      $query-> bindParam(':email', $email, PDO::PARAM_STR);
      $query-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
      $query-> execute();
      $results = $query -> fetchAll(PDO::FETCH_OBJ);
      if($query -> rowCount() > 0)
      {
      $con="update tbladmin set Password=:newpassword where Email=:email and MobileNumber=:mobile";
      $chngpwd1 = $dbh->prepare($con);
      $chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
      $chngpwd1-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
      $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();
      echo "<script>alert('Your Password succesfully changed');</script>";
      }
      else {
      echo "<script>alert('Email id or Mobile no is invalid');</script>"; 
      }
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
  <?php @include("includes/head.php");?>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5" style="border-radius: 16px;">
                <div class="brand-logo " align="center" >
                  <img class="img-avatar mb-3" src="companyimages/poultrylogo.png" alt=""> <br>
                </div>
                <form class="js-validation-signin px-30" method="post" name="chngpwd" onSubmit="return valid();">
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <div class="form-material floating">
                                <label for="login-username">Email Address</label>
                                <input type="email" style="border-radius: 8px;" class="form-control" required="true" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <div class="form-material floating">
                                <label for="login-password">Mobile Number</label>
                                <input type="text" class="form-control" style="border-radius: 8px;" name="mobile" required="true" maxlength="10" pattern="[0-9]+">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <div class="form-material floating">
                                <label for="login-password">New Password</label>
                                <input class="form-control" style="border-radius: 8px;" type="password" minlength="6" name="newpassword" required="true"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-12">
                            <div class="form-material floating">
                                <label for="login-password">Confirm Password</label>
                                <input class="form-control" style="border-radius: 8px;" type="password" minlength="6" name="confirmpassword" required="true"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                    <button name="submit" style="border-radius: 8px;" type="submit" class="btn btn-block btn-info btn-lg font-weight-medium auth-form-btn">Reset</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">Do you have account? &nbsp&nbsp&nbsp<a href="index.php" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin j

      s for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>