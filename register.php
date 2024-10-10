<?php

$host = 'localhost';
$user = 'root';
$password = ''; 
$database = 'portal';

$conn = mysqli_connect($host, $user, $password, $database);

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];

   // Check by email if user already exists in any table
   $select = "SELECT * FROM student_form WHERE email = '$email'
              UNION
              SELECT * FROM teacher_form WHERE email = '$email'
              UNION
              SELECT * FROM admins_form WHERE email = '$email'";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $error[] = 'User already exists!';
   } else {
      if($pass != $cpass){
         $error[] = 'Passwords do not match!';
      } else {
         // Insert into the respective table based on user_type
         if ($user_type == "student") {
            $insert = "INSERT INTO student_form(name, email, password, user_type) VALUES('$name', '$email', '$pass', '$user_type')";
         } elseif ($user_type == "teacher") {
            $insert = "INSERT INTO teacher_form(name, email, password, user_type) VALUES('$name', '$email', '$pass', '$user_type')";
         } elseif ($user_type == "admin") {
            $insert = "INSERT INTO admins_form(name, email, password, user_type) VALUES('$name', '$email', '$pass', '$user_type')";
         }
         
         mysqli_query($conn, $insert);
         header('location:login_form.php'); //linked with desired page.
      }
   }

};
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <link rel="stylesheet" href="register.css">
</head>

<body>

<div class="form-container">
   <div class="register-box">
      <div class="user-icon">
         <img src="photo/profile.png" alt="User Icon">
     </div>
     <form action="register.php" method="post">

         <!-- Error message display (if needed) -->
        
         <?php
         if(isset($error)){
            foreach($error as $error){
               echo '<span class="error-msg">'.$error.'</span>';
            };
         };
         ?>
        
         
         <!-- Name Field -->
         <div class="input-group">
            <label for="name">Name</label>
            <div class="input-with-icon">
               <i class="fas fa-user"></i>
               <input type="text" id="name" name="name" required placeholder="Enter your name">
            </div>
         </div>
   
         <!-- Email Field -->
         <div class="input-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
               <i class="fas fa-envelope"></i>
               <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
         </div>
   
         <!-- Password Field -->
         <div class="input-group">
            <label for="password">Password</label>
            <div class="input-with-icon">
               <i class="fas fa-lock"></i>
               <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
         </div>
   
         <!-- Confirm Password Field -->
         <div class="input-group">
            <label for="cpassword">Confirm Password</label>
            <div class="input-with-icon">
               <i class="fas fa-lock"></i>
               <input type="password" id="cpassword" name="cpassword" required placeholder="Confirm your password">
            </div>
         </div>
   
         <!-- User Type Field -->
         <div class="input-group">
            <label for="user_type">User Type</label>
            <div class="input-with-icon">
               <i class="fas fa-users"></i>
               <select id="user_type" name="user_type">
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
               </select>
            </div>
         </div>
   
         <!-- Submit Button -->
         <div class="input-group">
            <button type="submit" name="submit" class="form-btn">Register Now</button>
         </div>
   
         <p>Already have an account? <a href="login.php">Login now</a></p>
      </form>
   </div>
</div>

</body>
</html>