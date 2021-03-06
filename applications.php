<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_assignments.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of assignments (table: fr_assignments)
 * definition  : An assignment is a task for a volunteer at an event (shift). 
 * ---------------------------------------------------------------------------
 */

session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['persons_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">
	
		
		<?php 
		//gets logo
			include 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><?php if($id) echo 'My'; ?>Shifts</h3>
		</div>
		
		<div class="row">
			
			<p>
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="appli_create.php" class="btn btn-primary">Add Application</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="persons.php">Employee</a> &nbsp;';
				?>
				<a href="jobs.php">jobs</a> &nbsp;
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="applications.php">AllShifts</a>&nbsp;';
				?>
				<a href="applications.php?id=<?php echo $sessionid; ?>">MyShifts</a>&nbsp;
				<?php if($_SESSION['persons_title']=='Employee')
					echo '<a href="jobs.php" class="btn btn-primary">Employee</a>';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Job</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM applications 
						LEFT JOIN persons ON persons.id = applications.appli_per_id 
						LEFT JOIN jobs ON jobs.id = applications.appli_jobs_id
						WHERE persons.id = $id 
						ORDER BY lname ASC, lname ASC;";
					else
						$sql = "SELECT * FROM applications 
						LEFT JOIN persons ON persons.id = applications.appli_per_id 
						LEFT JOIN jobs ON jobs.id = applications.appli_jobs_id
						ORDER BY lname ASC, lname ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['fname'] . '</td>';
						echo '<td>'. $row['lname'] . '</td>';
						echo '<td>'. $row['jobs_title'] . '</td>';						
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="appli_read.php?id='.$row[0].'">Details</a>';
						if ($_SESSION['persons_title']=='Employer' )
							echo '&nbsp;<a class="btn btn-success" href="appli_update.php?id='.$row[0].'">Update</a>';
						if ($_SESSION['persons_title']=='Employer' 
							|| $_SESSION['persons_id']==$row['appli_per_id'])
							echo '&nbsp;<a class="btn btn-danger" href="appli_delete.php?id='.$row[0].'">Delete</a>';
						if($_SESSION["persons_id"] == $row['appli_per_id']) 		echo " &nbsp;&nbsp;Me";
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>