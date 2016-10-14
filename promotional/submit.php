<?php 
	
	function add2($email, $fav){
    // Create the connection
		$conn = new mysqli("localhost", "test", "apples", "ipw");
		if ($conn->connect_errno) {
			echo "blah";
		}
    // Prepare the statement
    // We assume the earth is flat since the function ST_DISTANCE_SPHERE is not supported very well
		$stmt = $conn->prepare("INSERT INTO sub (email, fav, timestamp1,id) VALUES (?, ?, ?,?)");
		$stmt->bind_param("sssi",$email, $fav,$date,$id);
		$id = null;
		$date = date("Y-m-d H:i:s");
		if ($stmt->execute()){
			//echo "done"
		}


    // Close the statement and connection
		$stmt->close();
		$conn->close();

		return "done";
	}

	$email = $_GET['email'];
	$fav = $_GET['fav'];


	echo add2($email, $fav);

	?>