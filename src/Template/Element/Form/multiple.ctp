<?php //debug($object->getMedia($collection['name']));?>
    <div class="form-group">
        <label class="control-label" for="title"><?= $collection["title"] ?></label>
        <?php
        //TODO multiple
        $medium = null;
        if(!is_null($this->request->data('medium.'.$collection["name"]))) {
            $medium = new stdClass();
            $medium->file = $this->request->data('medium.'.$collection["name"].'.file');
            $medium->name = $this->request->data('medium.'.$collection["name"].'.name');
            $medium->thumb = $this->request->data('medium.'.$collection["name"].'.thumb');
            $medium->deleted = $this->request->data('medium.'.$collection["name"].'.deleted');
        } else if(!empty($mediumItem = $object->getMedia($collection['name'])->first())) {
            $medium = new stdClass();
            $medium->file = '';
            $medium->name = $mediumItem->file_name;
            $medium->thumb = $mediumItem->getUrl();
            $medium->deleted = 0;
        }
        ?>
        <div id="<?= $collection["name"] ?>"
             class="fileUpload"
             data-use-template="#<?= $template; ?>"
             data-upload-url="<?= $this->Url->build(['controller' => 'Uploader', 'action' => 'upload', 'plugin' => 'DejwCake/Media', 'prefix' => 'media']) ?>"
             data-medium="<?= htmlentities(json_encode($medium), ENT_QUOTES, 'UTF-8');?>">
            <!-- The container for the uploaded files -->
            <div class="files"></div>

            <span class="btn btn-success fileinput-button" data-provides="fileinput">
            <i class="glyphicon glyphicon-plus"></i>
            <span data-provides="button-text" data-empty-text="<?= __d('media', 'Add files...') ?>" data-selected-text="<?= __d('media', 'Change') ?>">
                <?= __d('media', 'Add files...') ?>
            </span>
                <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="fileinput[]" multiple/>
            <input type="hidden" name="medium[<?= $collection["name"] ?>][file]" data-provides="fileInput" value="">
            <input type="hidden" name="medium[<?= $collection["name"] ?>][name]" data-provides="nameInput" value="">
            <input type="hidden" name="medium[<?= $collection["name"] ?>][thumb]" data-provides="thumbInput" value="">
            <input type="hidden" name="medium[<?= $collection["name"] ?>][deleted]" data-provides="deletedInput" value="0" />
        </span>
        </div>
    </div>

<?php $this->append('css'); ?>
<?php echo $this->Html->css('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload.css'); ?>
<?php echo $this->Html->css('DejwCake/Media./css/fileUpload.css'); ?>
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