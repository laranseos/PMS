<?php 
if(isset($_POST['switch']))
{
    $farmname=$_POST['farmid'];
    $_SESSION['fname'] = $farmname;
    echo '<script>alert("Switched into '.$farmname.' Management System!")</script>';
    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";

}

?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <?php
        $aid=$_SESSION['odmsaid'];
        $sql="SELECT * from  tbladmin where ID=:aid";
        $query = $dbh -> prepare($sql);
        $query->bindParam(':aid',$aid,PDO::PARAM_STR);
        $query->execute();

        $results=$query->fetchAll(PDO::FETCH_OBJ);
        $cnt=1;
        if($query->rowCount() > 0)
        {  
            foreach($results as $row)
            { 
                if($row->AdminName=="Admin")
                { 

                    $_SESSION['admin'] = 'true';
                    $_SESSION['admin_logo'] = '(Administrator)';
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <span class="menu-title">Dashboard</span>
                                <i class="mdi mdi-home menu-icon"></i>
                            </a>
                        </li>
                    <?php 
                } 
                else{
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_user.php">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                <?php 
                }
            }
        } ?>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Chicken Management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="category.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Category</a></li>
                    <li class="nav-item"> <a class="nav-link" href="weight.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Weight Recording</a></li>
                    <?php if($_SESSION['Layer']==1) {?>
                    <li class="nav-item"> <a class="nav-link" href="product.php">Egg Recording</a></li>
                    <?php } ?>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basics" aria-expanded="false" aria-controls="ui-basics">
                <span class="menu-title">Feed Management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-food-variant menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basics">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="feed.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Feed</a></li>
                    <?php
                    if($_SESSION['admin']=='true') { ?>
                        <li class="nav-item"> <a class="nav-link" href="feedplan.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Feed Plan</a></li>
                    <?php } ?>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#vui-basics" aria-expanded="false" aria-controls="vui-basics">
                <span class="menu-title">Vaccination Management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-needle menu-icon"></i>
            </a>
            <div class="collapse" id="vui-basics">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="vaccination.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Vaccination</a></li>
                    <?php
                    if($_SESSION['admin']=='true') { ?>
                    <li class="nav-item"> <a class="nav-link" href="vaccinationplan.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Vaccination Plan</a></li>
                    <?php } ?>
                </ul>
            </div>
        </li>

   
        <li class="nav-item">
            <a class="nav-link" href="report.php">
                <span class="menu-title">Reports</span>
                <i class="mdi mdi-file-document menu-icon"></i>
            </a>
        </li>
      
       
        <?php
            if($_SESSION['admin']=='true')
            { 
                ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages">
                        <span class="menu-title">User management</span>
                        <i class="menu-arrow"></i>
                        <i class="mdi mdi-account-multiple menu-icon"></i>
                    </a>
                    <div class="collapse" id="general-pages">

                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="userregister.php">Manage Users</a></li> 
                            <li class="nav-item"> <a class="nav-link" href="#" data-toggle="modal" data-target="#switch">Switch user</a></li> 
                        </ul>

                    </div>
                </li>
                <?php 
            } 
        ?>
    </ul>
</nav>

<div class="modal fade" id="switch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Switch System</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="col-md-12 mt-4">
                    <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row ">
                            <div class="form-group col-md-12">
                                <label for="farmid">Farm Name</label>
                                <select id="farmid" name="farmid" style="border-radius: 8px;" class="form-control">
                                    <option value="" selected disabled hidden>Select Farm</option>                                        
                                    <?php
                                    $sql="SELECT DISTINCT tbladmin.FarmName from  tbladmin where tbladmin.Status=1 ";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    
                                    if($query->rowCount() > 0)
                                    {
                                    foreach($results as $rows)
                                    {
                                        if($rows->FarmName==' ') continue;
                                        ?> 
                                        <option value="<?php  echo $rows->FarmName;?>"><?php  echo $rows->FarmName;?></option>
                                        <?php 
                                    }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" style="float: left; border-radius: 10px" name="switch" class="btn btn-info mr-2 mb-4">Switch</button>
                    </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
        <!-- /.modal-content -->
</div>