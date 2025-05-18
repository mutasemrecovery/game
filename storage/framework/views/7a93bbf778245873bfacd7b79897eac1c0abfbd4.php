

<?php $__env->startSection('content'); ?>
<section class="cart-section">
    <div class="cart-container">
      <h2 class="cart-title"><?php echo e(__('messages.Your Cart')); ?></h2>
      
      <?php if(session('success')): ?>
        <div class="alert alert-success">
          <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>
      
      <?php if($cartItems->count() > 0): ?>
        <form action="<?php echo e(route('cart.update')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <table class="cart-table">
            <thead>
              <tr>
                <th></th>
                <th><?php echo e(__('messages.Item')); ?></th>
                <th><?php echo e(__('messages.Price')); ?></th>
                <th><?php echo e(__('messages.Quantity')); ?></th>
                <th><?php echo e(__('messages.Subtotal')); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td class="cart-remove">
                    <a href="<?php echo e(route('cart.remove', $item->id)); ?>" onclick="return confirm('<?php echo e(__('messages.Are you sure you want to remove this item?')); ?>')">x</a>
                  </td>
                  <td class="cart-item">
                    <?php if($item->product->productImages->count() > 0): ?>
                      <img src="<?php echo e(asset('assets/admin/uploads/' . $item->product->productImages->first()->photo)); ?>" alt="<?php echo e($item->product->name_en); ?>">
                    <?php else: ?>
                      <img src="<?php echo e(asset('assets/images/placeholder.png')); ?>" alt="<?php echo e($item->product->name_en); ?>">
                    <?php endif; ?>
                    <div>
                      <?php echo e(app()->getLocale() == 'en' ? $item->product->name_en : $item->product->name_ar); ?>

                      <?php if($item->offer): ?>
                        <span class="offer-badge"><?php echo e(__('messages.On Sale')); ?></span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="cart-price"><strong><?php echo e($item->price); ?> JD</strong></td>
                  <td class="cart-quantity">
                    <input type="number" name="quantity[<?php echo e($item->id); ?>]" min="1" value="<?php echo e($item->quantity); ?>">
                  </td>
                  <td class="cart-subtotal"><strong><?php echo e($item->total_price_product); ?> JD</strong></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
          
          <div class="cart-actions">
            <div class="coupon-box">
              <input type="text" name="code" placeholder="<?php echo e(__('messages.Coupon Code')); ?>">
              <button type="submit" formaction="<?php echo e(route('cart.apply-coupon')); ?>" class="apply-coupon-btn"><?php echo e(__('messages.Apply Coupon')); ?></button>
            </div>
            <button type="submit" class="update-cart-btn"><i class="fa fa-rotate-right"></i> <?php echo e(__('messages.Update Cart')); ?></button>
          </div>
        </form>
        
        <div class="cart-totals">
          <h3><?php echo e(__('messages.Cart Totals')); ?></h3>
          <table>
            <tr>
              <td><?php echo e(__('messages.Subtotal')); ?></td>
              <td><?php echo e($subtotal); ?> JD</td>
            </tr>
            <?php if($totalDiscount > 0): ?>
              <tr>
                <td><?php echo e(__('messages.Discount')); ?></td>
                <td><?php echo e($totalDiscount); ?> JD</td>
              </tr>
            <?php endif; ?>
         
            <tr>
              <td><?php echo e(__('messages.Total')); ?></td>
              <td><strong><?php echo e($total); ?> JD</strong></td>
            </tr>
          </table>
          <form action="<?php echo e(route('checkout')); ?>" method="GET">
            <button type="submit" class="checkout-btn"><?php echo e(__('messages.Checkout')); ?></button>
          </form>
        </div>
      <?php else: ?>
        <div class="empty-cart">
          <p><?php echo e(__('messages.Your cart is empty')); ?></p>
          <a href="<?php echo e(route('menu')); ?>" class="continue-shopping"><?php echo e(__('messages.Continue Shopping')); ?></a>
        </div>
      <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/cart.blade.php ENDPATH**/ ?>