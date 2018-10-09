<?php
	session_start();
	//database details
	$db_host = "localhost";
	$db_username = "itsyourboymo";
	$db_password = "root";
	$db_name = "";

	//connecting to 'nymbuses' database
	$con = mysqli_connect($db_host, $db_username, $db_password, $db_name);

	if(!$con) {
		die("Connection failed: " . mysqli_connect_error());
	}
	//the services array below is the main array that contains all the primary keys for every service. it's useful in finding out which routes, driver, buses are busy at certain of the day.
	$services = array();

	//this query selects all services' ids, routeIDs and their driver's ids, then added them to an array 
	$serviceSql = "SELECT services.serviceID, services.routeID, services.driverID FROM services";
	$serviceSqlResult = mysqli_query($con, $serviceSql);
	if (mysqli_num_rows($serviceSqlResult) > 0) {
		while($serviceRow = mysqli_fetch_assoc($serviceSqlResult)) {
			$serviceRow["serviceID"] = array($serviceRow["serviceID"]);
			$serviceRow["serviceID"][] = $serviceRow["routeID"];
			$serviceRow["serviceID"][] = $serviceRow["driverID"];
			$services[] = $serviceRow["serviceID"];
		}

	}
	//this sql query selects the services' busRoutes ids and bus ids that are in use 
	$busRouteSql = "SELECT busroutes.busRouteID, busroutes.routeID, busroutes.busID FROM busroutes";
		$busRouteSqlResult = mysqli_query($con, $busRouteSql);
		if (mysqli_num_rows($busRouteSqlResult) > 0) {
		while($busRouteRow = mysqli_fetch_assoc($busRouteSqlResult)) { 
			for ($i=0; $i < count($services); $i++) {
				if ($services[$i][1] == $busRouteRow["routeID"]) {
					$services[$i][] = $busRouteRow["busID"];
				}
			}
		}
	}

	//this sql query gets the ids of the routes as well as the times that they are being used
	$routeTimesSql = "SELECT routetimes.routeTimeID, routetimes.routeID, routetimes.timeID FROM routetimes";
		$routeTimesSqlResult = mysqli_query($con, $routeTimesSql);
		if (mysqli_num_rows($routeTimesSqlResult) > 0) {
		while($routeTimesRow = mysqli_fetch_assoc($routeTimesSqlResult)) { 
			for ($i=0; $i < count($services); $i++) {
				if ($services[$i][1] == $routeTimesRow["routeID"]) {
					$services[$i][] = $routeTimesRow["timeID"];
				}
			}
		}
	}

	//this query gets the actual times of the services
	$timesSql = "SELECT timess.timeID, timess.journeyStart, timess.journeyEnd FROM timess";
		$timesSqlResult = mysqli_query($con, $timesSql);
		if (mysqli_num_rows($timesSqlResult) > 0) {
		while($timesRow = mysqli_fetch_assoc($timesSqlResult)) { 
			for ($i=0; $i < count($services); $i++) {
				if ($services[$i][4] == $timesRow["timeID"]) {
					$services[$i][] = $timesRow["journeyStart"];
					$services[$i][] = $timesRow["journeyEnd"];
				}
			}
		}
	}

?>

<!-- MAIN HEADER -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<title><?php echo $pageTitle ?></title>
		<link href='https://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>

	<body>
		<!-- header for all the pages -->
		<header>
			<h1><a href="index.php" title="NYM Buses">NYM Buses</a></h1>
		</header>

		<section>

