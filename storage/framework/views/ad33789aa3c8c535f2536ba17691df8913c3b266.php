<?php $__env->startSection('title'); ?>
 <?php echo e(__('messages.branches')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('contentheaderactive'); ?>
 <?php echo e(__('messages.Show')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center">  <?php echo e(__('messages.branches')); ?> </h3>
          <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">

          <a href="<?php echo e(route('branches.create')); ?>" class="btn btn-sm btn-success" >   <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.branches')); ?></a>
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
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-table')): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th><?php echo e(__('messages.Name')); ?></th>
                    <th><?php echo e(__('messages.photo')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>


                        <td><?php echo e($info->name); ?></td>

                        <td>
                            <?php if($info->photo): ?>

                                <div class="image">
                                   <img class="custom_img" src="<?php echo e(asset('assets/admin/uploads').'/'.$info->photo); ?>"  >

                                         </div>

                              <?php else: ?>
                                No Photo
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-edit')): ?>
                            <a href="<?php echo e(route('branches.edit', $info->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                           <?php endif; ?>
                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-delete')): ?>
                            <form action="<?php echo e(route('branches.destroy', $info->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('messages.Delete')); ?></button>
                            </form>
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
<script src="<?php echo e(asset('assets/admin/js/branchess.js')); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/admin/branches/index.blade.php ENDPATH**/ ?>