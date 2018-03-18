<?php

if(isset($_POST['submit'])){
    include_once "dbh.inc.php";

    $first=mysqli_real_escape_string($conn,$_POST['first']);
    $last=mysqli_real_escape_string($conn,$_POST['last']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $uid=mysqli_real_escape_string($conn,$_POST['uid']);
    $pwd=mysqli_real_escape_string($conn,$_POST["pwd"]);

    /*Error handlers*/
    if(empty($first) || empty($last)|| empty($email)||empty($uid)||empty($pwd)){
      header("Location: ../signup.php?signup=empty");
      exit();
    }else{
      //check if input is valid (firstname,lastname)
        if(!preg_match("/^[a-zA-Z]*$/",$first) || !preg_match("/^[a-zA-Z]*$/",$last)){
          header("Location: ../signup.php?signup=invalid");
          exit();
      }
        else{
          //check if email is valid
          if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: ../signup.php?signup=email");
            exit();
        }
          else {
            $sql="SELECT * FROM users WHERE user_UID='$uid'";
            $result=mysqli_query($conn,$sql);
            $resultCheck=mysqli_num_rows($result);
// Error occurrence
              if($resultCheck > 0){
                header("Location: ../signup.php?signup=usertaken");
                exit();
              } else{
                //hashing the password
                $hasedpwd=password_hash($pwd,PASSWORD_DEFAULT);
                //Insert user in the database
                $sql="INSERT INTO users (user_FIRST,user_LAST,user_EMAIL,user_UID,user_PWD) VALUES ('$first','$last','$email','$uid','$hasedpwd');";
                mysqli_query($conn,$sql);
                header("Location: ../signup.php?signup=success");
                exit();
          }

        }
      }
    }
}else{
    header("Location: ../signup.php");
    exit();
}
