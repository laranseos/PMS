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
                    
                    if($row->Status == 99) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="sp_userregister.php">
                                <span class="menu-title">Admin Panel</span>
                                <i class="mdi mdi-account-key menu-icon"></i>
                            </a>
                        </li>
                    <?php } ?>

                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
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
                    <li class="nav-item"> <a class="nav-link" href="weight.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Weight Record</a></li>
                    <?php if($_SESSION['Layer']==1) {?>
                    <li class="nav-item"> <a class="nav-link" href="product.php">Egg Record</a></li>
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
                <i class="mdi    mdi-needle menu-icon"></i>
            </a>
            <div class="collapse" id="vui-basics">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="vaccination.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Vaccination</a></li>
                    <li class="nav-item"> <a class="nav-link" href="vaccinationplan.php?cate_id=<?php echo $_SESSION['Inite'] ?>">Manage Vaccination Plan</a></li>
                </ul>
            </div>
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
                            <li class="nav-item"> <a class="nav-link" href="userregister.php">Register user </a></li> 
                        </ul>

                    </div>
                </li>
                <?php 
            } 
        ?>
    </ul>
</nav>