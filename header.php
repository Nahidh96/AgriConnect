<?php

if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}

?>


<header class="header">

   <div class="flex">

      <a href="index.php" class="logo">AgriConnect<span>.</span></a>

      <nav class="navbar">
         <a href="index.php">Home</a>
         <a href="shop.php">Shop</a>
         <a href="orders.php">Orders</a>
         <a href="about.php">About</a>
         <a href="contact.php">Contact</a>
         <a href="user_messages.php">Messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <a href="search_page.php" class="fas fa-search"></a>

         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            
            // Fetch unread notification count
            $count_notifications = $conn->prepare("SELECT COUNT(*) FROM `notifications` WHERE seller_id = ? AND is_read = 0");
            $count_notifications->execute([$user_id]);
            $unread_count = $count_notifications->fetchColumn();

            // Fetch all notifications
            $notifications = $conn->prepare("SELECT * FROM `notifications` WHERE seller_id = ? ORDER BY created_at DESC");
            $notifications->execute([$user_id]);
         ?>
         <a href="#" id="notification-btn"><i class="fas fa-bell"></i><span>(<?= $unread_count; ?>)</span></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

   <!-- Notifications dropdown -->
    <div id="notifications" class="notifications">
       <?php
          foreach ($notifications as $notification) {
             $notification_id = $notification['id'];
             $is_read = $notification['is_read'] ? '' : 'unread';
             echo '
             <div class="notification ' . $is_read . '" data-id="' .    $notification_id . '">
                <span>' . $notification['message'] . '</span>
                <i class="fas fa-times" onclick="removeNotification(' .     $notification_id . ');"></i>
             </div>';
          }
       ?>
       <div class="view-all">
           <!--<a href="notifications.php" class="cbtn">View All Notifications</a>-->
       </div>
    </div>
    
    <style>
       /* Hide notifications dropdown on small screens */
       @media (max-width: 768px) {
          #notifications {
             display: none;
          }
       }
    </style>


</header>

<script>
// Toggle notification dropdown
document.getElementById('notification-btn').addEventListener('click', function() {
    document.getElementById('notifications').classList.toggle('show');
});

// Mark notification as read when removed
function removeNotification(notificationId) {
    // AJAX call to mark notification as read in the database
    fetch(`mark_notification_read.php?id=${notificationId}`)
        .then(response => response.text())
        .then(data => {
            // Remove notification from the UI
            document.querySelector(`[data-id="${notificationId}"]`).remove();
        });
}
</script>
