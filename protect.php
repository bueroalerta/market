<?php

  include 'connect.php';

  $db_selected = mysqli_select_db($conn, $logindb);
  if (!$db_selected) {
    echo 'Login database not found!';
    exit();
  }

  function login($username, $pass) {
    global $conn;
    $sql = "SELECT id, username, password FROM members WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row || $row['password'] != $pass) {
      session_destroy();
      return false;
    }

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['login_string'] = $row['password'];
    return true;
  }

  function login_check() {
    global $conn;
    if (!isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
      return false;
    }

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $password = $_SESSION['login_string'];

    $sql = "SELECT password, userdb FROM members WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row || $row['password'] != $password) {
      return false;
    }

    global $userdb;
    $userdb = $row['userdb'];
    return true;
  }

  session_start();

  if (!login_check()) {
    header("Location: login.php");
    exit();
  }

  $db_selected = mysqli_select_db($conn, $userdb);
  if (!$db_selected) {
    echo 'User database not found!';
    exit();
  }

?>
