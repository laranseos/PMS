<?php
include('includes/checklogin.php');
check_login();
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
                  <h5 class="modal-title" style="float: left;">View Invoices</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body ">
                  <section class="hk-sec-wrapper hk-invoice-wrap pa-35">
                    <div class="invoice-from-wrap">
                      <div class="row">
                        <div class="col-md-7 mb-20">
                          <h3 class="mb-35 font-weight-600">     PFMS </h3>
                          <h6 class="mb-5">Poultry Management System</h6>
                        </div>
                        <?php 
                        //Consumer Details
                        $inid=$_SESSION['invoice'];
                        $query=mysqli_query($con,"select distinct InvoiceNumber,CustomerName,CustomerContactNo,PaymentMode,InvoiceGenDate  from tblorders  where InvoiceNumber='$inid'");
                        $cnt=1;
                        while($row=mysqli_fetch_array($query))
                        {    
                          ?>
                          <div class="col-md-5 mb-20">
                            <h4 class="mb-35 font-weight-600">Invoice / Receipt</h4>
                            <table  border="0" >
                              <tr>
                                <td><strong >Date:</strong></td>
                                <td></td>
                                <td><?php  echo htmlentities(date("d-m-Y", strtotime($row['InvoiceGenDate'])));?></td>
                              </tr>
                              <tr>
                                <td><strong >Invoice / Receipt:</strong></td>
                                <td>&nbsp;</td>
                                <td><?php echo $row['InvoiceNumber'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Customer:</strong></td>
                                <td></td>
                                <td><?php echo $row['CustomerName'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Customer Mobile No:</strong></td>
                                <td></td>
                                <td>0<?php echo $row['CustomerContactNo'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Payment Mode:</strong></td>
                                <td></td>
                                <td><?php echo $row['PaymentMode'];?></td>
                              </tr>
                            </table>
                          </div>
                          <?php
                        } ?>
                      </div>
                    </div>
                    <dir>&nbsp;</dir>
                    <div class="row">
                      <div class="card-body table-responsive p-3">
                        <div class="table-wrap">
                          <table  class="table align-items-center table-bordered " id="dataTableHover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th >Product Name</th>
                                <th>Category</th>
                                <th >Quantity</th>
                                <th >Unit Price</th>
                                <th >Price</th>

                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                              //Product Details
                              $query=mysqli_query($con,"SELECT tblproducts.CategoryName,tblproducts.ProductName,tblproducts.ProductImage,tblproducts.ProductPrice,tblorders.Quantity  from tblorders join tblproducts on tblproducts.id=tblorders.ProductId where tblorders.InvoiceNumber='$inid'");
                              $cnt=1;
                              while($row=mysqli_fetch_array($query))
                              {    
                                ?>                                                
                                <tr>
                                  <td><?php echo $cnt;?></td>
                                  <td><img src="productimages/<?php echo $row['ProductImage'];?>" class="mr-2" alt="image"><?php echo $row['ProductName'];?></td>
                                  <td><?php echo $row['CategoryName'];?></td>
                                  <td><?php echo $qty=$row['Quantity'];?></td>
                                  <td><?php echo $ppu=$row['ProductPrice'];?></td>
                                  <td><?php echo $subtotal=($ppu*$qty);?></td>
                                </tr>

                                <?php
                                $grandtotal+=$subtotal; 
                                $cnt++;
                              } ?>
                              <tr>
                                <th colspan="5" style="text-align:center; font-size:20px;">Total</th> 
                                <th style="text-align:left; font-size:20px;"><?php echo number_format($grandtotal,0);?></th>   
                              </tr>                                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </section>
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
</body>
</html>
