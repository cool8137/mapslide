<?php
//Start Session
session_start();
//Config File
require 'config.php';
//Database Class
require 'classes/database.php';

$database = new Database;

//Set Timezone
date_default_timezone_set('Asia/Kathmandu');
?>

<?php
  //LOG IN
  if($_POST['login_submit']){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $enc_password = md5($password);
    //Query
    $database->query("SELECT * FROM users WHERE username = :username AND password = :password");
    $database->bind(':username',$username);
    $database->bind(':password',$enc_password);
    $rows = $database->resultset();
    $count = count($rows);
    if($count > 0){
      session_start();
      //Assign session variables
      $_SESSION['username']   = $username;
      $_SESSION['password']   = $password;
      $_SESSION['logged_in']  = 1;
    } else {
      $login_msg[] = '<a style=\'color:red\'>Sorry, that login does not work</a>';
    }
  }


  //LOG OUT
  if($_POST['logout_submit']){
    if(isset($_SESSION['username']))
        unset($_SESSION['username']);
    if(isset($_SESSION['password']))
        unset($_SESSION['password']);
    if(isset($_SESSION['logged_in']))
        unset($_SESSION['logged_in']);
    session_destroy();
  }
?>

<!DOCTYPE html>
<html>
<head>
	<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<a href="index.php"><title>myTasks Application</title></a>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/styles.css"/>
	
	<style type="text/css">
		.map-canvas{
			width:500px;
			height:500px;
		}
	</style>
	<!--<script src = "http://code.jquery.com/jquery-1.10.2.min.js"></script>-->
	<script src = "js/jquery.js"></script>
	<script type="text/javascript"
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0Akb68UOD6wFzDiSQ76W2Ua8JH2JGGDI&libraries=places&sensor=false">
	</script>
</head>

<body>
	<!-- NAVBAR -->
	<div class="navbar navbar-inverse navbar-static-top">
        <div class="container">
		
			<!--MapSlide BRAND-->
			<a href="#" class="navbar-brand">MapSlide <small>(Alpha)</small></a>
				
			<!--3 bar menu button for small resolution-->
			<button class="navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			
			<!--div containing list of MENUS-->
			<div class="collapse navbar-collapse navHeaderCollapse">
				<p class="navbar-text pull-right">
					<!--HELLO-->
					<?php if($_SESSION['logged_in']) : ?>
						Hello, <?php echo $_SESSION['username']; ?>
					<?php endif; ?>
				</p>
				<ul class="nav navbar-nav navbar-left">
					<?php if ($_GET['page']==""):?>
						<li class = "active">
							<a href="index.php">Home</a>
						</li>
					<?php else:?>
						<li>
							<a href="index.php">Home</a>
						</li>
					<?php endif;?>					
					<?php if(!$_SESSION['logged_in']) : ?>
						<?php if ($_GET['page']=="register"):?>
							<li class = "active">
								<a href="index.php?page=register">Register</a>
							</li>
						<?php else:?>
							<li>
								<a href="index.php?page=register">Register</a>
							</li>
						<?php endif;?>
					<?php else : ?>
						<?php if ($_GET['page']=="new_list"):?>
							<li class="active"><a href="index.php?page=new_list">Add List</a></li>
						<?php else:?>
							<li><a href="index.php?page=new_list">Add List</a></li>
						<?php endif;?>
						<?php if ($_GET['page']=="new_task"):?>
							<li class="active"><a href="index.php?page=new_task">Add Task</a></li>
						<?php else:?>
							<li><a href="index.php?page=new_task">Add Task</a></li>
						<?php endif;?>
						<?php if ($_GET['page']=="add_slide"):?>
							<li class="active"><a href="index.php?page=add_slide">Add Landslide</a></li>
						<?php else:?>
							<li><a href="index.php?page=add_slide">Add Landslide</a></li>
						<?php endif;?>
					<?php endif; ?>  
				</ul>
			</div>
        </div>
    </div>
	
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="well sidebar-nav">
					
					<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
						<?php if(!$_SESSION['logged_in']) : ?>
							<h3>Login Form</h3>
							<?php foreach($login_msg as $msg): ?>
								<?php echo $msg.'<br />'; ?>
							<?php endforeach; ?>
							<label for="username" class="control-label">Username: </label><br />
							<input type="text" class = "form-control" name="username" /><br />
							<label for="password" class="control-label">Password: </label><br />
							<input type="password" class = "form-control" name="password" /><br />
							<br />
							<input type="submit" class="btn btn-default" value="Login" name="login_submit" />
						<?php else : ?>
							<h3>Your Account</h3>
							<input type="submit" class="btn btn-danger" value="Logout" name="logout_submit" />
					  <?php endif; ?>
					</form>
				</div>
			</div>
			
			<div class="col-md-9">
				<div class="span">
							<!--MAIN-->
	<?php if($_GET['page'] == 'welcome' || $_GET['page'] == ""): ?>
		<?php  include 'pages/welcome.php';?>
		
	<?php elseif(!$_SESSION['logged_in']) : ?>
		<?php 
		if($_GET['page'] == 'register'){
			include 'pages/register.php';
		}else{
			echo 'You dont have access to this!! Please login';
		}
		?>
		
	<?php else : ?>
		<?php
		if($_GET['msg'] == 'listdeleted'){
		  echo '<p class="msg">Your list has been deleted</p>';
		}
		if($_GET['page'] == 'list'){
		  include 'pages/list.php';
		} elseif($_GET['page'] == 'task'){
		  include 'pages/task.php';
		} elseif($_GET['page'] == 'new_task'){
		  include 'pages/new_task.php';
		} elseif($_GET['page'] == 'new_list'){
		  include 'pages/new_list.php';
		} elseif($_GET['page'] == 'edit_task'){
		  include 'pages/edit_task.php';
		} elseif($_GET['page'] == 'edit_list'){
		  include 'pages/edit_list.php';
		} elseif($_GET['page'] == 'delete_list'){
		  include 'pages/delete_list.php';
		} elseif($_GET['page'] == 'add_slide'){
		  include 'pages/add_slide.php';
		}
		?>
		
	<?php endif; ?>
				</div>
				
			
			</div>
			
			
		</div>
	</div>
	
    
    
	
	
        </div><!--/span-->
		</div><!--/row-->
      <hr>
	  

      
    <!-- FOOTER -->
	<div class="navbar navbar-default navbar-static-bottom">
		<div class="container">
			<p class = "navbar-text pull-left">
				&copy; GHEaSES International 2014
			</p>
			<a href="http://youtube.com/cool8137" class="navbar-btn btn-danger btn pull-right">Subscribe on Youtube</a>
		</div>
	</div>
	
	<script src = "http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src = "js/bootstrap.js"></script>
</body>
</html>
