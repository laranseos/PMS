<?php
include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;


if(isset($_POST['taken']))
{
  $vid = $_POST['taken'];
  
  $today=date('Y-m-d');
  $fowlrun = $_POST['fowlrun'];
  $fname=$_SESSION['fname'];
  $sql="insert into tblvaccination_log(tblvaccination_log.category,tblvaccination_log.fowlrun,tblvaccination_log.vacid,tblvaccination_log.fname,tblvaccination_log.date) values(:category,:fowlrun,:vid,:fname,:today)";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
  $query-> bindParam(':today', $today, PDO::PARAM_STR);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':fowlrun',$fowlrun,PDO::PARAM_STR);
  $query->bindParam(':vid',$vid,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Logged successfully")</script>';
    echo "<script>window.location.href ='vaccination.php?cate_id=$category'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}

if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from tblfeed_log where id='$cmpid'");

  echo "<script>window.location.href='vaccination.php?cate_id=$category</script>";
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
        <div class="container">
          <div class="row">
            <?php if($_SESSION['Broiler']==1) {?>
              <div class="col-4">
                <a href="vaccination.php?cate_id=Broiler"><button class="btn btn-info btn-block custom-blue">Broiler</button></a>
              </div>
              <?php } ?> <?php if($_SESSION['Layer']==1) {?>
              <div class="col-4">
                <a href="vaccination.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
              </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
              <div class="col-4">
                <a href="vaccination.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
              </div><?php } ?> 
          </div>
        </div>
        <h2 style="text-align: center; margin-top: 20px;"><?php echo $category ?></h2>
        <div class="row">
            <select  name="fowlrun" id="fowlrun" style="min-height: 50px; width: 100%; font-size:20px;" class="form-control mr-2 text-center" required>
              <option value="" selected>Select Fowl Run</option>
                <?php
                $cate=$_SESSION['cate'];
                $fname=$_SESSION['fname'];
                $sql="SELECT * from  tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname order by tblcategory.CategoryFowlRun";
                $query = $dbh -> prepare($sql);
                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':cate',$cate,PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                
                if($query->rowCount() > 0)
                {
                  foreach($results as $rows)
                  {
                    $currentfowl =$rows->CategoryFowlRun;
                    ?> 
                    <a href="vaccination.php?cate_id='<?php echo $cate?>'"><option value="<?php  echo $currentfowl;?>"><?php  echo $rows->CategoryFowlRun;?></option></a>
                    <?php 
                  }
                } ?>
              <!-- <option value="upcoming">upcoming</option>   -->
            </select>
          </div>
        <div class="content-wrapper">
            <div>
              <div class="row" style="margin-bottom: -20px;" >
                <?php
                $cate=$_SESSION['cate'];
                $fname=$_SESSION['fname'];
                $sql="SELECT * from tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname order by tblcategory.CategoryFowlRun";

                $query = $dbh -> prepare($sql);
                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                $query-> bindParam(':cate', $cate, PDO::PARAM_STR);

                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=1;
                if($query->rowCount() > 0)
                {
                  foreach($results as $row)
                  { 

                    $c_date = $row->PostingDate;

                    $postingDate = new DateTime($c_date);
                    $today = new DateTime('today');
                    $diff = $postingDate->diff($today);

                    $fdays = $diff->format('%a');
                  
                    $sql1="SELECT * from tblvaccination where tblvaccination.category=:cate";
                    $query1=$dbh->prepare($sql1);
                    $query1->bindParam(':cate',$cate,PDO::PARAM_STR);
                    $query1->execute();
                    $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                  
                    if($query1->rowCount() > 0)
                    {  
                      $cnt = 0;
                      foreach ($results1 as $row1) {
                        $left = $row1['age'] - $fdays;
                        $vacid = $row1['id'];
                        if(-4<$left && $left<0) $cnt++;
                        if($left<-3) continue;
                        if($left>0) $cnt++;
                        
                    ?>
                    
                        <div class="col-md-12 stretch-card grid-margin card2" style="margin-left:18px; margin-bottom:10px;">
                          <?php if($cnt==1) { ?>
                          <div class="card alarm card1" style="min-height: 15vh;"> <?php } else {?>
                          <div class="card card1" style="min-height: 15vh;"> 
                          <?php } ?>
                            <div class="card-header">
                                <?php if($left<0) { ?>
                                  <h4 class="text-center"><span class="float-left"><?php  echo htmlentities(abs($left));?>DAY(S) PASSED</span><span class="text-center header" style="color: #00008b;" id="title"><?php  echo htmlentities($row->CategoryFowlRun);?></span><i  style="color: #0DCEF0;" class="mdi mdi-pin mdi-24px float-right"></i></h4>
                                <?php } else {
                                  if($cnt == 1) {
                                  ?> 
                                  <span class="upcoming">upcoming</span>
                                  <h4 class="text-center"><span class="float-left animate-charcter" style="color:red;"><?php  echo htmlentities(abs($left));?>DAYS LEFT(within <?php  echo htmlentities(intval(abs($left-1)/7)+1);?>weeks)</span><span class="text-center header" style="color: red;" id="title"><?php  echo htmlentities($row->CategoryFowlRun);?></span><i  style="color: #0DCEF0;" class="mdi mdi-pin mdi-24px float-right"></i></h4>
                                  <?php }
                                  else { ?>
                                   <h4 class="text-center"><span class="float-left animate-charcter" style="color:red;"><?php  echo htmlentities(abs($left));?>DAYS LEFT(within <?php  echo htmlentities(intval(abs($left-1)/7)+1);?>weeks)</span><span class="text-center header" style="color: red;" id="title"><?php  echo htmlentities($row->CategoryFowlRun);?></span><i  style="color: #0DCEF0;" class="mdi mdi-pin mdi-24px float-right"></i></h4>   
                                  <?php 
                                  }
                                }
                                  ?>
                            </div>
                            <div class="card-body" style="margin-bottom: -40px;">
                                <div class="row text-center">
                                  <div class="form-group col-md-2">
                                    <label>Age</label>
                                    <h4><?php  echo htmlentities($row1['age']);?></h4>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label>Disease</label>
                                    <h4><?php  echo htmlentities($row1['disease']);?></h4>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label>Vaccination</label>
                                    <h4><?php  echo htmlentities($row1['vaccination']);?></h4>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label>Dose</label>
                                    <h4><?php  echo htmlentities($row1['dose']);?></h4>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label>Method</label>
                                    <h4><?php  echo htmlentities($row1['method']);?></h4>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <form method="post" action="vaccination.php?cate_id=<?php echo $category?>">
                                      <input type="" class="text-center" name='fowlrun' readonly="readonly" value="<?php  echo htmlentities($row->CategoryFowlRun);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                      <input type="" class="text-center" name='vaccination_id' readonly="readonly" value="<?php  echo htmlentities($row1['id']);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                      <label>Check</label>
                                      <?php 
                                        $fname=$_SESSION['fname'];
                                        $sql="SELECT * from tblvaccination_log where tblvaccination_log.fowlrun=:currentfowl and tblvaccination_log.vacid=:vacid and tblvaccination_log.fname=:fname";
                                        
                                        $currentfowl = $row->CategoryFowlRun;
                                        $query = $dbh -> prepare($sql);
                                        $query->bindParam(':currentfowl',$currentfowl,PDO::PARAM_STR);
                                        $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                                        $query->bindParam(':vacid',$vacid,PDO::PARAM_STR);
                                        $query->execute();
                                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                                        
                                      
                                        if($query->rowCount() > 0)
                                        {  
                                          ?>
                                        <div class="text-center">
                                          <a href="#" data-toggle="tooltip" data-original-title="Taken" onclick="return confirm('Vaccination is already taken.');">
                                            <input type="checkbox" name="taken" style="width: 1.8em; height:1.8em;" class="taken" value=<?php  echo htmlentities($row1['id']);?> checked onclick='return false'/>&nbsp;
                                          </a>
                                        </div>
                                          <?php
                                        } else { ?>
                                            <div class="text-center">
                                              <a href="#" data-toggle="tooltip" data-original-title="Taken" onclick="return confirm('Do you took vaccination?');">
                                                <input type="checkbox" name="taken" style="width: 1.8em; height:1.8em;" class="taken align-items-center" value=<?php  echo htmlentities($row1['id']);?> onchange="this.form.submit()"/>&nbsp;
                                              </a>
                                            </div>
                                              <?php
                                        }
                                      ?>
                                    </form>
                                  </div>
                                </div> 
                            </div>
                          </div>
                        </div>
                    <?php 
                      }
                    }
                    $cnt=$cnt+1;
                  }
                } ?>
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
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_vaccination.php",
        type:"post",
        data:{edit_id4:edit_id4},
        success:function(data){
          $("#info_update4").html(data);
          $("#editData4").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_category.php",
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
<!-- <script>
  $(document).ready(function(){
  $(document).on('click','.taken',function(){
    var check= $(this).attr('id');
    window.location.href ='vaccination.php?feedid='+check;
  });
});
  
</script> -->
<script>
    const selects = document.getElementById("fowlrun");

    selects.addEventListener("change", function() {
    if (selects.selectedIndex === 0) {
        selects.style.color = "gray";  
    } else {
        selects.style.color = "black";
    }
    });
</script>
<style>
    option {
    color: gray; 
    }

    option:checked {
    color: black;
    }
</style>
<script>
  $(document).ready(function(){
    $('table.display').DataTable();
} );
</script>
<style>
    option {
    color: gray; 
    }

    option:checked {
    color: #495057;
    }
</style>
<script>
  
  document.getElementById("fowlrun").addEventListener("change", function(){

    var keyword = this.value;
    keyword = keyword.toUpperCase();
    var header = document.getElementsByClassName("header");
    
    for(var i=0; i<header.length; i++){

        var name_column = header[i];
        if(name_column){
            var name_value = name_column.textContent || name_column.innerText;
            name_value = name_value.toUpperCase();

            if(name_value.indexOf(keyword) > -1) {
                header[i].closest('.card2').style.display = ""; // show
            } else {
                header[i].closest('.card2').style.display = "none"; // hide
            }
        }
    }
});    
</script>

<script>
  
  document.getElementById("fowlrun").addEventListener("change", function(){

    var keyword = this.value;
    keyword = keyword.toUpperCase();
    if(keyword=="upcoming") {
    var header1 = document.getElementsByClassName("upcoming");
    
    for(var i=0; i<header1.length; i++){

        var name_column = header1[i];
        if(name_column) {
            var name_value = name_column.textContent || name_column.innerText;
            name_value = name_value.toUpperCase();

            if(name_value.indexOf(keyword) > -1){
                header1[i].closest('.card2').style.display = ""; // show
            } else {
                header1[i].closest('.card2').style.display = "none"; // hide
            }
        }
    }
  }
});    
</script>

<style>
.animate-charcter
{
  text-transform: uppercase;
  background-image: linear-gradient(
    -225deg,
    #000000 0%,
    #ff0000 100%
  );
  background-size: auto auto;
  background-clip: border-box;
  background-size: 200% auto;
  color: #fff;
  background-clip: text;
  text-fill-color: transparent;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: textclip 2s linear infinite;
  display: inline-block;

}

@keyframes textclip {
  to {
    background-position: 200% center;
  }
}


.alarm {
	-webkit-animation: color_change 1.5s infinite alternate;
	-moz-animation: color_change 1.5s infinite alternate;
	-ms-animation: color_change 1.5s infinite alternate;
	-o-animation: color_change 1.5s infinite alternate;
	animation: color_change 1.5s infinite alternate;
  filter: alpha(opacity=60);
}

@-webkit-keyframes color_change {
	from { background-color: #fcf5fd; }
	to { background-color: #FFCEF0; }
}
@-moz-keyframes color_change {
	from { background-color: #fcf5fd; }
	to { background-color: #FFCEF0; }
}
@-ms-keyframes color_change {
	from { background-color: #fcf5fd; }
	to { background-color: #FFCEF0; }
}
@-o-keyframes color_change {
	from { background-color: #fcf5fd; }
	to { background-color: #FFCEF0; }
}
@keyframes color_change {
	from { background-color: #fcf5fd; }
	to { background-color: #FFCEF0; }
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</body>
</html>