<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= __d('dejw_cake_media', 'Galleries'); ?>
        <div class="pull-right">
            <?= $this->Html->link(__d('dejw_cake_media', 'Sort'), ['action' => 'sort'], ['class' => 'btn btn-info btn-xs']) ?>
            <?= $this->Html->link(__d('dejw_cake_media', 'New'), ['action' => 'add'], ['class' => 'btn btn-success btn-xs']) ?>
        </div>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __d('dejw_cake_media', 'List of Galleries') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="galleriesTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <!--<th scope="col"><?= $this->Paginator->sort('id') ?></th>-->
                                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('enabled_in_locales') ?></th>
                                <!--<th scope="col"><?= $this->Paginator->sort('created') ?></th>-->
                                <!--<th scope="col"><?= $this->Paginator->sort('modified') ?></th>-->
                                <th scope="col" class="actions"><?= __d('dejw_cake_media', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($galleries as $gallery): ?>
                            <tr>
                                <!--<td><?= $this->Number->format($gallery->id) ?></td>-->
                                <td><?= h($gallery->title) ?></td>
                                <td><?= h($gallery->slug) ?></td>
                                <td><?= h($gallery->enabled_in_locales_text) ?></td>
                                <!--<td><?= h($gallery->created) ?></td>-->
                                <!--<td><?= h($gallery->modified) ?></td>-->
                                <td class="actions" style="white-space:nowrap">
                                    <?= $this->Html->link(__d('dejw_cake_media', 'View'), ['action' => 'view', $gallery->id], ['escape' => false, 'class' => 'btn btn-info btn-xs']) ?>
                                    <?= $this->Html->link(__d('dejw_cake_media', 'Edit'), ['action' => 'edit', $gallery->id], ['escape' => false, 'class' => 'btn btn-warning btn-xs']) ?>
                                    <?= $this->Form->postLink(__d('dejw_cake_media', 'Delete'), ['action' => 'delete', $gallery->id], ['escape' => false, 'confirm' => __d('dejw_cake_media', 'Are you sure you want to delete this entry?'), 'class' => 'btn btn-danger btn-xs']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <!--<th scope="col"><?= $this->Paginator->sort('id') ?></th>-->
                            <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('enabled_in_locales') ?></th>
                            <!--<th scope="col"><?= $this->Paginator->sort('created') ?></th>-->
                            <!--<th scope="col"><?= $this->Paginator->sort('modified') ?></th>-->
                            <th scope="col" class="actions"><?= __d('dejw_cake_media', 'Actions') ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<!-- /.content -->

<?php $this->append('css'); ?>
<?php echo $this->Html->css('DejwCake/AdminLTE./plugins/datatables/dataTables.bootstrap.css'); ?>
<?php $this->end(); ?>
<?php $this->append('scriptBottom'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/datatables/dataTables.bootstrap.min.js'); ?>
    <script>
        $(function () {
            $('#galleriesTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });
        });
    </script>
<?php $this->end(); ?>
