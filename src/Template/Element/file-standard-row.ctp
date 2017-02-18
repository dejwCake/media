<div class="form-group">
    <label class="control-label" for="title"><?= $collection["title"] ?></label>
    <?php
        $medium = null;
        if(!is_null($this->request->data('medium.'.$collection["name"]))) {
            $medium = $this->request->data('medium.'.$collection["name"]);
        }
    ?>
    <div id="<?= $collection["name"] ?>" data-use-template="#fileTemplate" data-upload-url="<?= $this->Url->build(['controller' => 'Uploader', 'action' => 'upload', 'plugin' => 'DejwCake/Media', 'prefix' => 'media']) ?>">
        <!-- The container for the uploaded files -->
        <div class="files" data-default-medium="<?= json_encode($medium)?>"></div>

        <span class="btn btn-success fileinput-button" data-provides="fileinput">
            <i class="glyphicon glyphicon-plus"></i>
            <span data-provides="button-text" data-empty-text="<?= __d('media', 'Add files...') ?>" data-selected-text="<?= __d('media', 'Change') ?>">
                <?= __d('media', 'Add files...') ?>
            </span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="fileinput[]" multiple/>
            <input type="hidden" name="medium[<?= $collection["name"] ?>]" data-provides="mediumInput" <?php if(!is_null($medium)) { echo "value=".$medium.""; } ?>>
            <input type="hidden" name="hasDeleted[<?= $collection["name"] ?>]" data-provides="hasDeletedInput" value="0" />
        </span>
    </div>
</div>

<?php echo $this->element('DejwCake/Media.Template/file-template')?>

<?php $this->append('css'); ?>
    <?php echo $this->Html->css('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload.css'); ?>
<?php $this->end(); ?>
<?php $this->append('scriptBottom'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryUI/jquery-ui.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.iframe-transport.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-process.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-image.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-validate.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/mustache/mustache.min.js'); ?>
    <?php echo $this->Html->script('DejwCake/Media./js/fileUpload.js'); ?>
    <script type="text/javascript">
        $(function() {
            FileUpload.init($("#<?= $collection["name"] ?>"));
        });
    </script>
<?php $this->end(); ?>