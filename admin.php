<?php
	$pageTitle = "Welcome to NYM Buses Admin Area.";
	include("inc/header.php"); 

	//relocates user if session is not set as admin
	if (!isset($_SESSION['admin_id'])) {
		header('Location: index.php');
	}



	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		//a form is built on the admin page based on what the admin wants to add to the system
		if(isset($_POST["displayForm"])) {

			if(!empty($_POST["toBeAdded"])) {


				if($_POST["toBeAdded"] == "Driver") { //a driver's to add new driver form when admit selects driver
					
					$toBeAddedInput =  '<p>Driver Form</p>';
					$toBeAddedInput .= '<input type="text" name="title" placeholder="Title..">';
					$toBeAddedInput .= '<input type="text" name="firstName" placeholder="First name..">';
					$toBeAddedInput .= '<input type="text" name="lastName" placeholder="Last name..">';
					$toBeAddedInput .= '<input type="text" name="email" placeholder="Email..">';
					$toBeAddedInput .= '<input type="number" name="phone" placeholder="Phone number..">';
					$toBeAddedInput .= '<input type="number" name="houseNo" placeholder="House number..">';
					$toBeAddedInput .= '<input type="text" name="streetName" placeholder="Street name..">';
					$toBeAddedInput .= '<input type="text" name="postCode" placeholder="Post code..">';
					$toBeAddedInput .= '<input type="submit" name="addDriver" value="Add Driver">';


				} elseif ($_POST["toBeAdded"] == "Bus") { //to add a new bus
					
					$toBeAddedInput =  '<p>Bus Form</p>';
					$toBeAddedInput .= '<input type="number" name="noPassengers" placeholder="Number of passengers..">';
					$toBeAddedInput .= '<select name="busType">
											<option disabled selected>Bus type..</option>
											<option value="doubleDecker">Double Decker</option>
											<option value="singleDecker">Single Decker</option>
										</select>';
					$toBeAddedInput .= '<input type="submit" name="addBus" value="Add Bus">';

				} elseif($_POST["toBeAdded"] == "Route") { //to add a new route, gets the number of stops first

					$toBeAddedInput =  '<p>Route Form (Stops)</p>';
					$toBeAddedInput .= '<input type="number" name="noOfStops" placeholder="Number Of Stops">';
					$toBeAddedInput .= '<input type="submit" name="getBusStops" value="Get Bus Stops">';

				} elseif ($_POST["toBeAdded"] == "Stop") { //to add a new bus stop form
					
					$toBeAddedInput =  '<p>Stop Form</p>';
					$toBeAddedInput .= '<input type="text" name="stopName" placeholder="Stop Name">';
					$toBeAddedInput .= '<input type="submit" name="addStop" value="Add Stop">';
				}

			} else {
				$errorMessage = "Please select driver, bus, route or service to add.";
			}

		}





		//when admins add new driver
		if(isset($_POST["addDriver"])) {
			//gets all the inputs
			$title = $_POST["title"];
			$firstName = $_POST["firstName"];
			$lastName = $_POST["lastName"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$houseNo = $_POST["houseNo"];
			$streetName = $_POST["streetName"];
			$postCode = $_POST["postCode"];

			//makes sure all required fields are entered
			if(!empty($title) && !empty($firstName) && !empty($lastName) && !empty($email) && !empty($phone) && !empty($houseNo) && !empty($streetName) && !empty($postCode)) {
			
				//firstly, inserts drivers address as its tables doesn't have/need a foreign key column
				$addressSql = "INSERT INTO addresses (doorNo, streetName, postCode) VALUES ('$houseNo', '$streetName', '$postCode')";

				if (mysqli_query($con, $addressSql)) {
					$lastAddressID = mysqli_insert_id($con); //gets this foreign to add drivers table
				} else {
				    echo "Error: " . $addressSql . mysqli_error($con);
				}

				//seconly, inserts driver's details including address which is a foreign key from the address table
				$driverSql = "INSERT INTO drivers (title, firstName, lastName, email, phone, addressID) VALUES ('$title', '$firstName', '$lastName', '$email', '$phone', '$lastAddressID')";

				if (mysqli_query($con, $driverSql)) {
					$successMessage = $title . " " . $lastName . "'s new record has been successfully added.";
				} else {
				    echo "Error: " . $driverSql. mysqli_error($con);
				}


				// mysqli_close($con);

			} else {
				$errorMessage = "Please fill all fields.";
			}
		}




		//to add a new bus
		if(isset($_POST["addBus"])) {

			//makes sure all fields are filled
			if(!empty($_POST["noPassengers"]) && !empty($_POST["busType"])) {

				$noPassengers = $_POST["noPassengers"];
				$busType = $_POST["busType"];

				//inserts no of passengers as well as the type of bus to determine which roads a bus is allowed on
				$busSql = "INSERT INTO buses(NoPassengers, BusType) VALUES('$noPassengers', '$busType')";

				if(mysqli_query($con, $busSql)) {
					$successMessage = "A " . $busType . " bus of " . $noPassengers . " passenger capacity has been successfully added.";
				} else {
					echo "Error: " . $busSql . mysqli_error($con);
				}

				// mysqli_close($con);

			} else {
				$errorMessage = "Please fill all fields.";
			}
		}


		//this is a continuation of the route form after admin enters the amount of stops he would like to add with a route
		if(isset($_POST["getBusStops"])) {
			$noOfStops = $_POST["noOfStops"];

			//building of the form
			if(!empty($noOfStops)) {
				$toBeAddedInput =  '<p>Route Form</p>';
				$toBeAddedInput .= '<input type="text" name="routeName" placeholder="Route Name">';
				$toBeAddedInput .= '<select name="bridgeType">
										<option disabled selected>Does it have a bridge?</option>
										<option value="no">No</option>
										<option value="yes">Yes</option>
									</select>';

				//loops for the input of bus stops based on admin's entry
				//the stops are in select element so that the admin will only add stops that part of the nym bus system
				for ($i=0; $i < $noOfStops; $i++) { 

					$toBeAddedInput .= '<select name="busStop[' . $i . ']">';
					$stopNo = $i + 1;
					$toBeAddedInput .= '<option disabled selected>Stop ' . $stopNo .'..</option>';

					$stopSql = "SELECT stopID, stopName FROM stops";
					$stopSqlResult = mysqli_query($con, $stopSql);
					if (mysqli_num_rows($stopSqlResult) > 0) {
						while($stopSqlRow = mysqli_fetch_assoc($stopSqlResult)) {

							$toBeAddedInput .= '<option value="' . $stopSqlRow["stopID"] . '">' . $stopSqlRow["stopName"] . '</option>';
					    }
					}

					$toBeAddedInput .= '</select>';

				}

				mysqli_close($con);

				$toBeAddedInput .= '<input type="submit" name="addRoute" value="Add Route">';

			} else {
				$errorMessage = "Please fill all fields.";
			}


		}




		//when admin adds a new route
		if(isset($_POST["addRoute"])) {
			$routeName = $_POST["routeName"];

			if(!empty($routeName) && !empty($_POST["bridgeType"]) && !empty($_POST['busStop'])) {

				$bridgeType = $_POST["bridgeType"];

				//to insert the route name and its type (i.e. whether it has a bridge or not to determine which buses can go on it)
				$routeSql = "INSERT INTO routes (routeName, bridge) VALUES ('$routeName', '$bridgeType')";

				if (mysqli_query($con, $routeSql)) {
					$lastRouteID = mysqli_insert_id($con); //gets the id of the route
				} else {
				    $errorMessage = "Error: " . $routeSql . mysqli_error($con);
				}

				//loops through the input of the bus stops and inserts them into the routesstops table with the id of the route route that the user has entered to keep them related..
				foreach ($_POST['busStop'] as $checkedBusStop) {

					$routeStopSql = "INSERT INTO routestops(stopID, routeID) VALUES('$checkedBusStop', '$lastRouteID')";

					if (mysqli_query($con, $routeStopSql)) {
						$successMessage = "The route '" . $routeName . "' has been successfully added with few other bus stops." ;
					} else {
					    $errorMessage = "Error: " . $routeStopSql . mysqli_error($con);
					}
				}

				// mysqli_close($con);
			} else {
				$errorMessage = "Please fill all fields.";
			}

		}


		//to add a new bus stop
		if(isset($_POST["addStop"])) {
			$stopName = $_POST["stopName"];
			if(!empty($stopName)) {
				//inserts stop name.. TO BE IMPROVED, ASK FOR NUMBER OF STOPS FIRST
				$stopSql = "INSERT INTO stops(stopName) VALUES('$stopName')";

				if (mysqli_query($con, $stopSql)) {
					$successMessage = "'" . $stopName . "'" . " has been successfully added to bus stops." ;
				} else {
				    $errorMessage = "Error: " . $stopSql . mysqli_error($con);
				}

				// mysqli_close($con);
			} else {
				$errorMessage = "Please fill all fields.";
			}
		}









		//when admin is trying to add a new service
		if(isset($_POST["displayServices"])) { 

			//gets the start and end time first to make sure that only available routes, buses and drivers to displayed for admin to select
			//gets bus type too to make sure that only certain routes are displayed based on user's choice of input
			$start = strtotime($_POST["startTime"]);
			$end = strtotime($_POST["endTime"]);


			if(!empty($start) && !empty($end) && !empty($_POST["typeOfBus"])) {

				//makes sure that the entry of time is right as bus will be operting during the day
				if($end > $start) {

					//array to get buses, routes and drivers that are already in use from the main service array
			  		$busyDrivers = array();
					$busyRoutes = array();
					$busyBuses = array();

					//this loop goes through the times of every service, then makes sure that the drivers, routes and buses that are busy during the time that the admin is trying to add a service; are stored in specific arrays.. This means all the busy drivers, routes and buses at the moment (admin's time entry for new service) are saved
					for ($i=0; $i < count($services); $i++) { 
							$serviceStart = strtotime($services[$i][5]);
						    $serviceEnd = strtotime($services[$i][6]);

							if(($start >= $serviceStart && $start <= $serviceEnd) || ($end >= $serviceStart && $end <= $serviceEnd) || ($start <= $serviceStart && $end >= $serviceEnd)) {
		   						$busyDrivers[] = $services[$i][2];
		   						$busyRoutes[] = $services[$i][1];
		   						$busyBuses[] = $services[$i][3];
		   					} 
					}


					//building of the service entry form
			  		$toBeAddedServices =  '<p>Service Form</p>';
					$toBeAddedServices .= '<input type="number" name="serviceName" placeholder="Service Name">';


					$toBeAddedServices .= '<select name="availableDrivers">
											<option disabled selected>Drivers</option>';

					//get's driver details for driver input selection..
					$driverSql = "SELECT driverID, firstName, lastName FROM drivers";
					$driverResult = mysqli_query($con, $driverSql);
				    
					//then checks, if driver is not in the busy array, he/she can be displayed and be added to new service
		   			if (mysqli_num_rows($driverResult) > 0) {
				   		while($row = mysqli_fetch_assoc($driverResult)) {
				   			if(!in_array($row["driverID"], $busyDrivers)) {
		 						$toBeAddedServices .= '<option value=' . $row["driverID"] . '>' . $row["firstName"] . " " . $row["lastName"] .'</option>';
		 					}
				   		}

				    }

				    $toBeAddedServices .= '</select>';





					$toBeAddedServices .= '<select name="availableRoutes">
											<option disabled selected>Routes</option>';

					//gets the routes details for input selection
					$routeSqlSelect = "SELECT routeID, routeName, bridge FROM routes";
					$routeResult = mysqli_query($con, $routeSqlSelect);

					//makes sure that a route is not displayed if it alread in use plus makes sure that the route is suitable for the time of bus that the user has entered
					if (mysqli_num_rows($routeResult) > 0) {
					    while($row = mysqli_fetch_assoc($routeResult)) {
					    	if(!in_array($row["routeID"], $busyRoutes)) {
					    		if($_POST["typeOfBus"] == "doubleDecker") {
					    			if($row["bridge"] == "no") {
					    				$toBeAddedServices .= '<option value=' . $row["routeID"] . '>' . $row["routeName"] .'</option>'; 
					    			}
					    		} elseif ($_POST["typeOfBus"] == "singleDecker") {
					    			$toBeAddedServices .= '<option value=' . $row["routeID"] . '>' . $row["routeName"] .'</option>'; 
					    		}
								
							}
					    }
					}

					$toBeAddedServices .= '</select>';




					//gets the buses
					$toBeAddedServices .= '<select name="availableBuses">
												<option disabled selected>Buses</option>';

					$busSqlSelect = "SELECT busID, NoPassengers, busType FROM buses";
					$busResult = mysqli_query($con, $busSqlSelect);

					//makes sure only free buses(not in busyBuses array) are displayed and meet the user's early entry demand which is either a double/single decker bus
					if (mysqli_num_rows($busResult) > 0) {

					    while($row = mysqli_fetch_assoc($busResult)) {
					    	if(!in_array($row["busID"], $busyBuses)) {
					    		if($_POST["typeOfBus"] == "doubleDecker") {
					    			if($row["busType"] == "doubleDecker") {
										$toBeAddedServices .= '<option value=' . $row["busID"] . '>'. $row["NoPassengers"] . " passengers" .'</option>'; 
									}
								} elseif ($_POST["typeOfBus"] == "singleDecker") {
									if($row["busType"] == "singleDecker") {
										$toBeAddedServices .= '<option value=' . $row["busID"] . '>'. $row["NoPassengers"] . " passengers" .'</option>'; 
									}
								}
							}
					    }
					}

					$toBeAddedServices .= '</select>';

					$toBeAddedServices .= '<input type="submit" name="addService" value="Add Service">';


				} else {
					$errorMessageServices = "Please add an end time that is later than the start time.";
				}
			} else {
				$errorMessageServices = "Please fill all fields.";
			}
		
		}


		//when admin attempts to add a new service
		if(isset($_POST["addService"])) {
			if(!empty($_POST["serviceName"]) && !empty($_POST["availableDrivers"]) && !empty($_POST["availableRoutes"]) && !empty($_POST["availableBuses"])) {

				//gets all the services names that have already been taken - to avoid future duplication
				$takenServiceNames = array();
				$serviceNamesSql = "SELECT services.serviceName From services";
				$serviceNamesResult = mysqli_query($con, $serviceNamesSql);
	   			if (mysqli_num_rows($serviceNamesResult) > 0) {
		    		while($serviceNameRow = mysqli_fetch_assoc($serviceNamesResult)) {
		    			$takenServiceNames[] = $serviceNameRow["serviceName"];
	   				}
	   			}

	   			//stops admin from adding new service name if it is already in use on the nym bus service
	   			if(in_array($_POST["serviceName"], $takenServiceNames)) {
	   				$errorMessageServices = "Sorry, the service name " . $_POST["serviceName"] . " is already in use. Please try another one.";
	   			} else {
	   				//else stores all admin's entries
	   				$newServiceStart = $_POST["startTime"];
	   				$newServiceEnd = $_POST["endTime"];
	   				$newServiceName = $_POST["serviceName"];
	   				$newServiceDriver = $_POST["availableDrivers"];
	   				$newServiceRoute = $_POST["availableRoutes"];
	   				$newServiceBus = $_POST["availableBuses"];

	   				// times - inserts start and end time
					$newServiceTimeSql = "INSERT INTO timess (journeyStart, journeyEnd) VALUES ('$newServiceStart', '$newServiceEnd')";

					if (mysqli_query($con, $newServiceTimeSql)) {
						$newServicetimeID = mysqli_insert_id($con); //gets timeID to add to routeTimes (many-to-many relationship)
					} else {
					    $errorMessageServices = "Error: " . $newServiceTimeSql . mysqli_error($con);
					}
	   				
	   				// routetimes - routeID, timeID
	   				$newServiceRouteSql = "INSERT INTO routetimes (routeID, timeID) VALUES ('$newServiceRoute', '$newServicetimeID')";
	   				mysqli_query($con, $newServiceRouteSql);

	   				// busroutes - busID, routeID
	   				$newServiceBusSql = "INSERT INTO busroutes (busID, routeID) VALUES ('$newServiceBus', '$newServiceRoute')";
	   				mysqli_query($con, $newServiceBusSql);

	   				// services - serviceName, routeID, driverID
	   				$newServiceServiceSql = "INSERT INTO services (serviceName, routeID, driverID) VALUES ('$newServiceName', '$newServiceRoute', '$newServiceDriver')";
	   				mysqli_query($con, $newServiceServiceSql);

	   				//displays success message after inserting all new service entries
	   				$successMessageServices = $newServiceName . " service has been added. It will be operating from " . $newServiceStart . " to " . $newServiceEnd . ".";
	   			}
	 
			} else {
				$errorMessageServices = "Please fill all fields.";
			}
		}


		//when admin attempts to delete a service
		if(isset($_POST["deleteService"])) {
			if(!empty($_POST["serviceToDelete"])) {
				//gets the admin's selected option and breaks down using explode to remove the relationship between the multiple tables
				$serviceToDeleteID_RouteID = $_POST["serviceToDelete"];
				$serviceToDeleteID_RouteID = explode('|', $serviceToDeleteID_RouteID );
				$serviceToDeleteID = $serviceToDeleteID_RouteID[0];
				$serviceToDeleteRouteID = $serviceToDeleteID_RouteID[1];
				$serviceToDeleteName = $serviceToDeleteID_RouteID[2];

				//removes the service from service table
				$serviceToDeleteIDSql = "DELETE FROM services WHERE services.serviceID = $serviceToDeleteID";
				mysqli_query($con, $serviceToDeleteIDSql);

				//removes the relationship(many to many) between the bus and routes (busroutes)
				$serviceToDeleteBusRouteIDSQL = "DELETE FROM busroutes WHERE busroutes.routeID = $serviceToDeleteRouteID";
				mysqli_query($con, $serviceToDeleteBusRouteIDSQL);

				//removes the relationship between the routes and the time
				$serviceToDeleteRouteTimeIDSQL = "DELETE FROM routetimes WHERE routetimes.routeID = $serviceToDeleteRouteID";
				mysqli_query($con, $serviceToDeleteRouteTimeIDSQL);

				//to delete times from timess table
				// $serviceTimeSql = "SELECT routetimes.timeID FROM routetimes WHERE routetimes.routeID = $serviceToDeleteRouteID";
				// $serviceTimeSqlSqlResult = mysqli_query($con, $serviceTimeSql);
				// if (mysqli_num_rows($serviceTimeSqlSqlResult) > 0) {
				// 	while($serviceTimeIDRow = mysqli_fetch_assoc($serviceTimeSqlSqlResult)) {
				// 		$serviceToDeleteTimeID = $serviceTimeIDRow["timeID"];
				// 	}
				// }

				// $serviceToDeleteTimesIDSQL = "DELETE FROM timess WHERE timess.timeID = $serviceToDeleteTimeID";
				// mysqli_query($con, $serviceToDeleteTimesIDSQL);

			
				$successMessageDelete = "The " . $serviceToDeleteName . " service has been deleted";

			} else {
				$errorMessageDelete = "Please fill all fields.";
			}
		}
		
	}

