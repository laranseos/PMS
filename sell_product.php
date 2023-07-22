<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
//code for Cart
if(!empty($_GET["action"])) {
  switch($_GET["action"]) {

//code for adding product in cart
    case "add":
    if(!empty($_POST["quantity"])) {
      $pid=$_GET["pid"];
      $result=mysqli_query($con,"SELECT * FROM tblproducts WHERE id='$pid'");
      while($productByCode=mysqli_fetch_array($result)){
        $itemArray = array($productByCode["id"]=>array('catname'=>$productByCode["CategoryName"], 'compname'=>$productByCode["CompanyName"], 'quantity'=>$_POST["quantity"], 'pname'=>$productByCode["ProductName"],'image'=>$productByCode["ProductImage"], 'price'=>$productByCode["ProductPrice"],'code'=>$productByCode["id"]));
        if(!empty($_SESSION["cart_item"])) {
          if(in_array($productByCode["id"],array_keys($_SESSION["cart_item"]))) {
            foreach($_SESSION["cart_item"] as $k => $v) {
              if($productByCode["id"] == $k) {
                if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                  $_SESSION["cart_item"][$k]["quantity"] = 0;
                }
                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
              }
            }
          } else {
            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
          }
        }  else {
          $_SESSION["cart_item"] = $itemArray;
        }
      }
    }
    break;


    // code for removing product from cart
    case "remove":
    if(!empty($_SESSION["cart_item"])) {
      foreach($_SESSION["cart_item"] as $k => $v) {
        if($_GET["code"] == $k)
          unset($_SESSION["cart_item"][$k]);              
        if(empty($_SESSION["cart_item"]))
          unset($_SESSION["cart_item"]);
      }
    }
    break;
    // code for if cart is empty
    case "empty":
    unset($_SESSION["cart_item"]);
    break;  
  }
}

