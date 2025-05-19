<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.orders')); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>


      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> <?php echo e(__('messages.orders')); ?> </h3>
          <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">

          <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-success" > <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.orders')); ?></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-table')): ?>
            <?php if(isset($data) && !$data->isEmpty()): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th><?php echo e(__('messages.number')); ?></th>
                    <th><?php echo e(__('messages.total_prices')); ?></th>
                    <th><?php echo e(__('messages.delivery_fee')); ?></th>
                    <th><?php echo e(__('messages.total_discounts')); ?></th>
                    <th><?php echo e(__('messages.order_status')); ?></th>
                    <th><?php echo e(__('messages.user')); ?></th>
                    <th><?php echo e(__('messages.date')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>


                        <td><?php echo e($info->number); ?></td>
                        <td><?php echo e($info->total_prices); ?></td>
                        <td><?php echo e($info->delivery_fee); ?></td>
                        <td><?php echo e($info->total_discounts); ?></td>
                        <td><?php if($info->order_status==1): ?> Pending <?php elseif($info->order_status==2): ?> OnTheWay <?php elseif($info->order_status==3): ?> Cancelled <?php elseif($info->order_status==4): ?> Failed <?php else: ?> DELIVERD <?php endif; ?></td>
                        <td><?php echo e($info->user->name); ?></td>
                        <td><?php echo e($info->date); ?></td>

                        <td>
                             <?php if($info->order_status != 6 && $info->order_status != 3 ): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $info->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                            <?php endif; ?>
                            <?php endif; ?>
                           
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <br>
            <?php echo e($data->links()); ?>


            <?php else: ?>
            <div class="alert alert-danger">
                <?php echo e(__('messages.No_data')); ?> </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>



      </div>

        </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\game\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>