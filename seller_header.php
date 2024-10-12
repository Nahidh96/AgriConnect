<?php

if(isset($_POST['logout'])){
   session_destroy();
   header('location:login.php');
}

?>

<header class="header">

   <div class="flex">
      <a href="seller_page.php" class="logo">Seller Dashboard</a>

      <nav class="navbar">
         <a href="seller_page.php">Home</a>
         <a href="index.php">User Page</a>
         <a href="seller_products.php">Products</a>
         <a href="seller_orders.php">Orders</a>
         <a href="community_forum.php">Community</a>
         <a href="educational_resources.php">E.Resources</a>
      </nav>

      <form action="" method="POST">
         <input type="submit" value="Logout" name="logout" class="btn">
      </form>
   </div>

</header>
