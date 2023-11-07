
<?php

session_start();

error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login']))
{
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $sql ="SELECT * FROM tbladmin WHERE Email=:email and Password=:password";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        foreach ($results as $result) 
        {
            $_SESSION['odmsaid']=$result->ID;
            $_SESSION['login']=$result->Email;
            $_SESSION['names']=$result->FirstName;
            $_SESSION['permission']=$result->AdminName;
            $_SESSION['companyname']=$result->CompanyName;
            $get=$result->Status;
        }
        $aa= $_SESSION['odmsaid'];
        $sql="SELECT * from tbladmin  where ID=:aa";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':aa',$aa,PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        $cnt=1;
        if($query->rowCount() > 0)
        {
            foreach($results as $row)
            {            
                $_SESSION['fname']=$result->FarmName;
                $_SESSION['Free_Range']=0;
                $_SESSION['Layer']=0;
                $_SESSION['Broiler']=0;
                $role = $row->UserRole;
                if($role/100 >=1 ) {
                    $_SESSION['Free_Range'] = 1;
                    $_SESSION['Inite'] = 'Free_Range';
                }
                if(($role/10)%10 ==1 ) {
                    $_SESSION['Layer'] = 1;
                    $_SESSION['Inite'] = 'Layer';
                }
                if($role%10 ==1 ) {
                    $_SESSION['Broiler'] = 1;
                    $_SESSION['Inite'] = 'Broiler';
                }

                $inite = $_SESSION['Inite'];
                $freerange = $_SESSION['Free_Range'];
                $layer = $_SESSION['Layer'];
                $broiler =$_SESSION['Broiler'];

                // echo "<script>alert('Roles.$role');</script>";
                // echo "<script>alert('Hello.$a');</script>";
                // echo "<script>alert('broiler.$broiler');</script>";
                // echo "<script>alert('layer.$layer');</script>";
                // echo "<script>alert('freerange.$freerange');</script>";

            if($row->Status=="1")
            { 
                if($row->AdminName=="Admin")  echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>"; 
                echo "<script type='text/javascript'> document.location ='dashboard_user.php'; </script>";     
            } else if($row->Status=="0")
            { 
                echo "<script>
                alert('Your account was blocked.');document.location ='index.php';
                </script>";
            } else {
                echo "<script>
                alert('Your account is pending.');document.location ='index.php';
                </script>";
            }
        } 
    } 
} else{
    echo "<script>alert('email or password is incorrect.');</script>";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth" >
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5" style="border-radius: 16px;"> 
                            <div class="brand-logo" align="center">
                                <img class="img-avatar mb-3" src="companyimages/poultrylogo.png" alt="">
                            </div>
                            <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">  
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg"  style="border-radius: 8px;" name="email" id="exampleInputEmail1" placeholder="Email" required>
                                </div>
                                <div class="form-group mt-3">
                                    <input type="password" name="password" class="form-control form-control-lg" style="border-radius: 8px;" id="exampleInputPassword1" placeholder="Password" required>
                                </div>
                                <div class="mt-3">
                                    <button name="login" class="btn btn-block btn-info btn-lg auth-form-btn" style="border-radius: 8px;">SIGN IN</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light"> 
                                    <a href="create_account1.php"> 
                                        Create Account
                                    </a>
                                    <hr>
                                    <a href="forgot_password.php"> 
                                        Forgot Password
                                    </a>
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
    <?php @include("includes/foot.php");?>
    <!-- endinject -->
</body>
</html>