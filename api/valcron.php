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
 $curr_date=date("Y-m-d");

    
$sql="SELECT user_id, date_of_joining FROM xin_employees";
$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");

if(mysqli_num_rows($result)>0){


    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $valuex = json_encode($output);
    echo ($valuex);

    // for( $i=0;$i<$value.length; $i++){
    //  $diff= date_diff($curr_date, $value[i].date_of_joining)  ;  
        
    //     echo $diff->format("%R%a days");
    // }
    
    
    
    
    
    
//     $sql="INSERT INTO `xin_user_duration`(`user_id`, `join_date`, `duration_t`) VALUES ('".$user_id."','".$join_date."','".$duration_t."')";
//  if(mysqli_query($con,$sql)){
//  echo("Data Inserted $employee_id");
//  }else{
//  echo("No");
//  }
    
    
    
    
    
}

else{
    echo json_encode(array('message' => 'No Record Found' , 'status' => false));
}
?>