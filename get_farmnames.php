<?php
// Assuming you have a database connection established
include('includes/dbconnection.php');
if(isset($_GET['input'])) {
    $input = $_GET['input'];

    // Query the database to fetch distinct farm names matching the input
    $sql = "SELECT DISTINCT FarmName FROM tbladmin WHERE FarmName LIKE :input";
    $query = $dbh->prepare($sql);
    $query->bindValue(':input', '%' . $input . '%', PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    if($result) {
    // Store the farm names in an array
    $farmnames = [];
    foreach ($result as $row) {
        $farmnames[] = $row['FarmName'];
    }

    // Return the farm names as JSON
    echo json_encode($farmnames);
  } else {
    http_response_code(400);
    $error = array(
        'message' => 'Invalid request.'
    );
    echo json_encode($error);
  }

}
?>