<?php $form_required = true;?>
<?php if($_POST['register_submit']){
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$errors = array();


		//Check passwords match
		if($password != $password2){
			$errors[] = "Your passwords do not match";
		} 
		//Check first name
		if(empty($first_name)){
			$errors[] = "First Name is Required";
		} 
		//Check email
		if(empty($email)){
			$errors[] = "Email is Required";
		} 
		//Check username
		if(empty($username)){
			$errors[] = "Username is Required";
		} 
		//Match passwords
		if(empty($password)){
			$errors[] = "Password is Required";
		} 


		//Instantiate Database object
		$database = new Database;

		/* Check to see if username has been used */

		//Query
		$database->query('SELECT username FROM users WHERE username = :username');
		$database->bind(':username', $username);  
		//Execute
		$database->execute();
		if($database->rowCount() > 0){
			$errors[] = "Sorry, that username is taken";
		}

		/* Check to see if email has been used */

		//Query
		$database->query('SELECT email FROM users WHERE email = :email');
		$database->bind(':email', $email);  
		//Execute
		$database->execute();
		if($database->rowCount() > 0){
			$errors[] = "Sorry, that email is taken";
		}

		//If there are no errors, proceed with registration
		if(empty($errors)){
			//Encrypt Password
			$enc_password = md5($password);

			//Query
			$database->query('INSERT INTO users (first_name,last_name,email,username,password)
			              VALUES(:first_name,:last_name,:email,:username,:password)');
			//Bind Values
			$database->bind(':first_name', $first_name);  
			$database->bind(':last_name', $last_name);   
			$database->bind(':email', $email);  
			$database->bind(':username', $username);  
			$database->bind(':password', $enc_password);  

			//Execute
			$database->execute();

			//If row was inserted
			if($database->lastInsertId()){
				echo '<p class="msg">You are now registered! Please Log In</p>';
				$form_required=false;
			} else {
				echo '<p class="error">Sorry, something went wrong. Contact the site admin</p>';
				$form_required=true;
			}
		}
}
?>

<?php if($form_required):?>
<h3>Register</h3>
<p>Please use the form below to register at our site</p>
<?php
if(!empty($errors)){
	echo "<ul>";
 	foreach($errors as $error){
		echo "<li style='color:red' class=\"error\">".$error."</li>";
	}
	echo "</ul>";
}
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal">
 				
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">First Name: </label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="first_name" value="<?php if($_POST['first_name'])echo $_POST['first_name'] ?>" placeholder = "Your first name"/>
		</div>
	</div>			
	
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">Last Name:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="last_name" value="<?php if($_POST['first_name'])echo $_POST['last_name'] ?>" placeholder = "Your last name"/>
		</div>
	</div>

	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">Email: </label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="email" value="<?php if($_POST['email'])echo $_POST['email'] ?>" placeholder = "Your email address"/>
		</div>
	</div>
		
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">Username: </label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="username" value="<?php if($_POST['username'])echo $_POST['username'] ?>" placeholder = "Choose a username"/>
		</div>
	</div>	
              
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">Password: </label>
		<div class="col-sm-10">
			<input type="password" class="form-control" name="password" value="<?php if($_POST['password'])echo $_POST['password'] ?>" placeholder = "Choose a password"/>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">Confirm Password: </label>
		<div class="col-sm-10">
			<input type="password" class="form-control" name="password2" value="<?php if($_POST['password'])echo $_POST['password2'] ?>" placeholder = "Retype the password"/>
		</div>
	</div>	
                
    <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" class="btn btn-default" value="Register" name="register_submit" />
		</div>
	</div>    
	
</form>
<?php endif;?>