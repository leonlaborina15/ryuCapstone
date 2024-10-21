<?php
function renderHeader($customer_name = 'Customer Name')
{
  // Get the current page URL
  $current_page = basename($_SERVER['PHP_SELF']);
?>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <!-- <a class="navbar-brand" href="#">OCDS</a> -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'customer_view_replies.php' ? 'active' : ''; ?>" href="customer_view_replies.php">Messages</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'browse_cars.php' ? 'active' : ''; ?>" href="browse_cars.php">Cars</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'view_purchases.php' ? 'active' : ''; ?>" href="view_purchases.php">Purchases</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'buy_car.php' ? 'active' : ''; ?>" href="buy_car.php">Buy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>" href="messages.php">Contact Us</a>
          </li>
          <!-- <li class="nav-item">
            <span class="navbar-text ml-3"><?php echo $customer_name ?></span>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>
<?php
}
?>