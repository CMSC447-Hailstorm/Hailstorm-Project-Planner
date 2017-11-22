<?php
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Create New Project</h2>
		
		<form method="post" action="">
			Title: <input type="text" name="Project_Title" required></br>
			Budget: <input type="text" name="Budget" required></br>
			Phase(s): <button id="Add_Phase">Add Phase</button></br>
			Milestone(s): <button id="Add_Milestone">Add Milestone</button></br>
			Description: <input type="text" name="Description" required></br>
			
			<button type="submit" name="submit">Save</button>
			<button name="cancel">Cancel</button>
		</form>
	
	</body>
	<footer>
	</footer>
</html>