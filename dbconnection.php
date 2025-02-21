<?php
function dbconnection(){
    $con=mysqli_connect("localhost","root","","mobile");
    return $con;
    
}


?>