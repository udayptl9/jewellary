<?php
  session_start();
  if(!isset($_SESSION['user_id'])) {
    header("Location: ./login.php"); 
  }
?>
<div class="topnav navbar">
  <a href="index.php">Home</a>
  <a href="materials.php">Materials</a>
  <a href="ornaments.php">Ornaments</a>
  <a href="orders.php">Orders</a>
  <a href="payments.php">Payments</a>
  <a href="balances.php">Balances</a>
  <a href="register.php">Add User</a>
  <a href="logout.php">Logout</a>
</div>
