<?php
 include 'dbconnection.php';
 $con=dbconnection();
 
 if(isset($_POST['name']))
 {
     $name=$_POST['name'];
 }
  else  return;
  
  if(isset($_POST['prename']))
  {
      $prename=$_POST['prename'];
  }
   else  return;
  
  if(isset($_POST['email']))
  {
    $email=$_POST['email'];
  }
   else  return;

   if(isset($_POST['password']))
   {
    $password=$_POST['password'];
    $hashed_password = password_hash($password,PASSWORD_BCRYPT);
   }
    else  return;
   $query="INSERT INTO client(name,prename,email,password)VALUES('$name','$prename','$email','$hashed_password')";
    $result=mysqli_query($con,$query);

    $arr=[];
    if($result)
    {
        $arr['status']='success';
        $arr['message']='Data inserted successfully';
    }
    else
    {
        $arr['status']='failed';
        $arr['message']='Data not inserted';
    }
     print(json_encode($arr));


?>