<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Out Not Returned Orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.days-badge  { font-size:.78rem; font-weight:700; padding:2px 8px; border-radius:10px; }
.product-pill{ display:inline-block; background:#e9ecef; border-radius:20px;
               padding:1px 8px; font-size:.78rem; margin:1px; }
.urgent-row  { border-right: 4px solid #dc3545 !important; background:#fff5f5; }
.normal-row  { border-right: 4px solid #fd7e14 !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-undo text-danger mr-2"></i>
            <?php echo e(__('messages.Out Not Returned Orders')); ?>

            <span class="badge badge-danger ml-2"><?php echo e($data->total()); ?></span>
        </h3>
    </div>

    <div class="card-body">

        <form method="GET" action="<?php echo e(route('orders.out-not-returned')); ?>" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label><?php echo e(__('messages.date')); ?></label>
                    <input type="date" name="check_date" class="form-control" value="<?php echo e($checkDate); ?>">
                    <small class="text-muted"><?php echo e(__('messages.Shows executed orders before this date')); ?></small>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
                    <a href="<?php echo e(route('orders.out-not-returned')); ?>" class="btn btn-secondary"><?php echo e(__('messages.reset')); ?></a>
                </div>
            </div>
        </form>

        <?php if($data->isEmpty() && $returnedData->isEmpty()): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> <?php echo e(__('messages.No unreturned orders')); ?>

            </div>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table class="table table-bordered table-hover">
            <thead class="custom_thead">
                <tr>
                    <th>#</th>
                    <th><?php echo e(__('messages.date')); ?></th>
                    <th><?php echo e(__('messages.Days Out')); ?></th>
                    <th><?php echo e(__('messages.user')); ?></th>
                    <th><?php echo e(__('messages.Phone')); ?></th>
                    <th><?php echo e(__('messages.products')); ?></th>
                    <th><?php echo e(__('messages.delivery')); ?></th>
                    <th><?php echo e(__('messages.Note')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </tr>
            </thead>
            <tbody id="out-tbody">
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $daysOut    = \Carbon\Carbon::parse($order->date)->diffInDays($today, false);
                        $rowClass   = $daysOut >= 2 ? 'urgent-row' : 'normal-row';
                        $badgeColor = $daysOut >= 2
                            ? 'background:#ffc9c9;color:#c92a2a;'
                            : 'background:#ffd8a8;color:#e67700;';
                    ?>
                    <tr class="<?php echo e($rowClass); ?>">
                        <td><?php echo e($order->number); ?></td>
                        <td>
                            <strong><?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></strong>
                            <?php if($order->end_date): ?>
                                <br><small class="text-danger">← <?php echo e(\Carbon\Carbon::parse($order->end_date)->format('d/m/Y')); ?></small>
                            <?php endif; ?><br>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('g:i')); ?>

                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                            </small>
                        </td>
                        <td>
                            <span class="days-badge" style="<?php echo e($badgeColor); ?>">
                                <?php echo e($daysOut); ?> <?php echo e(__('messages.days')); ?>

                            </span>
                        </td>
                        <td><?php echo e($order->user->name ?? '-'); ?></td>
                        <td><?php echo e($order->user->phone ?? '-'); ?></td>
                        <td>
                            <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-pill"><?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td><?php echo e($order->delivery->place ?? '-'); ?><br><small><?php echo e($order->address); ?></small></td>
                        <td><small><?php echo e($order->note ?? '-'); ?></small></td>
                        <td style="min-width:130px;">
                            <button class="btn btn-sm btn-primary mb-1 btn-quick-status"
                                data-id="<?php echo e($order->id); ?>" data-status="7">
                                <i class="fas fa-undo mr-1"></i><?php echo e(__('messages.Returned')); ?>

                            </button>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-sm btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i><?php echo e(__('messages.Edit')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-sm btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i><?php echo e(__('messages.Show')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $returnedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background:#d4edda; border-right:4px solid #28a745;">
                        <td><?php echo e($order->number); ?></td>
                        <td>
                            <strong><?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></strong><br>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('g:i')); ?>

                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                            </small>
                        </td>
                        <td>
                            <span class="days-badge" style="background:#b2f2bb;color:#2f9e44;">
                                <?php echo e(__('messages.Returned')); ?>

                            </span>
                        </td>
                        <td><?php echo e($order->user->name ?? '-'); ?></td>
                        <td><?php echo e($order->user->phone ?? '-'); ?></td>
                        <td>
                            <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-pill"><?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td><?php echo e($order->delivery->place ?? '-'); ?><br><small><?php echo e($order->address); ?></small></td>
                        <td><small><?php echo e($order->note ?? '-'); ?></small></td>
                        <td style="min-width:100px;">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-sm btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i><?php echo e(__('messages.Edit')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-sm btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i><?php echo e(__('messages.Show')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
        <?php if($data->isNotEmpty()): ?>
            <br><?php echo e($data->appends(request()->query())->links()); ?>

        <?php endif; ?>
        <?php endif; ?>

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
        if (!confirm('<?php echo e(__("messages.Confirm action")); ?>: <?php echo e(__("messages.Returned")); ?>?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '<?php echo e(url("")); ?>/<?php echo e(LaravelLocalization::getCurrentLocale()); ?>/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '<?php echo e(csrf_token()); ?>', status: status },
            success: function (res) {
                if (!res.success) return;
                var row = btn.closest('tr');
                row.removeClass('urgent-row normal-row');
                row.find('.days-badge').css({ 'background': '#b2f2bb', 'color': '#2f9e44' })
                   .text('<?php echo e(__("messages.Returned")); ?>');
                row.find('.btn-quick-status').remove();
                row.css({ 'background': '#d4edda', 'border-right': '4px solid #28a745', 'transition': 'background 0.4s' });
                row.appendTo('#out-tbody');
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Error');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\game\resources\views/admin/orders/out_not_returned.blade.php ENDPATH**/ ?>