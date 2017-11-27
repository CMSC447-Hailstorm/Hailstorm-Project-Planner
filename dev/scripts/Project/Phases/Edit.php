<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	if(!Session::UserLoggedIn())
	{
		header("Location: /login.php");
	}
	if(!isset($_GET))
	{
		header("Location: ../../home.php");
	}
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect' . mysqli_connect_error());
	}

	$prid = $_GET['prid'];
	$phid = $_GET['phid'];
	$sql = "SELECT * FROM Phases WHERE Phase_ID = '$phid'";

	if(isset($_POST['PhaseSubmit']) && !empty($_POST))
	{
		$phaseName = $_POST['Name'];
		$description = $_POST['Description'];

		$sql = "UPDATE Phases SET Phase_Name = '$phaseName', Phase_Description = '$description' WHERE Phase_ID='$phid'";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		header("Location: ./View.php?prid=" . $prid . "&phid=" . $phid);
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function GetSearchResults()
            {
                var searchText = document.getElementById("SearchBar").value;
                if (searchText != "")
                {
                    var handler = new XMLHttpRequest();
                    handler.onreadystatechange = function()
                    {
                        if(this.readyState == 4 && this.status == 200)
                        {
                            var users = JSON.parse(this.responseText);
                            if(users.length > 0)
                            {
                                if(users[0] == "No results found!")
                                {
                                    document.getElementById("AssignResults").innerHTML = users[0];
                                }
                                else
                                {
                                    document.getElementById("AssignResults").innerHTML = "";
                                    document.getElementById("AssignResults").innerHTML += "<nav><ul>";
                                    for (var u = 0; u < users.length; u += 2)
                                    {
                                        document.getElementById("AssignResults").innerHTML += "<li onclick='AssignUser(" + users[u+1] + ")'>" + users[u] + "</li>";
                                    }
                                    document.getElementById("AssignResults").innerHTML += "</ul></nav>";
                                }
                            }
                        }
                    }
                    handler.open("GET", "User_Search.php?u=" + searchText, false);
                    handler.send();
                }
			}
			function AssignUser(uid)
			{
				if(confirm("Are you sure you want to assign this user to this phase?  Your changes to the phase will not be saved."))
                {
                    var handler = new XMLHttpRequest();
                    handler.onreadystatechange = function()
                    {
                        if(this.readyState == 4 && this.status == 200)
                        {
                            if(String(this.responseText).length > 0)
                            {
                                alert(this.responseText);
                            }
                            else
                            {
                                location.reload();
                            }
                        }
                    }
                    handler.open("GET", "Assign_User.php?uid=" + uid + "&phid=<?php echo $_GET['phid']; ?>&prid=<?php echo $_GET['prid']; ?>", false);
                    handler.send();
                }
			}
		</script>
	</head>
	<body>
	
		<h2>Edit Phase</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid'] . "&phid=" . $_GET['phid']); ?>" autocomplete="off">
			<?php
				if($result = mysqli_query($conn, $sql))
				{
					$count = mysqli_num_rows($result);
					$phase = mysqli_fetch_array($result);
					if ($count == 1)
					{
						echo "<p>Name: <input type='text' name='Name' value='" . $phase['Phase_Name'] . "' required /></p>";
						echo "<p>Description: <input type='text' name='Description' value='" . $phase['Phase_Description'] . "' required /></p>";
					}
				}

				echo "<h3>Assigned Users: </h3>";
				echo "<nav><ul>";
				$sql = "SELECT * FROM User_Assignments WHERE Phase_ID_FK = " . $phase['Phase_ID'];
				if($result = mysqli_query($conn, $sql))
				{
					while($assign = mysqli_fetch_array($result))
					{
						$userSql = "SELECT * FROM Users WHERE User_ID = " . $assign['User_ID_FK'];
						if ($userResult = mysqli_query($conn, $userSql))
						{
							$user = mysqli_fetch_array($userResult);
							echo "<li>" . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</li>";
						}
					}
				}
				echo "</ul></nav>";

				echo "<p>Assign User: <input type='search' id='SearchBar' placeholder='Search for User...' onkeydown='if (event.keyCode == 13) return false;'/> <button type='button' onclick='GetSearchResults()'>Search</button></p>";
				echo "<div id='AssignResults'></div>";
			?>
			
			<button type="submit" name="PhaseSubmit">Save</button>
		</form>
		<a href="View.php?prid=<?php echo $_GET['prid'] . '&phid=' . $_GET['phid'] ?>"><button type="cancel" name="cancel">Cancel</button>
	</body>
	<footer>
	</footer>
</html>