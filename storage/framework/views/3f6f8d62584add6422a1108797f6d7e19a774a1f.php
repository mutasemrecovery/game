<footer class="footer-section">
    <div class="footer-container">
      <div class="footer-columns">
        
        <div class="footer-column">
          <h4 class="footer-heading"><?php echo e(__('messages.Home')); ?></h4>
          <ul class="footer-links">
            <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('messages.Home')); ?></a></li>
            <li><a href="#"><?php echo e(__('messages.About')); ?> </a></li>
            <li><a href="<?php echo e(route('menu')); ?>"><?php echo e(__('messages.Menu')); ?></a></li>
          </ul>
        </div>
  
        <div class="footer-column">
          <h4 class="footer-heading"><?php echo e(__('messages.Help')); ?></h4>
          <ul class="footer-links">
            <li><a href="<?php echo e(route('terms')); ?>"><?php echo e(__('messages.Terms of Use')); ?></a></li>
            <li><a href="<?php echo e(route('policy')); ?>"><?php echo e(__('messages.Privacy Policy')); ?></a></li>
          </ul>
        </div>
  
        <div class="footer-column">
          <h4 class="footer-heading"><?php echo e(__('messages.Contacts')); ?></h4>
          <ul class="footer-contacts">
            <li><i class="fa fa-map-marker-alt"></i> Amman - 7th Circle</li>
            <li><i class="fa fa-phone"></i> 00962795579421</li>
            <li><i class="fa fa-envelope"></i> <a href="mailto:info@dynamitebox.com">info@dynamitebox.com</a></li>
          </ul>
        </div>
  
        <div class="footer-column">
          <h4 class="footer-heading"><?php echo e(__('messages.Social Media')); ?></h4>
          <div class="footer-social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
          <div class="footer-payment">
            <img src="<?php echo e(asset('assets_front/assets/images/visa.png')); ?>" alt="Visa" />
            <img src="<?php echo e(asset('assets_front/assets/images/mastercard.png')); ?>" alt="MasterCard" />
          </div>
        </div>
  
      </div>
  
      <div class="footer-bottom">
        <p>©  <?php echo e(__('messages.2025 All rights reserved design by Recovery')); ?></p>
      </div>
    </div>
  </footer>
  <?php /**PATH C:\laragon\www\dynamite\resources\views/user/includes/footer.blade.php ENDPATH**/ ?>