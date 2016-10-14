<?php
require_once "uq/auth.php";
auth_require();
$info = auth_get_payload();

function getRecords($email, $fav){
    // Create the connection
	$conn = new mysqli("localhost", "test", "apples", "ipw");
	if ($conn->connect_errno) {
		echo "blah";
	}
    // Prepare the statement
    // We assume the earth is flat since the function ST_DISTANCE_SPHERE is not supported very well
	$stmt = $conn->prepare("SELECT email, fav, timestamp1 FROM sub");

	if ($stmt->execute()){
			//echo "done"
	}

	$result = $stmt->get_result();
	$i = 0;
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		$data[$i] = $row;
		$i++;
	}
	return $data;
}
function getDates ($data) {
	$org = array();
	$i = 0;
	foreach ($data as $row) {
		$date = substr($row[2], 0,4). "-" .substr($row[2], 5,2)."-". substr($row[2], 8,2)." 0:00";
		if ($org[$i][0] == $date) {
			$org[$i][1]++;
		}
		else {
			$i++;
			$org[$i][0] = $date;
			$org[$i][1] = 1;

		}
	}
	return $org;

}	

function organise($data) {
	$org = array();
	$org['Plan a Trip'] = 0;
	$org['Weather Query'] = 0;
	$org['API Implementation'] = 0;
	$org['View Daily Forecasts'] = 0;
	$org['Reverse Weather Lookup'] = 0;
	$org['View Optimal Trip'] = 0;
	$org['View Tourism Information'] = 0;
	$org['Save Search Data'] = 0;
	$org['View Weather Score'] = 0;
	$org['View Recommended Trips'] = 0;
	foreach ($data as $row) {
		if ($row[1] == "Plan a Trip") {
			$org['Plan a Trip']++;
		}
		else if ($row[1] == "Weather Query") {
			$org['Weather Query']++;
		}
		else if ($row[1] == "API Implementation") {
			$org['API Implementation']++;
		}
		else if ($row[1] == "View Daily Forecasts") {
			$org['View Daily Forecasts']++;
		}
		else if ($row[1] == "Reverse Weather Lookup") {
			$org['Reverse Weather Lookup']++;
		}
		else if ($row[1] == "View Optimal Trip") {
			$org['View Optimal Trip']++;
		}
		else if ($row[1] == "View Tourism Information") {
			$org['View Tourism Information']++;
		}
		else if ($row[1] == "Save Search Data") {
			$org['Save Search Data']++;
		}
		else if ($row[1] == "View Weather Score") {
			$org['View Weather Score']++;
		}
		else if ($row[1] == "View Recommended Trips") {
			$org['View Recommended Trips']++;
		}
	}
	return $org;
}


?>