?>

<!-- ADMIN PAGE -->
<div class="admin cf">
	<!-- To add a driver, bus, route or stop -->
	<div>
		<p>Adding Form</p>
		<form method="POST" name="addingForm">
			<p class="errorMessage"><?php if (isset($errorMessage)) { echo $errorMessage; } ?></p>
			<p class="successMessage"><?php if (isset($successMessage)) { echo $successMessage; } ?></p>
			<select name="toBeAdded">
				<option disabled selected>Driver, Bus, Route or Stop.. </option>
				<option value="Driver">Driver</option>
				<option value="Bus">Bus</option>
				<option value="Route">Route</option>
				<option value="Stop">Stop</option>
			</select>
			<input type="submit" name="displayForm" value="Display Form">
			<?php if (isset($toBeAddedInput)) { echo $toBeAddedInput; } ?>

		</form>
	</div>

	<!-- to add a new service -->
	<div>
		<p>Adding Service</p>
		<form method="POST" method="addingService">
			<p class="errorMessage"><?php if (isset($errorMessageServices)) { echo $errorMessageServices; } ?></p>
			<p class="successMessage"><?php if (isset($successMessageServices)) { echo $successMessageServices; } ?></p>
			<input type="time" name="startTime" value="<?php if(isset($_POST["startTime"])) { echo $_POST["startTime"]; } ?>">
			<input type="time" name="endTime" value="<?php if(isset($_POST["endTime"])) { echo $_POST["endTime"]; } ?>">
			<select name="typeOfBus">
				<option disabled selected>What type of Bus?</option>
				<option value="doubleDecker">Double Decker</option>
				<option value="singleDecker">Single Decker</option>
			</select>
			<input type="submit" name="displayServices" value="Get Available Services">
			<?php if (isset($toBeAddedServices)) { echo $toBeAddedServices; } ?>
		</form>
	</div>


	<!-- to delete a service -->
	<div>
		<p>Deleting Service</p>
		<form method="POST" method="deleteService">
			<p class="errorMessage"><?php if (isset($errorMessageDelete)) { echo $errorMessageDelete; } ?></p>
			<p class="successMessage"><?php if (isset($successMessageDelete)) { echo $successMessageDelete; } ?></p>
			<select name="serviceToDelete">
				<option disabled selected>Services..</option>
				<?php

					$serviceNameIDSql = "SELECT services.serviceID, services.serviceName, services.routeID FROM services";
					$serviceNameIDSqlResult = mysqli_query($con, $serviceNameIDSql);
					if (mysqli_num_rows($serviceNameIDSqlResult) > 0) {
						while($serviceNameIDRow = mysqli_fetch_assoc($serviceNameIDSqlResult)) {
							$deleteServiceOp .= '<option value="' . $serviceNameIDRow["serviceID"] . '|' . $serviceNameIDRow["routeID"] . '|' . $serviceNameIDRow["serviceName"]. '">' . $serviceNameIDRow["serviceName"] . '</option>';
						}
					}
					echo $deleteServiceOp;
				?>
			</select>

			<input type="submit" name="deleteService" value="Delete Service">
		</form>
	</div>
