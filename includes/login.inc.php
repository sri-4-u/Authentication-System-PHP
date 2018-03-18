<?php

session_start();

if(isset($_POST['submit'])){

  include 'dbh.inc.php';

  $uid=mysqli_real_escape_string($conn,$_POST['uid']);
  $pwd=mysqli_real_escape_string($conn,$_POST['pwd']);

  //Error handlers
  if(empty($uid) ||empty($pwd)){
    header("Location: ../index.php?login=empty");
    exit();
  }else{
    $sql="SELECT * FROM users WHERE user_UID='$uid' OR user_EMAIL='$uid'";
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck <1){
      header("Location: ../index.php?login=error");
      exit();
    }else{
      if($row=mysqli_fetch_assoc($result)){
        $hashedPwdCheck=password_verify($pwd,$row['user_PWD']);
        if($hashedPwdCheck==false){
          header("Location: ../index.php?login=incorrectpswd");
          exit();
        }elseif ($hashedPwdCheck==true) {
          $_SESSION['u_id']=$row['user_ID'];
          $_SESSION['u_first']=$row['user_FIRST'];
          $_SESSION['u_last']=$row['user_LAST'];
          $_SESSION['u_email']=$row['user_EMAIL'];
          $_SESSION['u_uid']=$row['user_UID'];
          header("Location: ../index.php?login=success");
          exit();
        }
      }
    }
  }
}
else {
  header("Location: ../index.php?login=error");
  exit();
}
