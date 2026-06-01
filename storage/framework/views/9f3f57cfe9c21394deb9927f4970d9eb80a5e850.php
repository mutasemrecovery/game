<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.conflict-product { color: #dc3545; font-weight: 600; }
.conflict-badge { font-size: .7rem; }
.today-conflict-row { border-right: 4px solid #dc3545 !important; background: #fff5f5 !important; }
.today-conflict-bar {
    background: #dc3545; color: #fff; font-size: 0.78rem; font-weight: 700;
    padding: 3px 10px; border-radius: 4px; display: inline-block;
    animation: blink-alert 1.2s step-start infinite;
}
@keyframes blink-alert { 50% { opacity: 0.4; } }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.orders')); ?> </h3>
            <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">
            <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-success">
                <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.orders')); ?>

            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="<?php echo e(route('orders.index')); ?>">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label><?php echo e(__('messages.from_date')); ?></label>
                        <input type="date" name="from_date" class="form-control"
                            value="<?php echo e(request('from_date', $filters['from_date'] ?? '')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label><?php echo e(__('messages.to_date')); ?></label>
                        <input type="date" name="to_date" class="form-control"
                            value="<?php echo e(request('to_date', $filters['to_date'] ?? '')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label><?php echo e(__('messages.Number')); ?></label>
                        <input type="text" name="number" class="form-control" placeholder="Search #"
                            value="<?php echo e(request('number', $filters['number'] ?? '')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label><?php echo e(__('messages.User')); ?></label>
                        <input type="text" name="user_name" class="form-control" placeholder="User Name"
                            value="<?php echo e(request('user_name', $filters['user_name'] ?? '')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label><?php echo e(__('messages.delivery_place')); ?></label>
                        <select name="delivery_place" class="form-control">
                            <option value=""><?php echo e(__('messages.choose')); ?></option>
                            <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($delivery->place); ?>"
                                    <?php echo e(request('delivery_place', $filters['delivery_place'] ?? '') == $delivery->place ? 'selected' : ''); ?>>
                                    <?php echo e($delivery->place); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-primary w-100"><?php echo e(__('messages.Search')); ?></button>
                        <a href="<?php echo e(route('orders.index', ['reset' => 1])); ?>" class="btn btn-secondary w-100">
                            <?php echo e(__('messages.reset')); ?>

                        </a>
                    </div>
                </div>
            </form>

            <div class="mb-3">
                <a href="<?php echo e(route('orders.export', request()->query())); ?>"
                   class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> <?php echo e(__('messages.Export Excel')); ?>

                </a>
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
                                <th><?php echo e(__('messages.delivery')); ?></th>
                                <th><?php echo e(__('messages.date')); ?></th>
                                <th><?php echo e(__('messages.Phone')); ?></th>
                                <th><?php echo e(__('messages.products')); ?></th>
                                <th><?php echo e(__('messages.Action')); ?></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $hasConflict = $info->orderProducts->pluck('product_id')
                                            ->intersect($conflictedProductIds)->isNotEmpty();
                                        $isTodayConflict = in_array($info->id, $todayConflictOrderIds);
                                    ?>
                                    <tr class="<?php echo e($isTodayConflict ? 'today-conflict-row' : ($hasConflict ? '' : '')); ?>"
                                        <?php if($hasConflict && !$isTodayConflict): ?> style="border-right: 3px solid #ffc107;" <?php endif; ?>>
                                        <td><?php echo e($info->number); ?></td>
                                        <td><?php echo e($info->total_prices); ?></td>
                                        <td><?php echo e($info->delivery_fee); ?></td>
                                        <td><?php echo e($info->total_discounts); ?></td>
                                        <td>
                                            <?php if($info->order_status == 1): ?>
                                                <span class="badge badge-warning"><?php echo e(__('messages.Pending')); ?></span>
                                            <?php elseif($info->order_status == 2): ?>
                                                <span class="badge badge-info"><?php echo e(__('messages.Processing')); ?></span>
                                            <?php elseif($info->order_status == 3): ?>
                                                <span class="badge badge-danger"><?php echo e(__('messages.Cancelled')); ?></span>
                                            <?php elseif($info->order_status == 4): ?>
                                                <span class="badge badge-secondary"><?php echo e(__('messages.failed')); ?></span>
                                            <?php elseif($info->order_status == 6): ?>
                                                <span class="badge badge-success"><?php echo e(__('messages.Executed')); ?></span>
                                            <?php elseif($info->order_status == 7): ?>
                                                <span class="badge badge-primary"><?php echo e(__('messages.Returned')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-light"><?php echo e($info->order_status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($info->user->name); ?></td>
                                        <td><?php echo e($info->delivery->place ?? null); ?><br><?php echo e($info->address); ?></td>
                                        <td>
                                            <?php echo e(\Carbon\Carbon::parse($info->date)->format('d/m/Y')); ?><br>
                                            <small class="text-muted">
                                                <?php echo e(\Carbon\Carbon::parse($info->date)->format('g:i')); ?>

                                                <?php echo e(\Carbon\Carbon::parse($info->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                                            </small>
                                            <?php if($info->end_date): ?>
                                                <br><small class="text-danger">
                                                    ← <?php echo e(\Carbon\Carbon::parse($info->end_date)->format('d/m/Y')); ?>

                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($info->user->phone ?? '-'); ?></td>
                                        <td>
                                            <?php if($isTodayConflict): ?>
                                                <span class="today-conflict-bar mb-1 d-block">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    <?php echo e(__('messages.Character not returned - urgent')); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php $__currentLoopData = $info->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $isConflict = in_array($item->product_id, $conflictedProductIds); ?>
                                                <small <?php if($isConflict): ?> class="conflict-product" title="<?php echo e(__('messages.Conflict Warning')); ?>" <?php endif; ?>>
                                                    <?php if($isConflict): ?><i class="fas fa-exclamation-triangle"></i> <?php endif; ?>
                                                    <?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)
                                                </small><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td style="min-width:160px;">
                                            
                                            <?php if(in_array($info->order_status, [1, 2])): ?>
                                                <button class="btn btn-xs btn-success mb-1 btn-quick-status"
                                                    data-id="<?php echo e($info->id); ?>" data-status="6"
                                                    title="<?php echo e(__('messages.Mark as Executed')); ?>">
                                                    <i class="fas fa-check"></i> <?php echo e(__('messages.Executed')); ?>

                                                </button>
                                                <button class="btn btn-xs btn-danger mb-1 btn-quick-status"
                                                    data-id="<?php echo e($info->id); ?>" data-status="3"
                                                    title="<?php echo e(__('messages.Cancel')); ?>">
                                                    <i class="fas fa-times"></i> <?php echo e(__('messages.Cancel')); ?>

                                                </button>
                                            <?php endif; ?>

                                            <?php if($info->order_status == 6): ?>
                                                <button class="btn btn-xs btn-primary mb-1 btn-quick-status"
                                                    data-id="<?php echo e($info->id); ?>" data-status="7"
                                                    title="<?php echo e(__('messages.Mark as Returned')); ?>">
                                                    <i class="fas fa-undo"></i> <?php echo e(__('messages.Returned')); ?>

                                                </button>
                                            <?php endif; ?>

                                            
                                            <?php if(!in_array($info->order_status, [3, 7])): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                                                    <a href="<?php echo e(route('orders.edit', $info->id)); ?>"
                                                        class="btn btn-sm btn-warning mb-1"><?php echo e(__('messages.Edit')); ?></a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-table')): ?>
                                                <a href="<?php echo e(route('orders.show', $info->id)); ?>"
                                                    class="btn btn-sm btn-secondary mb-1"><?php echo e(__('messages.Show')); ?></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <br>
                        <?php echo e($data->appends(request()->query())->links()); ?>

                    <?php else: ?>
                        <div class="alert alert-danger"><?php echo e(__('messages.No_data')); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function () {
    $(document).on('click', '.btn-quick-status', function () {
        var btn    = $(this);
        var id     = btn.data('id');
        var status = btn.data('status');

        var labels = { 6: '<?php echo e(__("messages.Executed")); ?>', 3: '<?php echo e(__("messages.Cancelled")); ?>', 7: '<?php echo e(__("messages.Returned")); ?>' };
        if (!confirm('<?php echo e(__("messages.Confirm action")); ?>: ' + labels[status] + ' ?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '<?php echo e(url("")); ?>/<?php echo e(LaravelLocalization::getCurrentLocale()); ?>/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '<?php echo e(csrf_token()); ?>', status: status },
            success: function (res) {
                if (res.success) {
                    location.reload();
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || '<?php echo e(__("messages.Error creating order: ")); ?>');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\game\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>