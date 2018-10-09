<?php
	$pageTitle = "Welcome to NYM Buses.";
	include("inc/header.php");

	//when a user submits form to view times and stops of services
	if(isset($_POST["viewServices"])) {
		if(!empty($_POST["services"])) {
			$serviceIndexID = $_POST["services"]; //gets the value of from user's selection choice

			//the value zero is not stored in the options, so the conditional statement below helps to avoid index errors and then it gets/saves the routeID of the service from $services array in header
			if($serviceIndexID == "zero") { 
				$serviceRouteID = $services[0][1]; 
			} else {
				$serviceRouteID = $services[$serviceIndexID][1];
			}

			//the sql query below gets the id and name of the bus stops of a service and stores in array for it to be displayed
			$serviceStopsSql = "SELECT routestops.stopID, stops.stopName FROM routestops
								INNER JOIN stops ON routestops.stopID = stops.stopID
								WHERE routestops.routeID = $serviceRouteID";
			$serviceStopsSqlResult = mysqli_query($con, $serviceStopsSql);
			if (mysqli_num_rows($serviceStopsSqlResult) > 0) {
				while($serviceStopRow = mysqli_fetch_assoc($serviceStopsSqlResult)) {
					$stopNames[] = $serviceStopRow["stopName"];
				}
			}

			//this query gets id of the times, as well as the starting/ending time of the service
			$serviceTimeSql = "SELECT routetimes.timeID, timess.journeyStart, timess.journeyEnd FROM routetimes
							   INNER JOIN timess ON routetimes.timeID = timess.timeID
							   WHERE routetimes.routeID = $serviceRouteID";
			$serviceTimeSqlResult = mysqli_query($con, $serviceTimeSql);
			if (mysqli_num_rows($serviceTimeSqlResult) > 0) {
				while($serviceTimeRow = mysqli_fetch_assoc($serviceTimeSqlResult)) {
					$journeyStart = $serviceTimeRow["journeyStart"];
					$journeyEnd = $serviceTimeRow["journeyEnd"]; 
				}
			}

		}
	}

?>

<!-- HOME PAGE (FOR USERS MAINLY) -->
<div class="index">
	<p>Welcome to NYM Bus Services where you can view the different services that run in the rural area of North Yorkshire Moors area.</p>

	<form method="POST" name="indexForm">
		<select name="services">
			<option disabled selected>NYM Bus Services..</option>
			<?php
				//this sql query gets the name of the services for the user to choose one to view at a time..
				//also uses a countdown to get the index of the selected option right so that it can match its other ids that are saved in the services array (which is located in the header)
				$serviceNameSql = "SELECT services.serviceID, services.serviceName FROM services";
				$serviceNameSqlResult = mysqli_query($con, $serviceNameSql);
				$optionVal = 0;
				if (mysqli_num_rows($serviceNameSqlResult) > 0) {
					while($serviceNameRow = mysqli_fetch_assoc($serviceNameSqlResult)) {
						if ($optionVal == 0) {
							echo '<option value="zero">' . $serviceNameRow["serviceName"] . '</option>';
						} else {
							echo '<option value="' . $optionVal . '">' . $serviceNameRow["serviceName"] . '</option>';
						}
						
						$serviceNames[] = $serviceNameRow["serviceName"];
						$optionVal++;
					}

				}
			?>
		</select>

		<input type="submit" name="viewServices" value="View Service Timetable">

	</form>

	<div>
		<p>
			<?php 
				//displays user's selected option as heading for user to know which service is being viewed
				if(isset($_POST["services"])) {
					if($_POST["services"] == "zero") {
						echo $serviceNames[0];
					} else {
						echo $serviceNames[$_POST["services"]];
					}
				}
			?>
		</p>

		<ul>
			<?php
				//gets/saves the starting and ending time of the journey
				if(isset($journeyStart) && isset($journeyEnd)) {
					$nextTimeHrFormat = $journeyStart;
					$endofTimeHrFormat = $journeyEnd;
				}

				//as their is 15 minutes between each stop, the statements below calculate how a journey will be based on the amounts of stops
				if (isset($stopNames)) {
					$journeyPeriod = count($stopNames) * 15;
					$journeyPeriodTime = "+" . $journeyPeriod . " minutes";
				}


				if(isset($stopNames)) {
					//this top loop makes sure that the start time of the journey is less than the end time as the stops are being displayed on the page
					for ($t = strtotime($nextTimeHrFormat); $t < strtotime($endofTimeHrFormat); $t = strtotime($journeyPeriodTime, $t)) { 

						//this loop increases the time as the stops are being displayed to show which time a service will be at a stop
						for ($i=0; $i < count($stopNames); $i++) { 
							$nextTime = strtotime("+15 minutes", strtotime($nextTimeHrFormat));
							if ($nextTime <= strtotime("+15 minutes", strtotime($endofTimeHrFormat))) {
								echo '<li>' . $nextTimeHrFormat . " - " . $stopNames[$i] . '</li>';
								$nextTimeHrFormat = date('H:i:s', $nextTime);
							}
						}

						echo "<br><br>";

					}
				}
			?>
		</ul>
	</div>
	
</div>

<?php include("inc/footer.php"); ?>