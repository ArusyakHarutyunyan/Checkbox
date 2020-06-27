<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "Test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['title']) && $_POST['title'] != ''){
		$title = $_POST['title'];
		$sql = mysqli_query($conn, "INSERT INTO `checkbox`(`task_title`) VALUES ('$title')");
	}

	if(isset($_POST['check']) && $_POST['check'] != '' && isset($_POST['id']) && $_POST['id'] != ''){
		$today   = new DateTime;
		$update = "UPDATE `checkbox` SET `checked`='$_POST[check]', `timestamp_modified`='".$today->format('Y-m-d H:i:s')."' WHERE `main`='$_POST[id]'";
		$result = mysqli_query($conn, $update);
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Checkbox</title>
</head>
<body>
	<form action="./" method="post">
		<input type="text" name="title">
		<button type="submit">Submit</button>
	</form>

	<div class="checkboxBlock">
		<?php
			$block = mysqli_query($conn, "SELECT `main`, `task_title`, `checked` FROM `checkbox`");
			foreach ($block as $key => $value) {
				if($value['checked'] != 1){
					echo "<input data-id='".$value['main']."' id='".$value['task_title']."' type='checkbox'/>
					<label for=''>".$value['task_title']."</label><br>";
				} else {
					echo "<input type='checkbox' data-id='".$value['main']."' id='".$value['task_title']."' checked/>
					<label for=''>".$value['task_title']."</label><br>";
				}
			}
		?>
	</div>






	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script>
		$('.checkboxBlock input[type="checkbox"]').click(function(){
			var id = $(this).attr('data-id');
			var check;
			if($(this).is(':checked')){
				check = 1;
			} else {
				check = 0;
			}

			$.ajax({
			    type: "POST",
			    url: "/",
			    data: {check:check, id:id}, 
			}).done((data) => {
				console.log('success')
			}).fail((data) => {
				console.log('fail')
			});

		})
	</script>
</body>
</html>