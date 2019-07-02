<!-- main web page where students input there information -->

<html>
	<head>
	<title> CSMATH Course Planner</title>
	</head>

<?php
$servername = "localhost";
$username = "cs377";
$password = "cs377_s17";
$dbname = "CoursePlanner";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$majorResult = $con->query("SELECT mID, mName FROM MajorMinor WHERE Major = 1");
$majorMinorResult = $con->query("SELECT mID, mName FROM MajorMinor");
$courseResult = $con->query("SELECT cID, cName FROM Course");

?>



	<body>
	<H3>
	Enter your information
	</H3>
	<P>
	<form action= database.php method= "post">

		
		Select your first Major:
		<select name="Major">
		<option value =0>Select</option>
		<?php
    		while($row = $majorResult->fetch_assoc()) {
        		echo "<option value =".$row["mID"].">".$row["mName"]."</option>";
    		}
		?>	
		</select>

		<BR>

		Select your Second Major or Minor:
		<select name="MajorMinor">
		<option value =0>None</option>
		<?php
   			while($row = $majorMinorResult->fetch_assoc()) {
        		echo "<option value =".$row["mID"].">".$row["mName"]."</option>";
   		 	}
		?>
		</select>
		<BR>



		Select current semester:
		<select name ="currentSemester">
		<option value =0>Spring 2017</option>
		<option value =1>Fall 2017</option>
		<option value =2>Spring 2018</option>
		<option value =3>Fall 2018</option>
		<option value =4>Spring 2019</option>
		<option value =5>Fall 2019</option>
		<option value =6>Spring 2020</option>
		<option value =7>Fall 2020</option>
		<option value =8>Spring 2021</option>
		<option value =9>Fall 2021</option>
		<option value =10>Spring 2022</option>
		</select>

		<BR>
		Select graduation date:
		<select name ="graduationDate">
		<option value =0>Spring 2017</option>
		<option value =1>Fall 2017</option>
		<option value =2>Spring 2018</option>
		<option value =3>Fall 2018</option>
		<option value =4>Spring 2019</option>
		<option value =5>Fall 2019</option>
		<option value =6>Spring 2020</option>
		<option value =7>Fall 2020</option>
		<option value =8>Spring 2021</option>
		<option value =9>Fall 2021</option>
		<option value =10>Spring 2022</option>
		<option value =11>Fall 2022</option>
		<option value =12>Spring 2023</option>
		<option value =13>Fall 2023</option>
		<option value =14>Spring 2024</option>
		<option value =15>Fall 2024</option>
		<option value =16>Spring 2025</option>
		<option value =17>Fall 2025</option>
		<option value =18>Spring 2026</option>
		<option value =19>FAll 2026</option>
		<option value =20>Spring 2027</option>
		</select>



		<BR>
		What Classes have you already taken?
		<BR>
		<?php
    		while($row = $courseResult->fetch_assoc()) {
        		echo '<input type=checkbox name=course[] value="'.$row["cID"].'">'.$row["cID"].': '.$row["cName"].'<BR>';
    		}
		?>	

		<input type = "submit" />
	</form>
	</P>

	</body>

</html>


<?php $con->close(); ?>





