<?php $__env->startSection('title'); ?>
 <?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Show')); ?> <?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">  <?php echo e(__('messages.products')); ?> </h3>
          <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">

          <a href="<?php echo e(route('products.create')); ?>" class="btn btn-sm btn-success" >  <?php echo e(__('messages.New')); ?>  <?php echo e(__('messages.products')); ?></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

            

            

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">

            <?php if(isset($data) && !$data->isEmpty()): ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-table')): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th> <?php echo e(__('messages.Name_en')); ?></th>
                    <th> <?php echo e(__('messages.Name_ar')); ?></th>
                    <th> <?php echo e(__('messages.description_en')); ?></th>
                    <th> <?php echo e(__('messages.description_ar')); ?></th>
                    <th><?php echo e(__('messages.Selling_price')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>


                        <td><?php echo e($info->name_en); ?></td>
                        <td><?php echo e($info->name_ar); ?></td>

                        <td><?php echo e($info->description_en); ?></td>
                        <td><?php echo e($info->description_ar); ?></td>

                        <td><?php echo e($info->selling_price); ?></td>


                        <td>
                           
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                            <a href="<?php echo e(route('products.edit', $info->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                            <?php endif; ?> 
                           
                      
                          
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php endif; ?>
            <br>
            <?php echo e($data->links()); ?>


            <?php else: ?>
            <div class="alert alert-danger">
                <?php echo e(__('messages.No_data')); ?>

            </div>
            <?php endif; ?>

        </div>



      </div>

        </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/admin/js/productss.js')); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\game\resources\views/admin/products/index.blade.php ENDPATH**/ ?>