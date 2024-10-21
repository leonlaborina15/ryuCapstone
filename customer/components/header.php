<?php
function renderHeader($customer_name = 'Customer Name')
{
  // Get the current page URL
  $current_page = basename($_SERVER['PHP_SELF']);
?>
  <div class="header">
    <div>OCDS</div>
    <div class="header-links">
      <a href="customer_view_replies.php" class="dashboard-item <?php echo $current_page == 'customer_view_replies.php' ? 'active' : ''; ?>">Messages</a>
      <a href="browse_cars.php" class="dashboard-item <?php echo $current_page == 'browse_cars.php' ? 'active' : ''; ?>">Cars</a>
      <a href="view_purchases.php" class="dashboard-item <?php echo $current_page == 'view_purchases.php' ? 'active' : ''; ?>">Purchases</a>
      <a href="buy_car.php" class="dashboard-item <?php echo $current_page == 'buy_car.php' ? 'active' : ''; ?>">Buy</a>
      <a href="messages.php" class="dashboard-item <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>">Contact Us</a>
      |
      <span><?php echo $customer_name ?></span>
    </div>
  </div>
<?php
}
?>