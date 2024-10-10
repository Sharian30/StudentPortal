<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = ''; 
$database = 'portal';

$conn = mysqli_connect($host, $user, $password, $database);

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $user_type = $_POST['user_type'];

   // Check user credentials based on user_type
   if($user_type == "student") {
      $select = "SELECT * FROM student_form WHERE email = '$email' AND password = '$pass'";
   } elseif($user_type == "teacher") {
      $select = "SELECT * FROM teacher_form WHERE email = '$email' AND password = '$pass'";
   } elseif($user_type == "admin") {
      $select = "SELECT * FROM admins_form WHERE email = '$email' AND password = '$pass'";
   }

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_assoc($result);

      // Store user data in session
      $_SESSION['user_id'] = $row['id']; // Storing user ID in session
      $_SESSION['user_type'] = $row['user_type']; // Storing user type in session

      // Redirect based on user type
      if($row['user_type'] == 'student'){
         header('location:index.html'); // Redirect to student dashboard
      } elseif($row['user_type'] == 'teacher'){
         header('location:teacher_dashboard.php'); // Redirect to teacher dashboard
      } elseif($row['user_type'] == 'admin'){
         header('location:admin_dashboard.php'); // Redirect to admin dashboard
      }

   } else {
      $error[] = 'Incorrect email or password!';
   }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="user-icon">
                <img src="photo/profile.png" alt="User Icon">
            </div>
            <form action="login.php" method="post">
            <?php
            if(isset($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                };
            };
            ?>
                <div class="input-group">
                    <label for="username">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter Your ID or Name">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter Your Password">
                    </div>
                </div>

      
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

                <div class="input-group remember-forgot">
                    <label><input type="checkbox" name="remember"> Remember</label>
                    <a href="#">Forgot Password?</a>
                </div>

                <div class="input-group">
                    <button type="submit">Log In</button>
                    <p>Don't have an account? <a href="register.php">Register Now</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
