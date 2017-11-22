<?php
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Create New Task</h2>
		
		<form method="post" action="">
			Name: <input type="text" name="Name" required></br>
			Estimated Hours: <input type="text" name="Hours" required></br>
			Estimated Budget: <input type="text" name="Budget" required></br>
			Requirements: <button id="Add_Requirement">Add Phase</button></br>
			Description: <input type="text" name="Description" required></br>
			
			<button type="submit" name="submit">Save</button>
			<button name="cancel">Cancel</button>
		</form>
	
	</body>
	<footer>
	</footer>
</html>