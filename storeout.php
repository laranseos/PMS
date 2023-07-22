<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['save']))
{
  $item2=$_POST['item'];
  $quantity2=$_POST['quantity'];
  $sql="SELECT * from store_stock where item='$item2'";
  $query = $dbh -> prepare($sql);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
    {  
      $remaining=$row->quantity_remaining;
    }
  }
  if ($remaining>$quantity2) 
  {
    $date=$_POST['date'];
    $item=$_POST['item'];
    $quantity=$_POST['quantity'];
    $rate = $_SESSION['rate']; 
    $itemsout = ($quantity*$rate);
    $sql="insert into store_out(date,item,quantity,itemsoutvalue)values(:date,:item,:quantity,:itemsout)";
    $query=$dbh->prepare($sql);
    $query->bindParam(':date',$date,PDO::PARAM_STR);
    $query->bindParam(':item',$item,PDO::PARAM_STR);
    $query->bindParam(':itemsout',$itemsout,PDO::PARAM_STR);
    $query->bindParam(':quantity',$quantity,PDO::PARAM_STR);
    $query->execute();
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
      $quantity = $_SESSION['quantity'];
      $rate = $_SESSION['rate'];      
      $quantity2=$_POST['quantity'];
      $newqtyleft = ($quantity-$quantity2);
      $item=$_POST['item'];
      $sql3="update  store_stock set quantity_remaining=:newqtyleft where item=:item";
      $query=$dbh->prepare($sql3);
      $query->bindParam(':newqtyleft',$newqtyleft,PDO::PARAM_STR);
      $query->bindParam(':item',$item,PDO::PARAM_STR);
      $query->execute();
      $quantity = $_SESSION['quantity'];
      $rate = $_SESSION['rate'];      
      $quantity2=$_POST['quantity'];
      $remainingbalance = ($quantity-$quantity2);
      $newqtyleft = ($remainingbalance*$rate);
      $item=$_POST['item'];
      $sql4="update  store_stock set itemvalue=:newqtyleft where item=:item";
      $query=$dbh->prepare($sql4);
      $query->bindParam(':newqtyleft',$newqtyleft,PDO::PARAM_STR);
      $query->bindParam(':item',$item,PDO::PARAM_STR);
      $query->execute();
      echo '<script>alert("store_out recorded successfuly, Also your remaining items was affected")</script>';
      echo "<script>window.location.href ='store.php'</script>";
    }
    else
    {
      echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }
  }else{
    echo '<script>alert("Insufficient quantity balance, Please stock in Items")</script>';
  }
}
?>

<div class="card-body">
  <!-- Date -->

  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">

    <div class="card-body">
      <div class="form-group">
        <label>Date:</label>
        <div class="input-group date" id="reservationdate" >
          <input type="date" name="date" class="form-control " required />
        </div>
      </div>
      <div class="form-group ">
        <?php
        $eid=$_POST['edit_id'];
        $sql="SELECT * from store_stock   where id=:eid ";                                    
        $query = $dbh -> prepare($sql);
        $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);

        $cnt=1;
        if($query->rowCount() > 0)
        {
          foreach($results as $row)
          { 
            $_SESSION['quantity']=$row->quantity_remaining;   
            $_SESSION['rate']=$row->rate;     
            ?>
            <label for="exampleInputPassword1">Item</label>
            <input type="text" name="item" class="form-control" id="exampleInputPassword1"  value="<?php echo $row->item; ?>" readonly="" placeholder="item" required>
            <?php 
          }
        } ?>
      </div>
      <div class="form-group ">
        <label for="exampleInputPassword1">Quantity out of store</label>
        <input type="text" name="quantity" class="form-control" id="exampleInputPassword1" placeholder="Quantity">
      </div>
    </div>
    <div class="modal-footer text-right">
      <button type="submit" name="save" class="btn btn-primary">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    </div>
  </form>       
</div>     
<!-- /.card-body -->

