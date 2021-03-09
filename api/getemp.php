<?php
header ('Content-Type: application/json');
header('Acess-Control-Allow-Origin; ');

include "config.php";
   if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    $url.= $_SERVER['HTTP_HOST'];   
    $url.= $_SERVER['REQUEST_URI'];    
    $url_components = parse_url($url); 
    parse_str($url_components['query'], $params); 
    $id = $params['id']; 

    
$sql="SELECT * FROM xin_employees where reports_to = '$id' ";
$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");

if(mysqli_num_rows($result)>0){

    

    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);
}

else{
    echo json_encode(array('message' => 'No Record Found' , 'status' => false));
}
?>