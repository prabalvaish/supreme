
<!DOCTYPE html>

	<?php 
	// header ('Content-Type: application/json');
	// header('Acess-Control-Allow-Origin; ');

    include "config.php";

    if (isset($_POST['submit'])) {
       	$reporto = $_REQUEST['report'];    
        
		$sql="SELECT * FROM xin_employees WHERE reports_to = '".$reporto."'";
		$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");

		if(mysqli_num_rows($result)>0){  
    		$output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    		echo json_encode($output);
		}
		else{
    	echo json_encode(array('message' => 'No Record Found' , 'status' => false));
		}
	}

    
?>
<form class="form" method="post" name="report">
    
    <input type="number" name="report" placeholder="value" autofocus="true"/>
    <input type="submit" value="submit" name="submit" /> 

</form>




  