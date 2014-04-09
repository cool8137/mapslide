<?php

$task_id = $_GET['id'];

//Instantiate Database object
$database = new Database;

//Get logged in user
$list_user = $_SESSION['username'];

//Query tasks
$database->query('SELECT tasks.task_name,tasks.task_body,tasks.due_date,tasks.is_complete, lists.list_user AS list_user 
				  FROM tasks 
				  INNER JOIN lists
				  ON tasks.list_id = lists.id
				  WHERE tasks.id = :task_id');

$database->bind(':task_id',$task_id);

$row = $database->single();

?>


<?php if ($row['list_user']!=$list_user):?>
	<p>You do not have permission to view this Task!!</p>
	
<?php else: ?>
	<?php
	echo '<h1>'.$row['task_name'].'</h1>';

	echo '<h3>Task Description</h3>';
	echo '<p>'.$row['task_body'].'</p>';

	echo '<h3>Due Date</h3>';
	echo '<p>'.$row['due_date'].'</p>';

	if($row['is_complete'] == 1){
		echo 'Status: <strong>Complete</strong>';
	} else {
		echo 'Status: <strong>Incomplete</strong>';
	}
	?>
<br />
<br />
<a href="?page=edit_task&id=<?php echo $row['id']; ?>">Edit Task</a>
<?php endif;?>