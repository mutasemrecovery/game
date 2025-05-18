<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?php echo e(__('messages.Order Success')); ?></title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/plugins/fontawesome-free/css/all.min.css')); ?>">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/fonts/SansPro/SansPro.min.css')); ?>">
    <?php if(App::getLocale() == 'ar'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')); ?>">
    <?php endif; ?>
    
<style>
    body {
        background: #f8fafc;
    }
    
    .success-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 3rem 0;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }
    
    .success-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .order-details {
        border-top: 1px solid #e5e7eb;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
    }
    
    .orders-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background: #f9fafb;
        border-top: none;
        font-weight: 600;
        color: #374151;
        padding: 1rem;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-confirmed {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .action-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .btn-view {
        background: #667eea;
        color: white;
        border: none;
    }
    
    .btn-view:hover {
        background: #5a67d8;
        color: white;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }
    
    @media (max-width: 768px) {
        .success-header {
            padding: 2rem 0;
        }
        
        .success-icon {
            width: 60px;
            height: 60px;
            font-size: 2rem;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
    }
</style>
</head>
<body>

<!-- Success Header -->
<div class="success-header">
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="mb-2"><?php echo e(__('messages.Order Placed Successfully!')); ?></h1>
        <p class="lead"><?php echo e(__('messages.Thank you for your order. We will contact you soon.')); ?></p>
    </div>
</div>

<div class="container">
    <!-- Order Success Card -->
    <div class="success-card">
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3"><?php echo e(__('messages.Order Information')); ?></h4>
                <ul class="list-unstyled">
                    <li><strong><?php echo e(__('messages.Order Number')); ?>:</strong> #<?php echo e($order->number); ?></li>
                    <li><strong><?php echo e(__('messages.Customer Name')); ?>:</strong> <?php echo e($order->user->name); ?></li>
                    <li><strong><?php echo e(__('messages.Phone')); ?>:</strong> <?php echo e($order->user->phone); ?></li>
                    <li><strong><?php echo e(__('messages.Order Date')); ?>:</strong> <?php echo e(\Carbon\Carbon::parse($order->date)->format('M d, Y - h:i A')); ?></li>
                    <li><strong><?php echo e(__('messages.Delivery')); ?>:</strong> <?php echo e($order->delivery->place ?? 'N/A'); ?></li>
                    <li><strong><?php echo e(__('messages.Payment Type')); ?>:</strong> <?php echo e(ucfirst($order->payment_type)); ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4 class="mb-3"><?php echo e(__('messages.Order Summary')); ?></h4>
                <div class="order-details">
                    <div class="order-item">
                        <span><?php echo e(__('messages.Subtotal')); ?>:</span>
                        <span>JD <?php echo e(number_format($order->total_prices, 2)); ?></span>
                    </div>
                    <div class="order-item text-success">
                        <span><?php echo e(__('messages.Discount')); ?>:</span>
                        <span>-JD <?php echo e(number_format($order->total_discounts, 2)); ?></span>
                    </div>
                    <div class="order-item">
                        <span><?php echo e(__('messages.Delivery Fee')); ?>:</span>
                        <span>JD <?php echo e(number_format($order->delivery_fee, 2)); ?></span>
                    </div>
                    <div class="order-item border-top pt-3" style="font-weight: bold; font-size: 1.125rem;">
                        <span><?php echo e(__('messages.Total')); ?>:</span>
                        <span>JD <?php echo e(number_format($order->total_prices + $order->delivery_fee, 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="mt-4">
            <h4 class="mb-3"><?php echo e(__('messages.Order Items')); ?></h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.Product')); ?></th>
                            <th><?php echo e(__('messages.Quantity')); ?></th>
                            <th><?php echo e(__('messages.Unit Price')); ?></th>
                            <th><?php echo e(__('messages.Total')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $item->product->productImages->first()->photo)); ?>" 
                                         alt="<?php echo e($item->product->name_en ?? $item->product->name_ar); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 1rem;">
                                    <span><?php echo e($item->product->name_en ?? $item->product->name_ar); ?></span>
                                </div>
                            </td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>
                                <?php if($item->discount_value > 0): ?>
                                    <span class="text-decoration-line-through text-muted me-2">JD <?php echo e(number_format($item->unit_price, 2)); ?></span>
                                    <span class="text-danger">JD <?php echo e(number_format($item->unit_price - ($item->discount_value / $item->quantity), 2)); ?></span>
                                <?php else: ?>
                                    JD <?php echo e(number_format($item->unit_price, 2)); ?>

                                <?php endif; ?>
                            </td>
                            <td>JD <?php echo e(number_format($item->total_price, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Create Another Order')); ?>

            </a>
        </div>
    </div>
    
    <!-- Order History Section -->
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-history me-2 text-primary"></i><?php echo e(__('messages.Your Order History')); ?>

        </div>
        <div class="customer-info">
            <i class="fas fa-user"></i> <?php echo e($order->user->name); ?>

            <i class="fas fa-phone ms-3"></i> <?php echo e($order->user->phone); ?>

        </div>
    </div>
    
    <?php if($userOrders->count() > 0): ?>
    <div class="orders-table">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.Order')); ?> #</th>
                    <th><?php echo e(__('messages.Date')); ?></th>
                    <th><?php echo e(__('messages.Items')); ?></th>
                    <th><?php echo e(__('messages.Total')); ?></th>
                    <th><?php echo e(__('messages.Status')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $userOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userOrder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <span class="fw-semibold">#<?php echo e($userOrder->number); ?></span>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($userOrder->date)->format('M d, Y')); ?></td>
                    <td><?php echo e($userOrder->orderProducts->count()); ?> <?php echo e(Str::plural('item', $userOrder->orderProducts->count())); ?></td>
                    <td>JD <?php echo e(number_format($userOrder->total_prices + $userOrder->delivery_fee, 2)); ?></td>
                    <td>
                        <?php switch($userOrder->order_status):
                            case (1): ?>
                                <span class="status-badge status-pending"><?php echo e(__('messages.Pending')); ?></span>
                                <?php break; ?>
                            <?php case (2): ?>
                                <span class="status-badge status-confirmed"><?php echo e(__('messages.Confirmed')); ?></span>
                                <?php break; ?>
                            <?php case (3): ?>
                                <span class="status-badge status-delivered"><?php echo e(__('messages.Delivered')); ?></span>
                                <?php break; ?>
                            <?php case (0): ?>
                                <span class="status-badge status-cancelled"><?php echo e(__('messages.Cancelled')); ?></span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="status-badge status-pending"><?php echo e(__('messages.Pending')); ?></span>
                        <?php endswitch; ?>
                    </td>
                   
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <?php if($userOrders->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($userOrders->links()); ?>

    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <p><?php echo e(__('messages.You have no previous orders')); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="text-center my-5">
        <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-home me-2"></i><?php echo e(__('messages.Back to Homepage')); ?>

        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html><?php /**PATH C:\laragon\www\game\resources\views/layouts/order-success.blade.php ENDPATH**/ ?>