<?php
include('includes/checklogin.php');
check_login();
$category=$_GET['cate_id'];
$_SESSION['cate']=$category;


if(isset($_POST['taken']))
{
    $logid=$_POST['logid'];
    $query=mysqli_query($con,"update tblfeed_log set state='1' where id='$logid'");   
    echo "<script>window.location.href='feed.php?cate_id=$category</script>";
}

if(isset($_POST['notaken'])){    
  $cmpid=$_POST['logid'];
  $query=mysqli_query($con,"update tblfeed_log set state='0' where id='$cmpid'");   
  echo "<script>window.location.href='feed.php?cate_id=$category</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">
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
                <a href="feed.php?cate_id=Broiler"><button class="btn btn-info btn-block custom-blue">Broiler</button></a>
              </div>
              <?php } ?> <?php if($_SESSION['Layer']==1) {?>
              <div class="col-4">
                <a href="feed.php?cate_id=Layer"><button class="btn btn-success btn-block custom-green">Layer</button></a>
              </div><?php } ?> <?php if($_SESSION['Free_Range']==1) {?>
              <div class="col-4">
                <a href="feed.php?cate_id=Free_Range"><button class="btn btn-danger btn-block custom-red">Free Range</button></a>
              </div><?php } ?> 
            </div>
        </div>
        <h2 style="text-align: center; margin-top: 20px;"><?php echo $category ?></h2>
        <div class="content-wrapper">
            <div>
              <div class="row" style="margin-bottom: -20px;" >
                <?php
                $cate=$_SESSION['cate'];
                $fname=$_SESSION['fname'];
                $sql="SELECT * from tblcategory where tblcategory.CategoryName=:cate and tblcategory.fname=:fname order by tblcategory.CategoryFowlRun ASC";
                
                $query = $dbh -> prepare($sql);
                $query-> bindParam(':cate', $cate, PDO::PARAM_STR);
                $query-> bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=1;
                if($query->rowCount() > 0)
                {
                  foreach($results as $row)
                  { 
                    if($cate=='Free_Range') $c_code = $row->cocks + $row->hews;
                    else $c_code = $row->CategoryCode; 
                    $c_date = $row->PostingDate;

                    $postingDate = new DateTime($c_date);
                    $today = new DateTime('today');
                    $diff = $postingDate->diff($today);

                    $fdays = $diff->format('%a')+1;
                  
                    $sql1="SELECT tblfeed.fpd from tblfeed where tblfeed.category=:cate and tblfeed.start<=:fdays and tblfeed.end>=:fdays";
                    $query1=$dbh->prepare($sql1);
                    $query1->bindParam(':fdays',$fdays,PDO::PARAM_STR);
                    $query1->bindParam(':cate',$cate,PDO::PARAM_STR);
                    $query1->execute();
                    $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                  
                    if($query1->rowCount() > 0)
                    {  
                      foreach ($results1 as $row1) {
                        $c_feed =  $row1['fpd'];
                      }
                    }

                    $fr=$row->CategoryFowlRun;
                    $dt=date("Y-m-d");

                    $sql="SELECT * from tblfeed_log where tblfeed_log.fowlRun=:fr and tblfeed_log.posting=:dt";
                    
                    $query = $dbh -> prepare($sql);
                    $query-> bindParam(':fr', $fr, PDO::PARAM_STR);
                    $query-> bindParam(':dt', $dt, PDO::PARAM_STR);
                    $query->execute();
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $feedcheck=0;
                    if($query->rowCount() > 0)
                    { 
                      foreach ($result as $row2) {
                        $feedcheck=$row2['state'];
                        $logid =  $row2['id'];
                      }
                    } else {
                      $total = $c_feed*$c_code;
                      $fname=$_SESSION['fname'];
                      
                      $sql2="insert into tblfeed_log(tblfeed_log.category,tblfeed_log.fowlRun,tblfeed_log.count,tblfeed_log.fpd,tblfeed_log.total,tblfeed_log.posting,tblfeed_log.fname,tblfeed_log.age) values(:category,:fowlrun,:code,:fpd,:tfeed,:tdate,:fname,:age)";
                      $query2=$dbh->prepare($sql2);
                      $query2-> bindParam(':fname', $fname, PDO::PARAM_STR);
                      $query2->bindParam(':category',$cate,PDO::PARAM_STR);
                      $query2->bindParam(':fowlrun',$fr,PDO::PARAM_STR);
                      $query2->bindParam(':code',$c_code,PDO::PARAM_STR);
                      $query2->bindParam(':fpd',$c_feed,PDO::PARAM_STR);
                      $query2->bindParam(':tfeed',$total,PDO::PARAM_STR);
                      $query2->bindParam(':tdate',$dt,PDO::PARAM_STR);
                      $query2->bindParam(':age',$fdays,PDO::PARAM_STR);
                      $query2->execute();
                      $LastInsertId=$dbh->lastInsertId();
                    }
                    

                    ?>
                        <div class="col-md-3 stretch-card grid-margin" style="padding-right: 2px;">
                          <div class="card card1" style="min-height: 35vh;">
                            <div class="card-header">
                              <h4><?php  echo htmlentities(date("Y-m-d"));?><i class="mdi mdi-pin mdi-24px float-right"></i></h4>
                              <?php if($feedcheck==1){ ?>
                              <h3 class="font-weight-normal mb-3 text-center" style="color: #00008B;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3> <?php } else{?> 
                              <h3 class="font-weight-normal mb-3 text-center" style="color:crimson;"><?php  echo htmlentities($row->CategoryFowlRun);?></h3><?php } ?>
                            </div>
                            <div class="card-body">
                              <form method="post" action="feed.php?cate_id=<?php echo $category?>">
                                <input type="text" class="text-center" name='logid' readonly="readonly"  value="<?php  echo htmlentities($logid);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;   display: none;"></input>
                                <input type="text" class="text-center" name='tdate' readonly="readonly"  value="<?php  echo htmlentities(date("d-m-Y"));?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;   display: none;"></input>
                                <input type="" class="text-center" name='fowlrun' readonly="readonly" value="<?php  echo htmlentities($row->CategoryFowlRun);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                <label for="tfeed" style="color: #aaaaaa;">Age</label><input type="" class="text-center" name='age' readonly="readonly" value="<?php echo $fdays;?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <label for="code" style="color: #aaaaaa;">Chicken Count</label><input type="" class="text-center" name='code' readonly="readonly" value="<?php  echo htmlentities($c_code);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>
                                <input type="" class="text-center" name='fpd'readonly="readonly" value="<?php echo number_format($c_feed, 3, '.', '');?>" style="resize: vertical; width: 100%; border: none; border-color: transparent; display: none;"></input>
                                <label for="tfeed" style="color: #aaaaaa;">Feed per day(Kg)</label><input type="" class="text-center" name='tfeed' readonly="readonly" value="<?php echo number_format($c_feed*$c_code, 2, '.', '');?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;"></input><hr>


                                <?php if($feedcheck==1){
                                  ?>
                                  <input type="text" class="text-center" name='notaken' readonly="readonly"  value="<?php  echo htmlentities($logid);?>" style="resize: vertical; width: 100%; border: none; border-color: transparent;   display: none;"></input>
                                  <label for="fpd" style="color: #aaaaaa;">Feed Given</label>
                                  <div class="text-center">
                                    <a href="#" data-toggle="tooltip" data-original-title="Notaken" onclick="return confirm('Do you want to cancel it?');">
                                      <input type="checkbox" name="notaken" style="width: 1.8em; height:1.8em;" class="taken" value="<?php  echo $logid;?>" checked onchange='this.form.submit()'/>&nbsp;
                                    </a>
                                  </div>
                                  <?php
                                } else { ?>
                                    <label for="fpd" style="color: #aaaaaa;">Feed Given</label>
                                    <div class="text-center">
                                      <a href="#" data-toggle="tooltip" data-original-title="Taken" onclick="return confirm('Did you give feed?');">
                                        <input type="checkbox" name="taken" style="width: 1.8em; height:1.8em;" class="taken align-items-center" value=<?php  echo htmlentities($row->CategoryFowlRun);?> onchange="this.form.submit()"/>&nbsp;
                                      </a>
                                    </div>
                                      <?php
                                }
                                ?>
                              </form>
                            </div>
                          </div>
                        </div>
                    <?php 
                    $cnt=$cnt+1;
                  }
                } ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="modal-header">
                      <h5 class="modal-title">Feed History</h5>
                      <div class="d-flex flex-column flex-sm-row align-items-center ml-auto" style="float:right;">
                        <label for="fowlrun" class="mr-2">Fowl Run:</label>
                        <select  name="fowlrun" id="fowlrun" style="height: 35px; width: auto;" class="form-control mr-2" required>
                          <option value="" selected disabled hidden>Select Fowl Run</option>
                          <option value="">All</option>
                          <?php
                          $cate=$_SESSION['cate'];
                          $sql="SELECT * from  tblcategory where tblcategory.CategoryName='$cate' order by tblcategory.CategoryFowlRun";
                          $query = $dbh -> prepare($sql);
                          $query->execute();
                          $results=$query->fetchAll(PDO::FETCH_OBJ);
                          if($query->rowCount() > 0)
                          {
                            foreach($results as $row)
                            {
                              ?> 
                              <option value="<?php  echo $row->CategoryFowlRun;?>"><?php  echo $row->CategoryFowlRun;?></option>
                              <?php 
                            }
                          } ?>
                        </select>
                        <label for="from" class="mr-2">From:</label>
                        <input type="date" name="from" id="from" class="datepicker form-control input-small" style="height: 35px; width: auto;" value="<?php echo date('2023-06-01');?>" required>
                        <label for="to" class="ml-0 ml-sm-3 mr-2 mt-2 mt-sm-0">To:</label>
                        <input type="date" name="to" id="to" class="datepicker form-control input-small mr-2" style="height: 35px; width: auto;" value="<?php echo date('Y-m-d');?>" required>
                        <a href=""><i class="mdi mdi-refresh"></i><a></a>
                      </div>
                    </div>  
                  <div class="card-body table-responsive p-3">
                    <table class="display table align-items-center table-flush table-hover" id="mytable">
                      <thead>
                        <tr>
                          <th class="text-center">FowlRun</th>
                          <th class="text-center">Age</th>
                          <th class="text-center">Chicken Count</th>
                          <!-- <th class="text-center">Feed per day(Kg)</th> -->
                          <th class="text-center">Total Feed</th>
                          <th class="text-center">Feed Date</th>
                          <th class="text-center" style="width: 15%;">Feed State</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $cate=$_SESSION['cate'];
                        $fname=$_SESSION['fname'];
                        $sql="SELECT * from tblfeed_log where tblfeed_log.category=:cate and tblfeed_log.fname=:fname  ORDER BY id DESC";
                        
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
                            ?>
                            <tr>
                              <td class="text-center"><?php  echo htmlentities($row->fowlRun);?></td>
                              <td class="text-center"><?php  echo htmlentities($row->age);?></td>
                              <td class="text-center"><?php  echo htmlentities($row->count);?></td>
                              <!-- <td class="text-center"><?php echo number_format($row->fpd, 3, '.', '');?></td> -->
                              <td class="text-center"><?php echo number_format($row->total, 2, '.', '');?></td>
                              <td class="text-center"><?php  echo htmlentities(date("Y-m-d", strtotime($row->posting)));?></td>
                              <?php 
                                  $feedcheck = $row->state;
                                  if($feedcheck==1)
                                  {  
                                    ?>
                                    <td class="text-center">
                                        <input type="checkbox" name="taken" value="HI" checked onclick="return false;" readonly/>&nbsp;
                                    </td>
                                    <?php
                                  } else { ?>
                                    <td class="text-center">
                                        <input type="checkbox" name="taken" value="HI" onclick="return false;" readonly/>&nbsp;
                                    </td>
                                      <?php
                                  }
                              ?>

                            </tr>
                            <?php 
                            $cnt=$cnt+1;
                          }
                        } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
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
        url:"edit_feed.php",
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
    window.location.href ='feed.php?feedid='+check;
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
  $(document).ready(function() {
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
        var table_1 = document.getElementById("mytable");
        var all_tr = table_1.getElementsByTagName("tr");
        for(var i=0; i<all_tr.length; i++){
            var name_column = all_tr[i].getElementsByTagName("td")[0];
            if(name_column){
                var name_value = name_column.textContent || name_column.innerText;
                name_value = name_value.toUpperCase();
                // if(all_tr[i].style.display != 'none')
                // {
                  if(name_value.indexOf(keyword) > -1){
                      all_tr[i].style.display = ""; // show
                  }else{
                      all_tr[i].style.display = "none"; // hide
                  }
                // }
            }
        }
});    
</script>
<script>
function filterRows() {
  var from = $('#from').val();
  var to = $('#to').val();

  if (!from && !to) { // no value for from and to
    return;
  }

  from = from || '1970-01-01'; // default fro m to a old date if it is not set
  to = to || '2999-12-31';

  var dateFrom = moment(from);
  var dateTo = moment(to);

  $('#mytable tbody tr').each(function(i, tr) {
    var val = $(tr).find("td:nth-child(5)").text();
    var dateVal = moment(val);
    var visible = (dateVal.isBetween(dateFrom, dateTo, null, [])) ? "" : "none"; // [] for inclusive
    if($(tr).css('display') != 'none')
    {
      $(tr).css('display', visible);
    }
  });
}

$('#from').on("change", filterRows);
$('#to').on("change", filterRows);
</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</body>
</html>