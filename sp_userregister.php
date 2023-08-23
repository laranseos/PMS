<?php
include('includes/checklogin.php');
check_login();

if(isset($_GET['delid']))
{

    $rid=intval($_GET['delid']);
    $sql="update tbladmin set Status='0' where ID=:rid ";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    $result = $query->execute();
    if ($query->execute()){
        echo "<script>alert('User blocked');</script>"; 
        echo "<script>window.location.href = 'sp_userregister.php'</script>";
    } else{
        echo '<script>alert("update failed! try again later")</script>';
    }
    
}

if(isset($_GET['modal_pending'])) { ?>
    <script>
       window.onload =  function ()
        {
            document.getElementById('pbtn').click();     
        }
    </script>
    <?php }

if(isset($_GET['modal_block'])) { ?>
    <script>
       window.onload =  function ()
        {
            document.getElementById('bbtn').click();     
        }
    </script>
    <?php }
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
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="modal-header">
                                <h5 class="modal-title" style="float: left;">Registered Users</h5>    
                                <div class="card-tools" style="float: right;">
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#switch" id='pbtn'></i> Switch Farm
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#add" id='pbtn'></i> Pending Users
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete" id='bbtn'></i> Blocked Users
                                    </button>
                                    <!-- <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#registeruser" ><i class="fas fa-plus" ></i> Register User
                                    </button> -->
                                </div>      
                            </div>
                            <!-- /.card-header -->

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
                                                            <label for="exampleInputName1">Fowl-Run Name</label>
                                                            <input type="text" style="border-radius: 10px;" name="frcode" value="" placeholder="Enter Fowl Run Name..." class="form-control" id="frcode"required>
                                                        </div>
                                                        </div>

                                                        <button type="submit" style="float: left; border-radius: 10px" name="save" class="btn btn-info mr-2 mb-4">Add</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                    <!-- /.modal-content -->
                            </div>

                            <div class="modal fade" id="registeruser">
                                <div class="modal-dialog ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Register User</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p>One fine body&hellip;</p> -->
                                            <?php @include("newuser_form.php");?>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            <div class="modal fade" id="delete">
                                <div class="modal-dialog modal-xl ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deleted Users</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p>One fine body&hellip;</p> -->
                                            <?php @include("sp_deleted_users.php");?>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                            <div class="modal fade" id="add">
                                <div class="modal-dialog modal-xl ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Pending Users</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <p>One fine body&hellip;</p> -->
                                            <?php @include("sp_pending_users.php");?>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!--  start  modal -->
                            <div id="editData" class="modal fade">
                                <div class="modal-dialog ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit user info</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="info_update">
                                            <?php @include("update_user.php");?>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>

                            <div id="editData5" class="modal fade">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">View User Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="info_update5">
                                            <?php @include("view_user.php");?>
                                        </div>
                                        <div class="modal-footer ">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                <!-- /.modal-dialog -->
                                </div>
                            </div>
                            <!--   end modal -->
                            <div class="card-body table-responsive p-3">
                                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Email</th>
                                            <th class="">Name</th>
                                            <th class="text-center">Farm Name</th>
                                            <th class="text-center">Mobile number</th>
                                            <th class="">Country</th>
                                            <th class="">Category Subscribed</th>
                                            <th class=" text-center">Date registered</th>
                                            <th class="text-center" style="width: 5%;">View Information</th>
                                            <th class="text-center" style="width: 5%;">Block User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql="SELECT * from tbladmin where tbladmin.Status='1' and tbladmin.AdminName='Admin' ";
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
                                                    <td><?php  echo htmlentities($row->Email);?></td>
                                                    <td><?php  echo htmlentities($row->FirstName);?>&nbsp;<?php  echo htmlentities($row->LastName);?></td>
                                                    <td class="text-center"><?php  echo htmlentities($row->FarmName);?></td>
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
                                                    <td class=" text-center">
                                                        <a href="#"  class=" edit_data5" id="<?php echo  ($row->ID); ?>" title="click to view">&nbsp;<i class="mdi mdi-eye" aria-hidden="true"></i></a>
                                                    </td>
                                                    <td class=" text-center">
                                                        <a href="sp_userregister.php?delid=<?php echo ($row->ID);?>" onclick="return confirm('Do you really want to Block ?');" title="Block this User"><i class="mdi mdi-delete fa-delete" style="color: #f05050"  aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>   
                                                    <?php $cnt=$cnt+1;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
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
    <!-- End custom js for this page -->
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_user.php",
        type:"post",
        data:{edit_id5:edit_id5},
        success:function(data){
          $("#info_update5").html(data);
          $("#editData5").modal('show');
        }
      });
    });
  });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.edit_data',function()
        {
            var edit_id=$(this).attr('id');
            $.ajax(
            {
                url:"update_user.php",
                type:"post",
                data:{edit_id:edit_id},

                success:function(data)
                {
                    $("#info_update").html(data);
                    $("#editData").modal('show');
                }

            });
        });
    });
</script>
</body>
</html>
