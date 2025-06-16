<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.invoice')); ?> #<?php echo e($order->number); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        .d-print-none {
            display: none !important;
        }
        
        body {
            font-size: 11px !important;
            line-height: 1.3 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            max-width: none !important;
        }
        
        .invoice-container {
            margin: 0 !important;
            padding: 10mm !important;
            max-width: none !important;
            width: 100% !important;
            box-shadow: none !important;
        }
        
        .invoice-header {
            margin-bottom: 15px !important;
            padding-bottom: 15px !important;
        }
        
        .company-name {
            font-size: 22px !important;
            margin-bottom: 8px !important;
        }
        
        .invoice-title {
            font-size: 28px !important;
            margin-bottom: 8px !important;
        }
        
        .invoice-details {
            margin-bottom: 15px !important;
        }
        
        .bill-to h4, .delivery-info h4 {
            font-size: 14px !important;
            margin-bottom: 8px !important;
        }
        
        .items-table {
            margin-bottom: 15px !important;
            font-size: 10px !important;
        }
        
        .items-table th {
            padding: 8px 4px !important;
            font-size: 10px !important;
        }
        
        .items-table td {
            padding: 8px 4px !important;
            font-size: 10px !important;
        }
        
        .invoice-summary {
            margin-bottom: 15px !important;
        }
        
        .total-table {
            font-size: 11px !important;
        }
        
        .total-table td {
            padding: 6px 8px !important;
        }
        
        .invoice-footer {
            margin-top: 15px !important;
            padding-top: 10px !important;
        }
        
        .thank-you {
            font-size: 12px !important;
        }
        
        /* Prevent page breaks inside important elements */
        .invoice-header,
        .invoice-details,
        .items-table,
        .invoice-summary {
            page-break-inside: avoid;
        }
        
        /* Ensure table rows don't break */
        .items-table tr {
            page-break-inside: avoid;
        }
    }

    @page {
        size: A4;
        margin: 12mm 10mm;
    }

    .invoice-container {
        max-width: 190mm;
        margin: 0 auto;
        padding: 15px;
        background: white;
        font-family: 'Arial', sans-serif;
        color: #333;
        box-sizing: border-box;
    }

    .invoice-header {
        border-bottom: 2px solid #007bff;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .company-name {
        font-size: 28px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .company-address {
        color: #666;
        line-height: 1.6;
    }

    .invoice-title {
        font-size: 36px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .invoice-meta p {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-1 { background-color: #ffc107; color: #856404; }
    .status-2 { background-color: #17a2b8; color: white; }
    .status-3 { background-color: #dc3545; color: white; }
    .status-4 { background-color: #6c757d; color: white; }
    .status-5 { background-color: #fd7e14; color: white; }
    .status-6 { background-color: #28a745; color: white; }

    .invoice-details {
        margin-bottom: 30px;
    }

    .bill-to h4, .delivery-info h4 {
        color: #007bff;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .customer-info p, .delivery-info p {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .payment-status {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
    }

    .payment-1 { background-color: #28a745; color: white; }
    .payment-2 { background-color: #dc3545; color: white; }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
        overflow: hidden;
        border-radius: 6px;
    }

    .items-table th {
        background-color: #007bff;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: bold;
        border-right: 1px solid rgba(255,255,255,0.2);
        white-space: nowrap;
    }

    .items-table th:last-child {
        border-right: none;
    }

    .items-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        vertical-align: top;
        text-align: center;
    }

    .items-table td:last-child {
        border-right: none;
    }

    .items-table td:nth-child(2) {
        text-align: left;
    }

    .items-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .items-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-info strong {
        color: #333;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .invoice-summary {
        margin-bottom: 30px;
    }

    .payment-info h5 {
        color: #007bff;
        margin-bottom: 15px;
    }

    .total-table {
        width: 100%;
        margin-left: auto;
    }

    .total-table td {
        padding: 8px 12px;
        text-align: right;
        border-bottom: 1px solid #dee2e6;
    }

    .total-table td:first-child {
        text-align: left;
        width: 60%;
    }

    .discount {
        color: #28a745;
        font-weight: bold;
    }

    .total-row {
        border-top: 2px solid #007bff;
        font-size: 16px;
    }

    .total-row td {
        padding: 12px;
        background-color: #f8f9fa;
    }

    .invoice-footer {
        border-top: 1px solid #dee2e6;
        padding-top: 20px;
        margin-top: 30px;
    }

    .terms h5 {
        color: #007bff;
        margin-bottom: 10px;
    }

    .terms p {
        font-size: 12px;
        color: #666;
        line-height: 1.5;
    }

    .thank-you {
        text-align: center;
        margin-top: 20px;
        font-size: 16px;
        color: #007bff;
    }
</style>
   
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 d-print-none">
        <div class="col-12">
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> <?php echo e(__('messages.print')); ?>

            </button>
        </div>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <div class="company-info">
                        <h2 class="company-name"><?php echo e(config('app.name', 'Your Company')); ?></h2>
                        <p class="company-address">
                            Your Company Address<br>
                           00775504609<br>
                        </p>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <h1 class="invoice-title"><?php echo e(__('messages.invoice')); ?></h1>
                    <div class="invoice-meta">
                        <p><strong><?php echo e(__('messages.invoice_number')); ?>:</strong> #<?php echo e($order->number); ?></p>
                        <p><strong><?php echo e(__('messages.date')); ?>:</strong> <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?></p>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-details">
            <div class="row">
                <div class="col-6">
                    <div class="bill-to">
                        <h4><?php echo e(__('messages.bill_to')); ?>:</h4>
                        <div class="customer-info">
                            <p><strong><?php echo e($order->user->name); ?></strong></p>
                            <p><?php echo e($order->user->email ?? ''); ?></p>
                            <p><?php echo e($order->user->phone ?? ''); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="delivery-info">
                        <h4><?php echo e(__('messages.delivery_information')); ?>:</h4>
                        <p><strong><?php echo e(__('messages.delivery_place')); ?>:</strong> <?php echo e($order->delivery->place); ?></p>
                        <p><strong><?php echo e(__('messages.payment_type')); ?>:</strong> <?php echo e(ucfirst($order->payment_type)); ?></p>
                        <p><strong><?php echo e(__('messages.payment_status')); ?>:</strong> 
                            <span class="payment-status payment-<?php echo e($order->payment_status); ?>">
                                <?php echo e($order->payment_status == 1 ? __('messages.paid') : __('messages.unpaid')); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('messages.product')); ?></th>
                        <th><?php echo e(__('messages.quantity')); ?></th>
                        <th><?php echo e(__('messages.unit_price')); ?></th>
                        <th><?php echo e(__('messages.discount')); ?></th>
                        <th><?php echo e(__('messages.total')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->orderProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <div class="product-info">
                                <strong><?php echo e($item->product->name_ar); ?></strong>
                                <?php if($item->product->number): ?>
                                    <br><small class="text-muted"><?php echo e($item->product->number); ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td>JD<?php echo e(number_format($item->unit_price, 2)); ?></td>
                        <td>
                            <?php if($item->discount_percentage > 0): ?>
                                <?php echo e($item->discount_percentage); ?>%<br>
                                <small>(-JD<?php echo e(number_format($item->discount_value, 2)); ?>)</small>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><strong>JD<?php echo e(number_format($item->total_price, 2)); ?></strong></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="invoice-summary">
            <div class="row">
                <div class="col-6">
                    <div class="payment-info">
                        <h5><?php echo e(__('messages.payment_information')); ?></h5>
                        <p><?php echo e(__('messages.payment_method')); ?>: <?php echo e(ucfirst($order->payment_type)); ?></p>
                        <p><?php echo e(__('messages.order_date')); ?>: <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="summary-table">
                        <table class="total-table">
                            <tr>
                                <td><?php echo e(__('messages.subtotal')); ?>:</td>
                                <td>JD<?php echo e(number_format($order->total_prices + $order->total_discounts, 2)); ?></td>
                            </tr>
                            <?php if($order->total_discounts > 0): ?>
                            <tr>
                                <td><?php echo e(__('messages.discount')); ?>:</td>
                                <td class="discount">-JD<?php echo e(number_format($order->total_discounts, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td><?php echo e(__('messages.delivery_fee')); ?>:</td>
                                <td>JD<?php echo e(number_format($order->delivery_fee, 2)); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td><strong><?php echo e(__('messages.total_amount')); ?>:</strong></td>
                                <td><strong>JD<?php echo e(number_format($order->total_prices + $order->delivery_fee, 2)); ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="row">
                <div class="col-12">
                    
                    <div class="thank-you">
                        <p><em><?php echo e(__('messages.thank_you_for_business')); ?></em></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Auto-focus print dialog when page loads (optional)
    // window.onload = function() {
    //     window.print();
    // }
</script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u833050780/domains/ewformarketing.com/public_html/game/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>