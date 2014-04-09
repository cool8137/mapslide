<?php

	if($_POST['submit']){
		$list_name = $_POST['list_name'];
		$list_body = $_POST['list_body'];
		$list_user = $_SESSION['username'];

		//Instantiate Database object
		$database = new Database;

		$database->query('INSERT INTO lists (list_name,list_body,list_user) VALUES(:list_name,:list_body,:list_user)');
		$database->bind(':list_name',$list_name);
		$database->bind(':list_body',$list_body);
		$database->bind(':list_user',$list_user);
		$database->execute();

		if($database->lastInsertId()){

			echo '<p class="msg">Your list has been created</p>';
		}
	}
?>


<h1>Add a List</h1>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
	<label>List Name</label>
	<input type="text" class="form-control" name="list_name"/><br />
	<label>List Body</label>
	<textarea rows="5" cols="50" class="form-control" name="list_body"></textarea><br />
	<input type="submit" class="form-control" value="Create" name="submit" />
</form>