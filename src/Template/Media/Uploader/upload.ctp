<section class="content-header">
    <h1>
        Category
        <small><?= __d('media', 'Add') ?></small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> ' . __d('media', 'Back'), ['action' => 'index'], ['escape' => false]) ?>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __d('media', 'Form') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($category, ['role' => 'form']) ?>
                <div class="box-body">
                    <?php
                        echo $this->Form->input('table_link');
                        echo $this->Form->input('parent_id', ['options' => $parentCategories, 'empty' => true]);
                        echo $this->Form->input('view');
                        echo $this->Form->input('enabled_in_locales', ['options' => $enabledInLocales, 'class' => 'select2', 'data-placeholder' => __d('media', "Select Locale"), 'multiple' => true, 'required' => false]);
                        echo $this->element('DejwCake/Media.file-standard-row', ['collection' => ['title' => 'Image', 'name' => 'image'], 'object' => $category]);
                    ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                        <?php $i = 0; ?>
                        <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                            <li <?php if ($selectedLanguage == $language): ?>class="active"<?php endif; ?>><a
                                        href="#tab_<?= $i ?>" data-toggle="tab"><?= $language ?></a></li>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                        </ul>
                        <div class="tab-content">
                        <?php $i = 0; ?>
                        <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                            <div class="tab-pane <?php if ($selectedLanguage == $language): ?>active<?php endif; ?>"
                                 id="tab_<?= $i ?>">
                                <?php
                                    if($languageSettings['locale'] == $defaultLocale){
                                        echo $this->Form->input('title');
                                    } else {
                                        echo $this->Form->input('_translations.' . $languageSettings['locale'] . '.title');
                                    }
                                ?>
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <?= $this->Form->button(__d('media', 'Save')) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>

<?php $this->append('css'); ?>
<?php echo $this->Html->css('DejwCake/AdminLTE./plugins/iCheck/all.css'); ?>
<?php $this->end(); ?>
<?php $this->append('cssFirst'); ?>
<?php echo $this->Html->css('DejwCake/AdminLTE./plugins/select2/select2.min.css'); ?>
<?php $this->end(); ?>
<?php $this->append('scriptBottom'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/iCheck/icheck.min.js'); ?>
<?php echo $this->Html->script('DejwCake/AdminLTE./plugins/select2/select2.full.min.js'); ?>
    <script type="text/javascript">
        $(".select2").select2();
        $(function () {
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });
        });
    </script>
<?php $this->end(); ?>
