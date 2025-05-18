<?php $__env->startSection('title'); ?>

edit orders
<?php $__env->stopSection(); ?>



<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('orders.index')); ?>">  orders </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
Edit
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> edit orders </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


      <form action="<?php echo e(route('orders.update',$data['id'])); ?>" method="post" enctype='multipart/form-data'>
        <div class="row">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>


                <div class="form-group col-md-6">
                    <label for="name">total_taxes <span class="text-danger">*</span></label>
                    <input type="text" name="total_taxes" id="name" class="form-control" value="<?php echo e($data->total_taxes); ?>">
                    <?php $__errorArgs = ['total_taxes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">delivery_fee <span class="text-danger">*</span></label>
                    <input type="text" name="delivery_fee" id="name" class="form-control" value="<?php echo e($data->delivery_fee); ?>">
                    <?php $__errorArgs = ['delivery_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">total_prices <span class="text-danger">*</span></label>
                    <input type="text" name="total_prices" id="name" class="form-control" value="<?php echo e($data->total_prices); ?>">
                    <?php $__errorArgs = ['total_prices'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">total_discounts <span class="text-danger">*</span></label>
                    <input type="text" name="total_discounts" id="name" class="form-control" value="<?php echo e($data->total_discounts); ?>">
                    <?php $__errorArgs = ['total_discounts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group col-md-6">
                    <label for="name">payment_type <span class="text-danger">*</span></label>
                    <input type="text" name="payment_type" id="name" class="form-control" value="<?php echo e($data->payment_type); ?>">
                    <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>




                <div class="form-group col-md-6">
                    <label for="order_status">order_status</label>
                    <select name="order_status" id="order_status" class="form-control">
                        <option value="">Select</option>
                        <option value="1" <?php if($data->order_status == 1): ?> selected="selected" <?php endif; ?>>Pending</option>
                        <option value="2" <?php if($data->order_status == 2): ?> selected="selected" <?php endif; ?>>OnTheWay</option>
                        <option value="3" <?php if($data->order_status == 3): ?> selected="selected" <?php endif; ?>>Cancelled</option>
                        <option value="4" <?php if($data->order_status == 4): ?> selected="selected" <?php endif; ?>>Failed</option>
                        <option value="5" <?php if($data->order_status == 5): ?> selected="selected" <?php endif; ?>>DELIVERD</option>
                    </select>
                    <?php $__errorArgs = ['order_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>


      <div class="col-md-12">
      <div class="form-group text-center">
        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> update</button>
        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-sm btn-danger">cancel</a>

      </div>
    </div>

  </div>
            </form>



            </div>




        </div>
      </div>






<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/admin/orders/edit.blade.php ENDPATH**/ ?>