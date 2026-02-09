 <!-- Footer -->
 <footer style="background-color: #f9f9f9; border-top: 1px solid #ddd; padding: 40px 0; margin-top: 50px;">
     <div class="container">
         <div class="row">
             <div class="col-md-3">
                 <h5><strong>About Us</strong></h5>
                 <p>Your trusted online store for quality products at great prices. We're committed to providing excellent customer service.</p>
             </div>
             <div class="col-md-3">
                 <h5><strong>Quick Links</strong></h5>
                 <ul class="list-unstyled">
                     <li><a href="index.php">Home</a></li>
                     <li><a href="shop.php">Shop</a></li>
                     <li><a href="cart.php">My Cart</a></li>
                     <li><a href="contact.php">Contact Us</a></li>
                 </ul>
             </div>
             <div class="col-md-3">
                 <h5><strong>Customer Support</strong></h5>
                 <ul class="list-unstyled">
                     <li><a href="#">Shipping Info</a></li>
                     <li><a href="#">Returns</a></li>
                     <li><a href="#">FAQ</a></li>
                     <li><a href="#">Terms & Conditions</a></li>
                 </ul>
             </div>
             <div class="col-md-3">
                 <h5><strong>Contact Info</strong></h5>
                 <p>
                     <strong>Email:</strong> support@store.com<br>
                     <strong>Phone:</strong> 1-800-123-4567<br>
                     <strong>Hours:</strong> Mon-Fri 9AM-5PM EST
                 </p>
             </div>
         </div>
         <hr>
         <div class="row">
             <div class="col-lg-12 text-center">
                 <p style="color: #666;">
                     <span class="glyphicon glyphicon-copyright-mark"></span> Copyright &copy; <?php echo date('Y'); ?> My Online Store. All Rights Reserved.
                 </p>
                 <p style="color: #999; font-size: 12px;">
                     Secure Shopping | Easy Returns | 24/7 Support
                 </p>
             </div>
         </div>
     </div>
 </footer>

 </div>
 <!-- /.container -->

 <!-- jQuery -->
 <script src="js/jquery.js"></script>

 <!-- Bootstrap Core JavaScript -->
 <script src="js/bootstrap.min.js"></script>

 <script>
     // Update price range display
     document.addEventListener('DOMContentLoaded', function() {
         var minPriceInput = document.querySelector('input[name="min_price"]');
         var maxPriceInput = document.querySelector('input[name="max_price"]');
         var minPriceDisplay = document.getElementById('minPrice');
         var maxPriceDisplay = document.getElementById('maxPrice');

         if (minPriceInput) {
             minPriceInput.addEventListener('change', function() {
                 if (minPriceDisplay) minPriceDisplay.textContent = this.value;
             });
         }
         if (maxPriceInput) {
             maxPriceInput.addEventListener('change', function() {
                 if (maxPriceDisplay) maxPriceDisplay.textContent = this.value;
             });
         }
     });
 </script>

 </body>

 </html>