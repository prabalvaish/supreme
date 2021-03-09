
<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
 $con= new mysqli("localhost","root","","supreme");

$employee_id=8;
$attendance_date='2021-03-05';
$clock_in='2021-03-05 13:40:00';
$clock_out='2021-03-05 13:40:00';

if($clock_in != null)
{
    $sql="INSERT INTO `xin_attendance_time`(`employee_id`, `attendance_date`, `clock_in`, `clock_out`) VALUES ('".$employee_id."','".$attendance_date."','".$clock_in."','".$clock_out."')";
    if(mysqli_query($con,$sql))
    {
    echo("Data Inserted $employee_id");
    }
    else{
    echo("No");
    }
}
    else
    {
        $result = "SELECT clock_in from xin_attendance_time where employee_id ='".$employee_id."' AND attendance_date = '".$attendance_date."'";
        $time=mysqli_query($con,$result);
        if ($time->num_rows > 0) 
        {
            while($row = $time->fetch_assoc()) {
              $clockin=  $row["clock_in"];
            }
        }
        $datetime1 = new DateTime($clockin);
        $datetime2 = new DateTime($clock_out);
        $interval = $datetime1->diff($datetime2);
        $timeDiff= $interval->format('%h')." : ".$interval->format('%i'); 
        if($timeDiff != null){

        
        $sql="INSERT INTO `xin_attendance_time`(`employee_id`, `attendance_date`, `clock_in`, `clock_out`,`total_work`) VALUES ('".$employee_id."','".$attendance_date."','".$clock_in."','".$clock_out."','".$timeDiff."')";
        if(mysqli_query($con,$sql)){
        echo("Data Inserted $employee_id");
        }
    }
}
}
 
?>