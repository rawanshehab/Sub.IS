<?php 
include('server.php');
	$usn = $_SESSION['username'];
	$user_details = mysqli_query($db, "SELECT * FROM users WHERE username='$usn'");
	$user_row = mysqli_fetch_assoc($user_details);
	$user_id = $user_row['id'];
	$user_type = $user_row['type'];

	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$record = mysqli_query($db, "SELECT * FROM user_addresses WHERE id=$id");

		if (count($record) == 1 ) {
			$n = mysqli_fetch_array($record);
			$name = $n['name'];
			$address = $n['address'];
		}

	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Stuff </title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php if (isset($_SESSION['message'])): ?>
		<div class="msg">
			<?php 
				echo $_SESSION['message']; 
				unset($_SESSION['message']);
			?>
		</div>
	<?php endif ?>

	<?php 
	$results = null;


		if($user_type == 'student') {
			$results = mysqli_query($db, "SELECT * FROM user_addresses WHERE name='$usn'"); 
		} elseif($user_type == 'teacher' || $user_type == 'teacher_assistant') {
			$results = mysqli_query($db, "SELECT * FROM user_addresses"); 

		} else {
			$_SESSION['message'] = "You should not be here"; 	
			header('location: index.php');

		}

	?>

	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<?php if ($user_type == 'teacher') : ?>
				<th colspan="2">Action</th>
				<?php endif ?>	

			</tr>
		</thead>
		
		<?php while ($row = mysqli_fetch_array($results)) { ?>
			<tr>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['address']; ?></td>
				<?php if ($user_type == 'teacher') : ?>
					<td>
						<a href="address.php?edit=<?php echo $row['id']; ?>" class="edit_btn" >Edit</a>
					</td>
					<td>
						<a href="server.php?del=<?php echo $row['id']; ?>" class="del_btn">Delete</a>
					</td>
				<?php endif ?>	

			</tr>
		<?php } ?>
	</table>

	<?php if ($user_type == 'teacher') : ?>		
		<form method="post" action="server.php">

			<input type="hidden" name="id" value="<?php echo $id ?>">

			<div class="input-group">
				<label>Name</label>
				<input type="text" name="name" value="<?php echo $name ?>">
			</div>

			<div class="input-group">
				<label>Address</label>
				<input type="text" name="address" value="<?php echo $address ?>">
			</div>
			
			<div class="input-group">
				<?php if ($update == true): ?>
					<button class="btn" type="submit" name="update" style="background: #556B2F;" >update</button>
				<?php else: ?>
					<button class="btn" type="submit" name="post_address">Save</button>
				<?php endif ?>
			</div>
		</form>

	<?php endif ?>

</body>
</html>