</div>

<!-- this table displays the services and their key other attributes for admins to know which services are busy and how their other attributes are being used -->
<?php
	$tableBlock = '<table>';
	$tableBlock .= '<tr>';
	$tableBlock .= '<td>Service</td>';
	$tableBlock .= '<td>Driver</td>';
	$tableBlock .= '<td>Bus</td>';
	$tableBlock .= '<td>Route</td>';
	$tableBlock .= '<td>Start</td>';
	$tableBlock .= '<td>End</td>';
	$tableBlock .= '</tr>';

	//loops through the main services array
	for ($i=0; $i < count($services); $i++) { 
		$tableBlock .= '<tr>';

		//gets the serviceID and displays its names from services table
		$serviceID = $services[$i][0];
		$serviceNameSql = "SELECT services.serviceName FROM services WHERE services.serviceID = $serviceID";
		$serviceNameSqlResult = mysqli_query($con, $serviceNameSql);
		if (mysqli_num_rows($serviceNameSqlResult) > 0) {
			while($serviceNameRow = mysqli_fetch_assoc($serviceNameSqlResult)) {
				$tableBlock .= '<td>' . $serviceNameRow["serviceName"] . '</td>';
			}

		}

		//gets the driver's ID and display his/her from the driver table
		$driverID = $services[$i][2];
		$driverNameSql = "SELECT drivers.firstName, drivers.lastName FROM drivers WHERE drivers.driverID = $driverID";
		$driverNameSqlResult = mysqli_query($con, $driverNameSql);
		if (mysqli_num_rows($driverNameSqlResult) > 0) {
			while($driverNameRow = mysqli_fetch_assoc($driverNameSqlResult)) {
				$tableBlock .= '<td>' . $driverNameRow["firstName"] . " " . $driverNameRow["lastName"] . '</td>';
			}

		}

		//gets the busID and display its number of passengers and type
		$busID = $services[$i][3];
		$busNameSql = "SELECT buses.NoPassengers, buses.BusType FROM buses WHERE buses.BusID = $busID";
		$busNameSqlResult = mysqli_query($con, $busNameSql);
		if (mysqli_num_rows($busNameSqlResult) > 0) {
			while($busNameRow = mysqli_fetch_assoc($busNameSqlResult)) {
				$tableBlock .= '<td>' . $busNameRow["NoPassengers"] . " " . $busNameRow["BusType"] . '</td>';
			}

		}

		//gets the route ID and display its name from the routes table
		$routeID = $services[$i][1];
		$routeNameSql = "SELECT routes.routeName FROM routes WHERE routes.routeID = $routeID";
		$routeNameSqlResult = mysqli_query($con, $routeNameSql);
		if (mysqli_num_rows($routeNameSqlResult) > 0) {
			while($routeNameRow = mysqli_fetch_assoc($routeNameSqlResult)) {
				$tableBlock .= '<td>' . $routeNameRow["routeName"] . '</td>';
			}

		}
		
		//as the times have already been directly stored in the services array (in header), they are just straightly displayed 
		$tableBlock .= '<td>' . $services[$i][5] . '</td>';
		$tableBlock .= '<td>' . $services[$i][6] . '</td>';

		$tableBlock .= '</tr>';
	}

	$tableBlock .= '</table>';
	echo $tableBlock;
?>


<?php include("inc/footer.php"); ?>