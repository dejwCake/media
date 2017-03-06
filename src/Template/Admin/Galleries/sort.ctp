<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= __d('dejw_cake_media', 'Galleries'); ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> ' . __d('dejw_cake_media', 'Back'), ['action' => 'index'], ['escape' => false]) ?>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __d('dejw_cake_media', 'Sort Galleries') ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if($galleries->count()): ?>
                        <ol class="sortable ids ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="sortable">
                            <?php foreach ($galleries as $gallery): ?>
                                <li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" data-row-id="<?= $gallery->id ?>" id="menuItem_<?= $gallery->id ?>">
                                    <div class="menuDiv">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <?= $gallery->title ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <?= __d('dejw_cake_media', 'No Galleries to sort.') ?>
                    <?php endif; ?>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <?php if($galleries->count() && !empty($gallery)): ?>
                        <?= $this->Form->create($gallery, ['role' => 'form', 'id' => 'sortForm']) ?>
                            <input type="hidden" name="ids" value="[]" />
                            <?= $this->Form->button(__d('dejw_cake_media', 'Save')) ?>
                        <?= $this->Form->end() ?>
                    <?php endif; ?>
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<!-- /.content -->

<?php $this->append('css'); ?>
<?php echo $this->Html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css'); ?>
<?php echo $this->Html->css('DejwCake/AdminLTE./plugins/nestedSortable/jquery.mjs.nestedSortable'); ?>
<?php $this->end(); ?>
<?php $this->append('scriptBottom'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryUI/jquery-ui'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/nestedSortable/jquery.mjs.nestedSortable'); ?>
<script>
    $(function () {
        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            isTree: false,

            forcePlaceholderSize: true,
            helper:	'clone',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            maxLevels: 1,
            expandOnHover: 700,
            startCollapsed: false,
            excludeRoot: true,
            change: function(){
            }
        });

        $('#sortForm').submit(function(event) {
            var arrayed = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
            $(this).find('[name="ids"]').val(JSON.stringify(arrayed));
            return true;
        });
    });
</script>
<?php $this->end(); ?>