<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Pending Delivery Orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.product-pill { display:inline-block; background:#e9ecef; border-radius:20px;
                padding:1px 8px; font-size:.78rem; margin:1px; }

/* ── Mobile card layout ── */
@media (max-width: 767px) {
    .responsive-table thead { display: none; }
    .responsive-table tbody tr {
        display: block;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.07);
    }
    .responsive-table tbody td {
        display: flex;
        align-items: flex-start;
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 7px 12px;
        gap: 8px;
    }
    .responsive-table tbody td:last-child { border-bottom: none; }
    .responsive-table tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        font-size: 0.78rem;
        color: #555;
        flex: 0 0 90px;
        padding-top: 2px;
    }
    /* preserve row colors */
    .responsive-table tbody tr[style*="background:#d4edda"] td { background: #d4edda; }
    .responsive-table tbody tr[style*="background:#f8d7da"] td { background: #f8d7da; }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h3 class="card-title mb-0">
            <i class="fas fa-clock text-warning mr-2"></i>
            <?php echo e(__('messages.Pending Delivery Orders')); ?>

            <span class="badge badge-warning ml-2"><?php echo e($data->total()); ?></span>
        </h3>
        <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-success">
            <i class="fas fa-plus mr-1"></i> <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.orders')); ?>

        </a>
    </div>

    <div class="card-body">

        <form method="GET" action="<?php echo e(route('orders.pending-delivery')); ?>" class="mb-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label><?php echo e(__('messages.date')); ?></label>
                    <input type="date" name="check_date" class="form-control" value="<?php echo e($checkDate); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
                    <a href="<?php echo e(route('orders.pending-delivery')); ?>" class="btn btn-secondary"><?php echo e(__('messages.reset')); ?></a>
                </div>
            </div>
        </form>

        <?php if($data->isEmpty() && $executedData->isEmpty() && $cancelledData->isEmpty()): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> <?php echo e(__('messages.No pending delivery orders')); ?>

            </div>
        <?php else: ?>
        <table class="table table-bordered table-hover responsive-table">
            <thead class="custom_thead">
                <tr>
                    <th>#</th>
                    <th><?php echo e(__('messages.date')); ?></th>
                    <th><?php echo e(__('messages.user')); ?></th>
                    <th><?php echo e(__('messages.Phone')); ?></th>
                    <th><?php echo e(__('messages.products')); ?></th>
                    <th><?php echo e(__('messages.delivery')); ?></th>
                    <th><?php echo e(__('messages.Note')); ?></th>
                    <th><?php echo e(__('messages.order_status')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </tr>
            </thead>
            <tbody id="pending-tbody">
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td data-label="#"><?php echo e($order->number); ?></td>
                        <td data-label="<?php echo e(__('messages.date')); ?>">
                            <strong><?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></strong><br>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('g:i')); ?>

                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                            </small>
                            <?php if($order->end_date): ?>
                                <br><small class="text-danger">← <?php echo e(\Carbon\Carbon::parse($order->end_date)->format('d/m/Y')); ?></small>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.user')); ?>"><?php echo e($order->user->name ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.Phone')); ?>"><?php echo e($order->user->phone ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.products')); ?>">
                            <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-pill"><?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.delivery')); ?>"><?php echo e($order->delivery->place ?? '-'); ?><br><small><?php echo e($order->address); ?></small></td>
                        <td data-label="<?php echo e(__('messages.Note')); ?>"><small><?php echo e($order->note ?? '-'); ?></small></td>
                        <td data-label="<?php echo e(__('messages.order_status')); ?>">
                            <?php if($order->order_status == 1): ?>
                                <span class="badge badge-warning"><?php echo e(__('messages.Pending')); ?></span>
                            <?php else: ?>
                                <span class="badge badge-info"><?php echo e(__('messages.Processing')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.Action')); ?>">
                            <button class="btn btn-xs btn-success mb-1 btn-quick-status"
                                data-id="<?php echo e($order->id); ?>" data-status="6">
                                <i class="fas fa-check mr-1"></i><?php echo e(__('messages.Executed')); ?>

                            </button>
                            <button class="btn btn-xs btn-danger mb-1 btn-quick-status"
                                data-id="<?php echo e($order->id); ?>" data-status="3">
                                <i class="fas fa-times mr-1"></i><?php echo e(__('messages.Cancel')); ?>

                            </button>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i><?php echo e(__('messages.Edit')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-xs btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i><?php echo e(__('messages.Show')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $executedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background:#d4edda; border-right:4px solid #28a745;">
                        <td data-label="#"><?php echo e($order->number); ?></td>
                        <td data-label="<?php echo e(__('messages.date')); ?>">
                            <strong><?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></strong><br>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('g:i')); ?>

                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                            </small>
                            <?php if($order->end_date): ?>
                                <br><small class="text-danger">← <?php echo e(\Carbon\Carbon::parse($order->end_date)->format('d/m/Y')); ?></small>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.user')); ?>"><?php echo e($order->user->name ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.Phone')); ?>"><?php echo e($order->user->phone ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.products')); ?>">
                            <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-pill"><?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.delivery')); ?>"><?php echo e($order->delivery->place ?? '-'); ?><br><small><?php echo e($order->address); ?></small></td>
                        <td data-label="<?php echo e(__('messages.Note')); ?>"><small><?php echo e($order->note ?? '-'); ?></small></td>
                        <td data-label="<?php echo e(__('messages.order_status')); ?>"><span class="badge badge-success"><?php echo e(__('messages.Executed')); ?></span></td>
                        <td data-label="<?php echo e(__('messages.Action')); ?>">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i><?php echo e(__('messages.Edit')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-xs btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i><?php echo e(__('messages.Show')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $cancelledData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="background:#f8d7da; border-right:4px solid #dc3545;">
                        <td data-label="#"><?php echo e($order->number); ?></td>
                        <td data-label="<?php echo e(__('messages.date')); ?>">
                            <strong><?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></strong><br>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('g:i')); ?>

                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('A') === 'AM' ? 'ص' : 'م'); ?>

                            </small>
                            <?php if($order->end_date): ?>
                                <br><small class="text-danger">← <?php echo e(\Carbon\Carbon::parse($order->end_date)->format('d/m/Y')); ?></small>
                            <?php endif; ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.user')); ?>"><?php echo e($order->user->name ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.Phone')); ?>"><?php echo e($order->user->phone ?? '-'); ?></td>
                        <td data-label="<?php echo e(__('messages.products')); ?>">
                            <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-pill"><?php echo e($item->product->name_ar); ?> (<?php echo e($item->quantity); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td data-label="<?php echo e(__('messages.delivery')); ?>"><?php echo e($order->delivery->place ?? '-'); ?><br><small><?php echo e($order->address); ?></small></td>
                        <td data-label="<?php echo e(__('messages.Note')); ?>"><small><?php echo e($order->note ?? '-'); ?></small></td>
                        <td data-label="<?php echo e(__('messages.order_status')); ?>"><span class="badge badge-danger"><?php echo e(__('messages.Cancelled')); ?></span></td>
                        <td data-label="<?php echo e(__('messages.Action')); ?>">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-edit')): ?>
                            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-xs btn-warning mb-1">
                                <i class="fas fa-edit mr-1"></i><?php echo e(__('messages.Edit')); ?>

                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-xs btn-secondary mb-1">
                                <i class="fas fa-eye mr-1"></i><?php echo e(__('messages.Show')); ?>

                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
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
    var labels = {
        6: '<?php echo e(__("messages.Executed")); ?>',
        3: '<?php echo e(__("messages.Cancelled")); ?>'
    };

    $(document).on('click', '.btn-quick-status', function () {
        var btn    = $(this);
        var id     = btn.data('id');
        var status = btn.data('status');
        if (!confirm('<?php echo e(__("messages.Confirm action")); ?>: ' + labels[status] + '?')) return;

        btn.prop('disabled', true);
        $.ajax({
            url: '<?php echo e(url("")); ?>/<?php echo e(LaravelLocalization::getCurrentLocale()); ?>/admin/orders/' + id + '/quick-status',
            method: 'PATCH',
            data: { _token: '<?php echo e(csrf_token()); ?>', status: status },
            success: function (res) {
                if (!res.success) return;
                if (status == 6) {
                    var row = btn.closest('tr');
                    row.find('[data-label="<?php echo e(__("messages.order_status")); ?>"]').html('<span class="badge badge-success"><?php echo e(__("messages.Executed")); ?></span>');
                    row.find('.btn-quick-status').remove();
                    row.css({ 'background': '#d4edda', 'border-right': '4px solid #28a745', 'transition': 'background 0.4s' });
                    row.appendTo('#pending-tbody');
                } else if (status == 3) {
                    var row = btn.closest('tr');
                    row.find('[data-label="<?php echo e(__("messages.order_status")); ?>"]').html('<span class="badge badge-danger"><?php echo e(__("messages.Cancelled")); ?></span>');
                    row.find('.btn-quick-status').remove();
                    row.css({ 'background': '#f8d7da', 'border-right': '4px solid #dc3545', 'transition': 'background 0.4s' });
                    row.appendTo('#pending-tbody');
                }
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\game\resources\views/admin/orders/pending_delivery.blade.php ENDPATH**/ ?>