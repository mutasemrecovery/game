<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.orders')); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>


    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.orders')); ?> </h3>
            <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">

            <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-success"> <?php echo e(__('messages.New')); ?>

                <?php echo e(__('messages.orders')); ?></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
                   <form method="GET" action="<?php echo e(route('orders.index')); ?>">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label><?php echo e(__('messages.from_date')); ?></label>
                                <input type="date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label><?php echo e(__('messages.to_date')); ?></label>
                                <input type="date" name="to_date" class="form-control" value="<?php echo e(request('to_date')); ?>">
                            </div>
                            <div class="col-md-2">
                                <label><?php echo e(__('messages.Number')); ?></label>
                                <input type="text" name="number" class="form-control" placeholder="Search #" value="<?php echo e(request('number')); ?>">
                            </div>
                            <div class="col-md-2">
                                <label><?php echo e(__('messages.User')); ?></label>
                                <input type="text" name="user_name" class="form-control" placeholder="User Name" value="<?php echo e(request('user_name')); ?>">
                            </div>
                            <div class="col-md-2">
                                <label><?php echo e(__('messages.delivery_place')); ?></label>
                                <select name="delivery_place" class="form-control">
                                    <option value=""><?php echo e(__('messages.choose')); ?></option>
                                    <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($delivery->place); ?>" <?php echo e(request('delivery_place') == $delivery->place ? 'selected' : ''); ?>>
                                            <?php echo e($delivery->place); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-1">
                                <button type="submit" class="btn btn-primary w-100"><?php echo e(__('messages.Search')); ?></button>
                                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary w-100"><?php echo e(__('messages.reset')); ?></a>
                            </div>
                        </div>
                    </form>



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
                                <th><?php echo e(__('messages.delivery')); ?></th>
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
                                        <td>
                                            <?php if($info->order_status == 1): ?>
                                                Pending
                                            <?php elseif($info->order_status == 2): ?>
                                                OnTheWay
                                            <?php elseif($info->order_status == 3): ?>
                                                Cancelled
                                            <?php elseif($info->order_status == 4): ?>
                                                Failed
                                            <?php else: ?>
                                                DELIVERD
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($info->user->name); ?></td>
                                        <td><?php echo e($info->delivery->place); ?></td>
                                        <td><?php echo e($info->date); ?></td>

                                        <td>
                                            <?php if($info->order_status != 6 && $info->order_status != 3): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                                                    <a href="<?php echo e(route('orders.edit', $info->id)); ?>"
                                                        class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-table')): ?>
                                                    <a href="<?php echo e(route('orders.show', $info->id)); ?>"
                                                        class="btn btn-sm btn-secondary"><?php echo e(__('messages.Show')); ?></a>
                                             <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <br>
                       <?php echo e($data->appends(request()->query())->links()); ?>


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