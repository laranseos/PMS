<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="card-body">
  <h3><?php  echo $_POST['edit_id'];?></h3>
  <?php
  $eid=$_POST['edit_id5'];
  $sql="SELECT * from tbladmin  where tbladmin.ID=:eid";
  $query = $dbh -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
      {?>

        <table border="1" class="table table-bordered">
          <tr>
            <th>First Name</th>
            <td><?php  echo $row->FirstName;?></td>
          </tr>
          <tr>
            <th>Last Name</th>
            <td><?php  echo $row->LastName;?></td>
          </tr>
          <tr>
            <th>Email</th>
            <td><?php  echo $row->Email;?></td>
          </tr>
          <tr>
            <th>Phone Number</th>
            <td><?php  echo $row->MobileNumber;?></td>
          </tr>
          <tr>
            <th>Address</th>
            <td><?php  echo $row->MyAddress;?></td>
          </tr>
          <tr>
            <th>City</th>
            <td><?php  echo $row->City;?></td>
          </tr>
          <tr>
            <th>Country</th>
            <td><?php  echo $row->Country;?></td>
          </tr>
          <tr>
            <th>Farm Name</th>
            <td><?php  echo $row->FarmName;?></td>
          </tr>
          <tr>
            <th>Farm Address</th>
            <td><?php  echo $row->FarmAddress;?></td>
          </tr>
          <tr>
            <th>Farm City</th>
            <td><?php  echo $row->FarmCity;?></td>
          </tr>
          <tr>
            <th>Farm Country</th>
            <td><?php  echo $row->FarmCountry;?></td>
          </tr>
        </table> 
        <?php 
      }
    } ?>
  </div>