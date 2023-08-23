<?php
// Assuming you have a database connection established
include('includes/dbconnection.php');
if(isset($_POST['farmid'])) {
  $selectedFarm = $_POST['farmid'];

  // Fetch the farm details from the database based on the selected farm ID
  $sql = "SELECT FarmAddress, FarmCity, FarmCountry FROM tbladmin WHERE FarmName = :farmname";
  $query = $dbh->prepare($sql);
  $query->bindParam(':farmname', $selectedFarm, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch(PDO::FETCH_ASSOC);

  if($result) {
      // Return the farm details as an array
      $farmDetails = array(
          'FarmAddress' => $result['FarmAddress'],
          'FarmCity' => $result['FarmCity'],
          'FarmCountry' => $result['FarmCountry']
      );

      // Convert the array to JSON and return
      echo json_encode($farmDetails);
  } else {
      echo "No farm found with the selected ID.";
  }
}
?>