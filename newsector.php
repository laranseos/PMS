<?php 
include('includes/dbconnection.php');
if(isset($_POST['submitexpense']))
{
  $detail=$_POST['detail'];
  $expense_category=$_POST['expense_category'];
  $sql="insert into expensecategory(categoryname,details)values(:expense_category,:detail)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':detail',$detail,PDO::PARAM_STR);
  $query->bindParam(':expense_category',$expense_category,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("successfuly has been added.")</script>';
    echo "<script>window.location.href ='manage_sector.php'</script>";
  }
  else
  {
    echo '<script>alert("Something went wrong. Please try again")</script>';
  }
}
?>
<form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">
  <div class="card-body">
    <div class="row">
      <div class="form-group col-md-12 ">
        <label for="exampleInputEmail1">Expense category </label>
        <input type="text" name="expense_category" class="form-control" id="exampleInputEmail1"value=" "  required>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-12">
        <label for="feInputCity">Details</label>
        <textarea class="form-control" name="detail" rows="5" ></textarea>
      </div>
    </div>
  </div>
  <!-- /.card-body -->
  <div class="modal-footer text-right">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" name="submitexpense" class="btn btn-primary">Submit</button>
  </div>
</form>