//Code for Checkout
if(isset($_POST['checkout'])){
  $invoiceno= mt_rand(100000000, 999999999);
  $pid=$_SESSION['productid'];
  $quantity=$_POST['quantity'];
  $cname=$_POST['customername'];
  $cmobileno=$_POST['mobileno'];
  $pmode=$_POST['paymentmode'];
  $value=array_combine($pid,$quantity);
  foreach($value as $pdid=> $qty){
    $query=mysqli_query($con,"insert into tblorders(ProductId,Quantity,InvoiceNumber,CustomerName,CustomerContactNo,PaymentMode) values('$pdid','$qty','$invoiceno','$cname','$cmobileno','$pmode')") ; 
  }
  echo '<script>alert("Invoice generated successfully. Invoice number is "+"'.$invoiceno.'")</script>';  
  unset($_SESSION["cart_item"]);
  $_SESSION['invoice']=$invoiceno;
  echo "<script>window.location.href='invoice.php'</script>";

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
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="table-responsive p-3">
                  <table id="datable_1" class="table table-hover w-100 display pb-30">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Product Category</th>
                        <th>Pricing</th>
                        <th>Quantity</th>
                        <th>Action</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query=mysqli_query($con,"select * from tblproducts ");
                      $cnt=1;
                      while($row=mysqli_fetch_array($query))
                      {    
                        ?>
                        <form method="post" action="sell_product.php?action=add&pid=<?php echo $row["id"]; ?>">                                                  
                          <tr>
                            <td><?php echo $cnt;?></td>
                            <td> <img src="productimages/<?php  echo $row['ProductImage'];?>" class="mr-2" alt="image"><?php echo $row['ProductName'];?></td>
                            <td><?php echo $row['CategoryName'];?></td>
                            <td><?php echo $row['ProductPrice'];?></td>
                            <td><input type="text" class="product-quantity" name="quantity" value="1" size="2" /></td>
                            <td>
                              <input type="submit" value="Add to Cart" class="btnAddAction" />
                            </td>
                          </tr>
                        </form>
                        <?php 
                        $cnt++;
                      } ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="table-responsive p-3">
                  <form class="needs-validation" method="post" novalidate>
                   <h4>Shopping Cart</h4>
                   <hr />

                   <a id="btnEmpty" href="sell_product.php?action=empty" >Empty Cart</a>
                   <?php
                   if(isset($_SESSION["cart_item"])){
                    $total_quantity = 0;
                    $total_price = 0;
                    ?>  
                    <table class="table align-items-center table-bordered" >
                      <tbody>
                        <tr>
                          <th >Product Name</th>
                          <th>Category</th>
                          <th>Company</th>
                          <th >Quantity</th>
                          <th >Unit Price</th>
                          <th >Price</th>
                          <th >Remove</th>
                        </tr>   
                        <?php 
                        $productid=array();      
                        foreach ($_SESSION["cart_item"] as $item){
                          $item_price = $item["quantity"]*$item["price"];
                          array_push($productid,$item['code']);

                          ?>
                          <input type="hidden" value="<?php echo $item['quantity']; ?>" name="quantity[<?php echo $item['code']; ?>]">
                          <tr>
                            <td><img src="productimages/<?php  echo  $item["image"];?>" class="mr-2" alt="image"><?php echo $item["pname"]; ?></td>
                            <td><?php echo $item["catname"]; ?></td>
                            <td><?php echo $item["compname"]; ?></td>
                            <td><?php echo $item["quantity"]; ?></td>
                            <td><?php echo $item["price"]; ?></td>
                            <td><?php echo number_format($item_price,2); ?></td>
                            <td><a href="sell_product.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"> <i class="mdi mdi-close-circle" style="font-size: 25px;"></i> </a></td>
                          </tr>
                          <?php
                          $total_quantity += $item["quantity"];
                          $total_price += ($item["price"]*$item["quantity"]);
                        }
                        $_SESSION['productid']=$productid;
                        ?>

                        <tr>
                          <td colspan="3" align="right">Total:</td>
                          <td colspan="2"><?php echo $total_quantity; ?></td>
                          <td colspan=><strong><?php echo number_format($total_price, 2); ?></strong></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table> 
                    <div class="form-row">
                      <div class="col-md-6 mb-10">
                        <label for="validationCustom03">Customer Name</label>
                        <input type="text" class="form-control" id="validationCustom03" placeholder="Customer Name" name="customername" required>
                        <div class="invalid-feedback">Please provide a valid customer name.</div>
                      </div>
                      <div class="col-md-6 mb-10">
                        <label for="validationCustom03">Customer Mobile Number</label>
                        <input type="text" class="form-control" id="validationCustom03" placeholder="Mobile Number" name="mobileno" required>
                        <div class="invalid-feedback">Please provide a valid mobile number.</div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="col-md-6 mb-10">
                        <label for="validationCustom03">Payment Mode</label>
                        <div class="custom-control custom-radio mb-10">
                          <input type="radio" class="custom-control-input" id="customControlValidation2" name="paymentmode" value="cash" required>
                          <label class="custom-control-label" for="customControlValidation2">Cash</label>
                        </div>
                        <div class="custom-control custom-radio mb-10">
                          <input type="radio" class="custom-control-input" id="customControlValidation3" name="paymentmode" value="card" required>
                          <label class="custom-control-label" for="customControlValidation3">Card</label>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4 ">
                        <button class="btn btn-primary mt-6" type="submit" name="checkout">Checkout</button>
                      </div>
                    </div>
                  </form>
                  <?php
                } else {
                  ?>
                  <div style="color:red" align="center">Your Cart is Empty</div>
                  <?php 
                }
                ?>

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
<style type="text/css">
  #btnEmpty {
    background-color: #ffffff;
    border: #d00000 1px solid;
    padding: 5px 10px;
    color: #d00000;
    float: right;
    text-decoration: none;
    border-radius: 3px;
    margin: 10px 0px;
  }

</style>
<script type="text/javascript">
  /*Validation Init*/

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
</body>
</html>