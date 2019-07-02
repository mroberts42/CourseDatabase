<?php
/*MySql Server info and connection */
$servername = "localhost";
$username = "cs377";
$password = "cs377_s17";
$dbname = "CoursePlanner";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

/*Data from input form*/
$varMajor = $_POST['Major'];
$varMajorMinor = $_POST['MajorMinor'];
$varCourse = ($_POST['course']);
$graduation = $_POST['graduationDate'];
$current = $_POST['currentSemester'];


$semestersLeft = $graduation - $current;

if($varMajor == 0)
{
	die("You forgot to input a major, go back and fill select a valid major <BR>");
}

if($semestersLeft < 0)
{
	die("Congratulations! you already graduated... or your imput is wrong. Go back and fix it.");
}
else
{
	echo "You have $semestersLeft semesters left <BR>";
}



/*Query For Majors and Minor*/
$majorQuery = "SELECT mName, mID FROM MajorMinor WHERE mID = $varMajor OR mID = $varMajorMinor";
$result = $conn->query($majorQuery);

/*Query Courses taken by the student*/
$query1 = "SELECT cID, cName, cOffered FROM Course WHERE cID = ''";
$query2 = "SELECT cID FROM Course WHERE cID = ''";
foreach ($varCourse as $value) {
    $string .= " OR cID ='".$value."' " ;
}
$courseQuery = $query1 . $string;
$courseQuery2 = $query2 . $string;

$courseResult = $conn->query($courseQuery);


/*Required Courses Still Needed*/
$courseNeededQuery = $conn->query("SELECT cID, cName, cOffered  FROM Course  WHERE cID NOT IN ($courseQuery2) AND cID IN (SELECT CourseID FROM Required WHERE MajorID = $varMajor OR MajorID = $varMajorMinor)") ;

$courseNeeded2 = "SELECT cID  FROM Course  WHERE cID NOT IN ($courseQuery2) AND cID IN (SELECT CourseID FROM Required WHERE MajorID = $varMajor OR MajorID = $varMajorMinor)";



/*Prequisites needed for required course student still need to take*/
$preReq = $conn->query("SELECT cID, cName, cOffered FROM Course WHERE cID NOT IN ($courseQuery2) AND cID IN (SELECT PreReqID FROM Prerequisites WHERE CourseID IN ($courseNeeded2)) "); 

/*Required Courses not offered all the time*/
$coursesOfferedWhen = $conn->query("SELECT cID, cName, cOffered FROM Course WHERE cID IN ($courseNeeded2) AND cOffered != 'F & S'");

/*Electives that a student can take.*/
$electives = $conn->query("SELECT cID, cName, cOffered FROM Course WHERE cID NOT IN ($courseQuery2) AND cID IN (SELECT CourseID FROM Buckets, Contains WHERE (Buckets.MajorID = $varMajor OR Buckets.MajorID = $varMajorMinor) AND Buckets.MajorID = Contains.MajorID AND BucketNumber = bNo)"); 

/*Returns the Number of required courses left to take */

$countRequiredCourses = $conn->query("SELECT COUNT(cID) FROM Course WHERE cID IN ($courseNeeded2)");

$row1 = $countRequiredCourses->fetch_assoc();
$noCourseNeeded = $row1["COUNT(cID)"];
echo "you have $noCourseNeeded of required courses left <BR>";


/*Calculates the number of electives you've taken*/
$countElectives = $conn->query("SELECT DISTINCT CourseID FROM Buckets, Contains WHERE Buckets.MajorID = Contains.MajorID AND CourseID IN ($courseQuery2) AND (Buckets.MajorID = $varMajor OR Buckets.MajorID = $varMajorMinor) ");


$row2 = $countElectives->fetch_assoc();
$noElectivesTaken = $row2["COUNT(CourseID)"];



/*Calculates number of electives needed*/


$countElectivesNeeded = $conn->query("SELECT SUM(RequiredAmt) FROM Buckets WHERE (MajorID = $varMajor OR MajorID = $varMajorMinor) ");

$row3 = $countElectivesNeeded->fetch_assoc();
$noElectivesNeeded = $row3["SUM(RequiredAmt)"];

$noElectivesLeft = $noElectivesNeeded - $noElectivesTaken;

echo "you have $noElectivesLeft electives to take <BR>";
$noCoursesLeft = $noElectivesLeft + $noCourseNeeded;
echo "you need  $noCoursesLeft more courses in total to graduate <BR>";

$test = $noCoursesLeft/4.00;


if($noCoursesLeft/4.00 < $semestersLeft)
{
	echo "You should be able to graduate assuming the max amount of classes you take are 4 per semester";
}
else
{
	die("You probably can't graduate on time, I suggest changing your major.");
}

/*Print Major Query Results */
echo "<H4><u>Your Majors(Minors) </u></H4>";


while($row = $result->fetch_assoc()) 
{
    echo "" . $row["mName"]. "<br>";
}

/*Print Course Query Results*/
echo "<H4><u>Courses Taken</H4></u> <BR>";

while($row = $courseResult->fetch_assoc()) 
{
    echo "" .$row["cID"] . ": " . $row["cName"] . "<br>";
}


echo "<H4><u>Required Courses Still Needed</H4></u><BR>";

/*Print Required Courses Still Needed*/
if ($courseNeededQuery->num_rows > 0) 
{
    while($row = $courseNeededQuery->fetch_assoc()) 
    {
        echo "" . $row["cID"] . ": " . $row["cName"]. " Offered " .$row["cOffered"]. "<br>";
    }
} 
else 
{
    echo "Completed Required Courses";
}


/*Prints PreReqs still needed*/
echo "<BR><H4><u> Prerequisites for required courses, Prioritize these.</u></H4><BR>";

if ($preReq->num_rows > 0) 
{
    while($row = $preReq->fetch_assoc()) 
    {
        echo  " cID: " . $row["cID"] ." name: " . $row["cName"]. " cOffered : " .$row["cOffered"]. "<br>";
    }
} 
else 
{
    echo "No Prequisites left";
}

/*Print courses not offered all the time*/

echo "<H4><u>Be Aware that these courses aren't offered every semester</u></H4><BR>";

if ($coursesOfferedWhen->num_rows > 0) 
{
    while($row = $coursesOfferedWhen->fetch_assoc()) 
    {
        echo "" . $row["cID"] . ": " . $row["cName"]. " Offered: ".$row["cOffered"]. "<br>";
    }
} 
else 
{
    echo "none";
}

/*Print Electives one can take for their major/minor*/
echo "<H4><u>Electives you can take for your major</u></H4> <BR>";

while($row = $electives->fetch_assoc()) 
{
    echo  "" . $row["cID"] .": " . $row["cName"]. " Offered : " .$row["cOffered"]. "<br>";
}

$conn->close();
?>
