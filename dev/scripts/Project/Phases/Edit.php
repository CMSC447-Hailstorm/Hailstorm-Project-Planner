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

<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
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
                                        document.getElementById("AssignResults").innerHTML += "<li onclick='AssignUser(" + users[u+1] + ", 1)'>" + users[u] + "</li>";
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
			function AssignUser(uid, com)
			{
				if (com == 1)
				{
					var message = "Are you sure you want to assign this user to this phase?  Your other changes to the phase will not be saved.";
				}
				else
				{
					var message = "Are you sure you want to remove this user from this phase?  Your other changes to the phase will not be saved.";
				}
				if(confirm(message))
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
					if (com == 1)
					{
                    	handler.open("GET", "Edit_User_Assignment.php?uid=" + uid + "&phid=<?php echo $_GET['phid']; ?>&prid=<?php echo $_GET['prid']; ?>&com=1", false);
					}
					else
					{
						handler.open("GET", "Edit_User_Assignment.php?uid=" + uid + "&phid=<?php echo $_GET['phid']; ?>&prid=<?php echo $_GET['prid']; ?>&com=0", false);
					}
                    handler.send();
                }
			}
		</script>
	</head>
	<body>
	
		<!--Title Bar-->
		<div class="w3-top w3-card w3-white">
			<div class="w3-bar w3-padding" style="height:15%">
				<a class="w3-bar-item"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetUsername();?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:50%">
				<a href="View.php?prid=<?php echo $_GET['prid'] . '&phid=' . $_GET['phid'] ?>"><button class="w3-button w3-red" type="cancel" name="cancel">Cancel</button></a>
				<div class="w3-border w3-padding">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
												. "?prid=" . $_GET['prid'] . "&phid=" . $_GET['phid']); ?>" autocomplete="off">
						<?php
							if($result = mysqli_query($conn, $sql))
							{
								$count = mysqli_num_rows($result);
								$phase = mysqli_fetch_array($result);
								if ($count == 1)
								{
									echo "<label>Name:</label> <input class='w3-input w3-border' type='text' name='Name' value='" . $phase['Phase_Name'] . "' required /></p>";
									echo "<label>Description:</label> <input class='w3-input w3-border' type='text' name='Description' value='" . $phase['Phase_Description'] . "' required /></p>";
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
										echo "<li onclick='AssignUser(" . $user['User_ID'] . ", 0)'>" . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</li>";
									}
								}
							}
							echo "</ul></nav>";

							echo "<label>Assign User:</label> <input type='search' id='SearchBar' placeholder='Search for User...' onkeydown='if (event.keyCode == 13) return false;'/> <button class='w3-button w3-green' type='button' onclick='GetSearchResults()'>Search</button></p>";
							echo "<div id='AssignResults'></div>";
						?>
						
						<button class="w3-button w3-green" type="submit" name="PhaseSubmit">Save</button>
					</form>
				</div>
			</div>
		</div>
		
	</body>
	<footer>
	</footer>
</html>