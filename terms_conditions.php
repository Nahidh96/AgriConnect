<?php

@include 'config.php';

session_start();

$user_id = $_COOKIE['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Learn more about Agriconnect and our mission to promote organic farming and sustainable agriculture. Discover how we connect farmers and consumers in Sri Lanka.">
   <meta name="keywords" content="about us, Agriconnect, organic farming, sustainable agriculture, Sri Lanka, Nahidh Naseem">
   <meta name="robots" content="index, follow">
   <link rel="canonical" href="https://www.agriconnect.lk/about">
   <meta name="author" content="Nahidh Naseem | Agriconnect Team">
   <title>About | Agriconnect</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <!-- favicon -->
   <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>

<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <h3>Seller Terms and Conditions</h3>
         <p><strong>Last updated:</strong> 9.13.2024</p>

         <h4>1. Eligibility</h4>
         <p>To become a seller on Agriconnect, you must be 18 years of age or older and legally permitted to enter into binding contracts.</p>
         <p>You agree to provide accurate and complete information during the registration process and update it as necessary.</p>

         <h4>2. Seller Obligations</h4>
         <p><strong>Product Listings:</strong> You are responsible for the accuracy and completeness of the product information you provide. This includes product description, pricing, and availability.</p>
         <p><strong>Compliance with Laws:</strong> All products sold on Agriconnect must comply with local, state, and national laws, including agricultural and safety regulations.</p>
         <p><strong>Quality Assurance:</strong> Sellers are required to ensure that the products they offer meet the quality standards promised in their listing. Any failure to meet these standards could lead to account suspension or termination.</p>

         <h4>3. Prohibited Items</h4>
         <p>Sellers are prohibited from listing illegal or restricted items on Agriconnect. This includes, but is not limited to, counterfeit products, endangered species, and harmful substances.</p>

         <h4>4. Pricing and Payments</h4>
         <p><strong>Pricing:</strong> You agree to set fair and accurate prices for your products. Price gouging, misleading pricing, and false discounts are prohibited.</p>
         <p><strong>Payments:</strong> Agriconnect processes all payments securely. You will receive payments directly into your designated bank account after deducting the platform’s commission fees, as outlined in the fee schedule.</p>
         <p><strong>Taxes:</strong> Sellers are responsible for determining and paying applicable taxes on their sales.</p>

         <h4>5. Order Fulfillment and Shipping</h4>
         <p><strong>Order Processing:</strong> Sellers must process orders in a timely manner and ship products within the stated time frame.</p>
         <p><strong>Shipping Costs:</strong> Sellers may charge a shipping fee, but it must be reasonable and clearly stated in the product listing.</p>
         <p><strong>Returns and Refunds:</strong> Sellers must provide a fair return policy. In case of damaged or defective products, you must offer a refund or replacement to the buyer.</p>

         <h4>6. Seller Performance</h4>
         <p>Agriconnect monitors seller performance based on factors such as customer feedback, product quality, shipping times, and dispute resolution. Repeated failure to meet performance standards may result in account suspension or termination.</p>

         <h4>7. Intellectual Property</h4>
         <p>You must have the right to sell any products listed, including all associated intellectual property rights, such as trademarks and copyrights. Any violations will result in immediate removal of the product and potential legal action.</p>

         <h4>8. Termination of Seller Account</h4>
         <p>Agriconnect reserves the right to terminate your seller account at any time, with or without notice, for violations of these terms or any illegal activity.</p>

         <h4>9. Limitation of Liability</h4>
         <p>Agriconnect is not responsible for any losses incurred by the seller as a result of using the platform, including lost sales or technical issues.</p>

         <h4>10. Dispute Resolution</h4>
         <p>In case of disputes between buyers and sellers, Agriconnect will mediate the issue in good faith. However, it is encouraged that both parties attempt to resolve disputes amicably before seeking Agriconnect’s intervention.</p>

         <h4>11. Changes to Terms</h4>
         <p>Agriconnect reserves the right to change these terms and conditions at any time. Sellers will be notified of any significant changes, and continued use of the platform constitutes agreement to the updated terms.</p>

         <p>By registering as a seller, you acknowledge that you have read, understood, and agreed to these terms and conditions.</p>

         <a href="seller_r.php" class="btn">Join as a seller</a>
      </div>

   </div>

</section>


<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
