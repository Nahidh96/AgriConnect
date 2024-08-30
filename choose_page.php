<?php
include 'config.php';

// Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_COOKIE['user_id'];
$select = $conn->prepare("SELECT user_type FROM `users` WHERE id = ?");
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);
$user_type = $user['user_type'];

// Redirect if user is not a seller
if ($user_type !== 'seller') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Choose Your Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- internal css for styling and responsiveness -->
   <style>
      /* Custom background color for the body */
      body.custom-bg {
          background-color: #f4f4f4;
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
      }

      /* Styling the form container */
      .form-container {
          background-color: #fff;
          padding: 20px 40px;
          border-radius: 8px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          text-align: center;
          max-width: 400px;
          width: 100%;
      }

      /* Heading styles */
      .form-container h3 {
          margin-bottom: 15px;
          color: #333;
          font-size: 24px;
      }

      /* Paragraph styles */
      .form-container p {
          margin-bottom: 20px;
          color: #666;
          font-size: 16px;
      }

      /* Button styles */
      .form-container .btn {
          display: block;
          margin: 10px 0;
          padding: 10px 15px;
          background-color: #28a745;
          color: #fff;
          border: none;
          border-radius: 5px;
          text-decoration: none;
          font-size: 16px;
          transition: background-color 0.3s;
      }

      /* Button hover effect */
      .form-container .btn:hover {
          background-color: #218838;
      }

      /* Responsive design for smaller screens */
      @media (max-width: 480px) {
          .form-container {
              padding: 15px 20px;
          }

          .form-container h3 {
              font-size: 20px;
          }

          .form-container p {
              font-size: 14px;
          }

          .form-container .btn {
              font-size: 14px;
              padding: 8px 12px;
          }
      }
   </style>

</head>
<body class="custom-bg">

<section class="form-container">
   <h3>Choose Your Dashboard</h3>
   <p>You are registered as both a user and a seller. Please choose where you want to go:</p>
   <a href="seller_page.php" class="btn">Go to Seller Dashboard</a>
   <a href="index.php" class="btn">Go to Homepage</a>
</section>

</body>
</html>
