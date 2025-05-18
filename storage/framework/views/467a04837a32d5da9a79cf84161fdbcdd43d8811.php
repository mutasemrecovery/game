

<?php $__env->startSection('content'); ?>
<section class="orders-section">
    <div class="orders-header">
      <h2 class="orders-title"><?php echo e(__('messages.My Orders')); ?> (<?php echo e($orders->count()); ?>)</h2>
      <div class="orders-status-legend">
        <span class="legend-item pending">● <?php echo e(__('messages.Pending')); ?></span>
        <span class="legend-item confirmed">● <?php echo e(__('messages.Confirmed')); ?></span>
        <span class="legend-item on-way">● <?php echo e(__('messages.On the way')); ?></span>
        <span class="legend-item delivered">● <?php echo e(__('messages.Delivered')); ?></span>
        <span class="orders-total"><?php echo e(__('messages.Total')); ?> <?php echo e($totalAmount); ?> JD</span>
      </div>
    </div>
  
    <div class="orders-list">
      <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="order-card">
          <div class="order-col status-col">
            <?php
              $statusClass = '';
              $statusInitial = '';
              
              switch($order->order_status) {
                case 1:
                  $statusClass = 'pending';
                  $statusInitial = 'Pe';
                  break;
                case 2:
                  $statusClass = 'on-way';
                  $statusInitial = 'Ow';
                  break;
                case 3:
                  $statusClass = 'cancelled';
                  $statusInitial = 'Ca';
                  break;
                case 4:
                  $statusClass = 'failed';
                  $statusInitial = 'Fa';
                  break;
                case 5:
                  $statusClass = 'refund';
                  $statusInitial = 'Re';
                  break;
                case 6:
                  $statusClass = 'delivered';
                  $statusInitial = 'De';
                  break;
                default:
                  $statusClass = 'pending';
                  $statusInitial = 'Pe';
              }
            ?>
            <div class="status-circle <?php echo e($statusClass); ?>"><?php echo e($statusInitial); ?></div>
          </div>
          
          <div class="order-col id-col">
            #<?php echo e($order->id); ?>

          </div>
          
          <div class="order-col time-col">
            <div><?php echo e($order->date->format('H:i')); ?></div>
            <div><?php echo e($order->date->format('d/m/y')); ?></div>
          </div>
          
          <div class="order-col time-col">
            <?php echo e($order->branch->name); ?>

          </div>
          <div class="order-col details-col">
            <?php $products = $order->products()->with('product')->take(3)->get(); ?>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php echo e($item->product->name_en); ?> x <?php echo e($item->quantity); ?><br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($order->products->count() > 3): ?>
              <span class="more-items">+ <?php echo e($order->products->count() - 3); ?> <?php echo e(__('messages.more items')); ?></span>
            <?php endif; ?>
          </div>
          
          <div class="order-col price-col">
            <?php echo e($order->total_prices); ?> JD
          </div>
          
          <div class="order-col state-col <?php echo e($statusClass); ?>-text">
            <?php echo e($order->getStatusText()); ?>

          </div>
          
          <div class="order-col arrow-col">
            <i class="fa fa-chevron-right"></i>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="no-orders">
          <i class="fa fa-shopping-bag fa-4x"></i>
          <h3><?php echo e(__('messages.No orders yet')); ?></h3>
          <p><?php echo e(__('messages.You haven\'t placed any orders yet')); ?></p>
          <a href="<?php echo e(route('menu')); ?>" class="browse-menu-btn"><?php echo e(__('messages.Browse Menu')); ?></a>
        </div>
      <?php endif; ?>
    </div>
    
    <?php if($orders->count() > 0 && method_exists($orders, 'links')): ?>
    <div class="orders-pagination">
      <?php echo e($orders->links()); ?>

    </div>
    <?php endif; ?>
  </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/orders.blade.php ENDPATH**/ ?>