<!DOCTYPE html>
<html lang="en" style="width: 100%; height: 100%;">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>SILO/weather</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="shortcut icon" type="image/png" src="favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />




	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>
  	<nav class="navbar navbar-inverse navbar-fixed-top">
  		<div class="container">
  			<div class="navbar-header">
  				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
  					<span class="sr-only">Toggle navigation</span>
  					<span class="icon-bar"></span>
  					<span class="icon-bar"></span>
  					<span class="icon-bar"></span>
  				</button>
  				<a class="navbar-brand" id="view-home" href="/ipw/"><h3 class="logo"><b>SILO/</b><span id ="logo">weather</span></h3></a>
  			</div>
  			<div id="navbar">
  				<ul class="nav navbar-nav">
  					<li id="button"><input class="btn btn-link" id="" type="button" value="Viewing as <?php echo $info['name'] ?>"></li>                
  				</ul>

  				<ul class="nav navbar-nav navbar-right">

  					<li id="button"><a href="/ipw/"><input class="btn btn-lg btn-primary" id="" type="button" value="Back"></a></li>

  				</ul>
  			</div><!--/.navbar-collapse -->
  		</div>
  	</nav>

  	<div class="stats">
  		<div class = "col-md-8 col-md-offset-2 ">
  			
  			<div class="page-header">
  				<h1>Total Number of Responses - <?php echo sizeof(getRecords())?></h1>
  			</div>
  			<div class="page-header">
  				<h1>Favorite Features</h1>
  			</div>
  			<div id="pie8" class = "well middle" style="height:450px;width:600px;"></div>
  		</div>
  		<div class = "col-md-8 col-md-offset-2" style="margin-top:20px">
  			<div class="page-header">
  				<h1>Interest over Time</h1>
  			</div>
  			<div id="chart1" class="well middle" style="height:500px;width:800px;"></div>
  		</div>
  		<div class = "col-md-8 col-md-offset-2" style="margin-bottom:100px;margin-top:20px">
  			<div class="page-header">
  				<h1>Recent Submissions</h1>
  			</div>
  			<div class="well middle" style="height:500px;width:800px;">
  				<table class="table"> 
  					<thead> 
  						<tr> 
  							<th class="text-center">Time</th> 
  							<th class="text-center">Email</th> 
  							<th class="text-center">Favorite Feature</th> 
  						</tr> 
  					</thead> 
  					<tbody> 
  						<?php
  						$data2 = getRecords();
  						for ($i=sizeof($data2)-1; $i > sizeof($data2)-10; $i--) { 
  							echo "<tr> 
  							<td>". $data2[$i][2]."</td> 
  							<td>". $data2[$i][0]."</td> 
  							<td>". $data2[$i][1]."</td> 
  						</tr>";
  						}

  						?> 
  					</tbody> 
  				</table>
  			</div>
  			</div>
  		</div>



  		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  		<!-- Include all compiled plugins (below), or include individual files as needed -->
  		<script src="js/bootstrap.min.js"></script>
  		<script src="js/jquery.vide.js"></script>
  		<script src="js/jquery.jqplot.js"></script>
  		<script src="js/jqplot.pieRenderer.js"></script>
  		<script src="js/jqplot.barRenderer.js"></script>
  		<script src="js/jqplot.highlighter.js"></script>
  		<script src="js/jqplot.dateAxisRenderer.js"></script>



  		<script>
  			<?php  $dates = (getDates(getRecords()))?>
  			$(document).ready(function(){ 
  				<?php $array = organise(getRecords()); ?>
  				    var s1 = [['Plan a Trip', <?php echo $array['Plan a Trip'] ?>],
  				['View Recommended Trips', <?php echo $array['View Recommended Trips'] ?>],
  				['View Weather Score', <?php echo $array['View Weather Score'] ?>],
  				['Save Search Data', <?php echo $array['Save Search Data'] ?>],
  				['Reverse Weather Lookup', <?php echo $array['Reverse Weather Lookup'] ?>],
  				['View Tourism Information', <?php echo $array['View Tourism Information'] ?>],
  				['View Optimal Trip', <?php echo $array['View Optimal Trip'] ?>],
  				['View Daily Forecasts', <?php echo $array['View Daily Forecasts'] ?>],
  				['API Implementation', <?php echo $array['API Implementation'] ?>],
  				['Weather Query', <?php echo $array['Weather Query'] ?>]]
  				         
  				    var plot8 = $.jqplot('pie8', [s1], {
  					        grid: {
  						            drawBorder: false, 
  						            drawGridlines: false,
  						            background: '#ffffff',
  						            shadow:false
  					        },
  					        axesDefaults: {
  						             
  					        },
  					        seriesDefaults:{
  						            renderer:$.jqplot.PieRenderer,
  						            rendererOptions: {
  							                showDataLabels: true
  						            }
  					        },
  					        legend: {
  						            show: true,
  					        }
  				    });
  				var line1=[<?php 
  				foreach ($dates as $row) {
  					echo "['" . $row[0] ."', " . $row[1] . "],";
  				}
  				?>];
  				var plot1 = $.jqplot('chart1', [line1], {
  					axes:{
  						xaxis:{
  							renderer:$.jqplot.DateAxisRenderer,
  							tickOptions:{formatString:'%d-%m-20%y'},
  							tickInterval:'1 day',
  							label:'Date'
  						},
  						yaxis:{
  							label:'Number of expressions recieved'
  						}
  					},
  					highlighter: {
  						show: true,
  						sizeAdjust: 7.5
  					},
  					cursor: {
  						show: false
  					}
  				});
  			});
  		</script>

  	</body>
  	</html>