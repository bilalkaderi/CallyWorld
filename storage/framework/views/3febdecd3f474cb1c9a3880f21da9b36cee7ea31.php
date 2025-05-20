<!DOCTYPE html>
<html>
<head>
  <title>Bank Card Information Form</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    .card-form {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card-form">
      <h2>Bank Card Information</h2>
      <?php echo csrf_field(); ?>
      <form action="<?php echo e(route('process.payment')); ?>" method="POST">
        <div class="form-group">
          <label for="card-number">Card Number</label>
          <input type="text" class="form-control" id="card-number" name="cardNumber" placeholder="Enter card number" required>
        </div>
        <div class="form-group">
          <label for="card-holder">Card Holder</label>
          <input type="text" class="form-control" id="card-holder" name="cardHolder" placeholder="Enter card holder name" required>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="expiry-date">Expiry Date</label>
            <input type="text" class="form-control" id="expiry-date" name="cardExpiry" placeholder="MM/YY" required>
          </div>
          <div class="form-group col-md-6">
            <label for="cvv">CVV</label>
            <input type="text" class="form-control" id="cvv" placeholder="CVV" name="cardCVV" required>
          </div>
        </div>
    <!-- Existing form fields -->
    <!-- ... -->
        <script src="https://checkout.stripe.com/checkout.js"
                class="stripe-button"
                data-key="<?php echo e(config('services.stripe.key')); ?>"
                data-amount="10000"
                data-name="Your Company"
                data-description="Payment Description"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-currency="usd">
        </script>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/layouts/bank_card.blade.php ENDPATH**/ ?>