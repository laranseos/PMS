<?php 
include('includes/checklogin.php');
check_login();

?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
  <div class="container-scroller">

    <?php @include("includes/header.php");?>

    <div class="container-fluid page-body-wrapper">

      <?php @include("includes/sidebar.php");?>

      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row" style="margin-bottom: 50px;">
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 150px;">

                <div class="card-body" style="background-color: #0DCEF0; color:antiquewhite">
                  <h5 class="card-title">Users</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                      $sql ="SELECT ID from tbladmin where Status='1'";
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $totalunreadquery=$query->rowCount();
                      ?>
                      <h2><?php echo htmlentities($totalunreadquery);?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 150px;">

                <div class="card-body" style="background-color: #FF1493; color:antiquewhite">
                  <h5 class="card-title">Fowls</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $sql=mysqli_query($con,"select id from tblcategory");
                      $listedproduct=mysqli_num_rows($sql);
                      ?>
                      <h2><?php echo $listedproduct;?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 150px;">

                <div class="card-body" style="background-color: #00FA9A; color:antiquewhite">
                  <h5 class="card-title">Chickens</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory");
                      $row=mysqli_fetch_array($query);
                      ?>
                      <h2><?php echo $row['total']?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 150px;">

                <div class="card-body" style="background-color: #FF0000; color:antiquewhite">
                  <h5 class="card-title">Layers</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory where tblcategory.CategoryName='Layer'");
                      $row=mysqli_fetch_array($query);
                      ?>
                      <h2><?php echo $row['total']?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="text-left ml-4"><h2 style="color:#FF1493">Today is <?php echo date('Y-m-d');?></h2></div>
          <hr>
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color:#009999; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Feed Need(Kg)</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $sql="SELECT * from tblcategory";
                      
                      $query = $dbh -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $cnt=1;
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        { 

                          $c_code = $row->CategoryCode; 
                          $c_date = $row->PostingDate;
                          $c_fowl = $row->CategoryName;

                          $postingDate = new DateTime($c_date);
                          $today = new DateTime('today');
                          $diff = $postingDate->diff($today);

                          $fdays = $diff->format('%a');
                          
                          $sql1="SELECT tblfeed.fpd from tblfeed where tblfeed.category=:fowl and tblfeed.start<=:fdays and tblfeed.end>=:fdays";
                          $query1=$dbh->prepare($sql1);
                          $query1->bindParam(':fdays',$fdays,PDO::PARAM_STR);
                          $query1->bindParam(':fowl',$c_fowl,PDO::PARAM_STR);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          if($query1->rowCount() > 0)
                          {  
                            foreach ($results1 as $row1) {
                              $c_feed +=  $row1['fpd']*$c_code;
                            }
                          }
                        }
                      }
                      ?>
                      <h2><?php echo number_format($c_feed, 2, '.', '');?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color:#33cccc; color:antiquewhite" >
                  <h5 class="card-title" style="color:white">Eggs</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php
                      $date = date('Y-m-d');
                      
                      $sql1="SELECT sum(tblproducts.Eggcount) as total from tblproducts where tblproducts.Eggdate=:date";
                      
                      $query1=$dbh->prepare($sql1);
                      $query1->bindParam('date',$date,PDO::PARAM_STR);
                      $query1->execute();
                      $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          if($query1->rowCount() > 0)
                          {  
                            foreach ($results1 as $row1) { 
                              if($row1['total']==""){?>
                              <h2>0</h2> <?php } else {?>
                              <h2><?php echo $row1['total']?></h2><?php
                            }
                            }
                          } 
                      ?>
                      
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color: #0099ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white">Mortality Rate<i class="mdi mdi-dots-vertical-circle-outline mdi-24px float-right" style="color:aqua;" data-toggle="modal" data-target="#viewLog"></i></h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                     <?php
                      $query=mysqli_query($con,"select sum(tblcategory.CategoryCode) as total from tblcategory");
                      $row=mysqli_fetch_array($query);
                      $total = $row['total'];
                      
                      $query1=mysqli_query($con,"select sum(tblcategory_log.CategoryCount) as total from tblcategory_log");
                      $row1=mysqli_fetch_array($query1);
                      $deaths = $row1['total'];
                      
                      $m_rate=($deaths*100)/($deaths+$total);
                      ?>
                      <h2><?php echo number_format($m_rate, 2, '.', '');?>%</h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-xxl-4 col-md-3">
              <div class="card info-card sales-card" style="min-height: 160px;">

                <div class="card-body" style="background-color:#3366ff; color:antiquewhite">
                  <h5 class="card-title" style="color:white;">Vaccination Takens</h5>
                  <hr>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <?php 
                          $sql1="SELECT * from tblvaccination_log";
                          $query1=$dbh->prepare($sql1);
                          $query1->execute();
                          $results1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                          
                          $cnt = $query1->rowCount();
                      ?>
                      <h2><?php echo $cnt?></h2>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>


        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <?php @include("includes/footer.php");?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <?php @include("includes/foot.php");?>
  <script >
    $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
      {
        label               : 'Digital Goods',
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : false,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [28, 48, 40, 19, 86, 27, 90]
      },
      {
        label               : 'Electronics',
        backgroundColor     : 'rgba(200, 150, 30, 1)',
        borderColor         : 'rgba(210, 214, 222, 1)',
        pointRadius         : false,
        pointColor          : 'rgba(210, 214, 222, 1)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : [66, 59, 80, 81, 56, 55, 41]
      },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas, { 
      type: 'bar',
      data: areaChartData, 
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    var lineChartData = jQuery.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, { 
      type: 'line',
      data: lineChartData, 
      options: lineChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    
    var donutData        = {
      labels: [
      'Chrome', 
      'IE',
      'FireFox', 
      'Safari', 
      'Opera', 
      'Navigator', 
      ],
      datasets: [
      {
        data: [700,500,400,600,300,100],
        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
      }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions      
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar', 
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = jQuery.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar', 
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
// $(document).ready(function () {
//   showGraph();
// });


// function showGraph()
// {
//   {
//     $.post("data.php",
//       function (data)
//       {
//         console.log(data);
//         var name = [];
//         var marks = [];

//         for (var i in data) {
//           name.push(data[i].ServiceName);
//           marks.push(data[i].population);
//         }
//         var barChartOptions = {
//           responsive              : true,
//           maintainAspectRatio     : false,
//           datasetFill             : false,
//           scales:{
//             yAxes:[{
//                 ticks:{
//                     beginAtZero: true
//                 }
//             }]
//           }
//         }

//           var chartdata = {
//             labels: name,
//             datasets: [
//             {
//               label: 'Student Marks',
//               backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
//               borderColor: '#46d5f1',
//               hoverBackgroundColor: '#CCCCCC',
//               hoverBorderColor: '#666666',
//               data: marks
//             }
//             ]
//           };


//           var graphTarget = $("#graphCanvas");

//           var barGraph = new Chart(graphTarget, {
//             type: 'bar',
//             data: chartdata,
//             options: barChartOptions
//           });
//         });
//   }
// }


$(document).ready(function(){
  $.ajax({
    url: "data.php",
    method: "GET",
    success: function(data){
      console.log(data);
      var name = [];
      var marks = [];

      for (var i in data){
        name.push(data[i].Sector);

        marks.push(data[i].total);
      }
      var chartdata = {
        labels: name,
        datasets: [{
          label: 'student marks',
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
          borderColor: 'rgba(134, 159, 152, 1)',
          hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
          hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
          data: marks

        }]
      };
      var graphTarget = $("#graphCanvas");
      var barGraph = new Chart(graphTarget, {
        type: 'bar',
        data: chartdata,
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });
    },
    error: function(data) {
      console.log(data);
    }

  });
});

$(document).ready(function () {
  showGraph2();
});
function showGraph2()
{
  {
    $.post("data.php",
      function (data)
      {
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data) {
          name.push(data[i].Sector);
          marks.push(data[i].total);
        }

        var chartdata = {
          labels: name,
          datasets: [
          {
            label: 'Student Marks',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            // borderColor: '#46d5f1',
            hoverBackgroundColor: '#CCCCCC',
            hoverBorderColor: '#666666',
            data: marks
          }
          ]
        };

        var graphTarget = $("#graphCanvas2");

        var pieChart = new Chart(graphTarget, {
          type: 'pie',
          data: chartdata
        });
      });
  }
}

</script>

<script >
  $(document).ready(function(){
    $.ajax({
      url: "data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data){
          name.push(data[i].Sector);

          marks.push(data[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'student marks',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });



  $(document).ready(function(){
    $.ajax({
      url: "data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data){
          name.push(data[i].Sector);

          marks.push(data[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of Bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas3");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });





  $(document).ready(function(){
    $.ajax({
      url: "data1.php",
      method: "GET",
      success: function(data1){
        console.log(data1);
        var name = [];
        var marks = [];

        for (var i in data1){
          name.push(data1[i].Status);

          marks.push(data1[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas4");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });




  $(document).ready(function(){
    $.ajax({
      url: "data2.php",
      method: "GET",
      success: function(data2){
        console.log(data2);
        var name = [];
        var marks = [];

        for (var i in data2){
          name.push(data2[i].Source);

          marks.push(data2[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas5");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });



  $(document).ready(function(){
    $.ajax({
      url: "data3.php",
      method: "GET",
      success: function(data3){
        console.log(data3);
        var name = [];
        var marks = [];

        for (var i in data3){
          name.push(data3[i].Newspaper);

          marks.push(data3[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'No of Bids',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas6");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });

</script>
</body>
</html>


