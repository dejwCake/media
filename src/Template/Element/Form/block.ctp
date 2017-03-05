<div class="form-group">
    <label class="control-label" for="title"><?= $collection["title"] ?></label>
    <?php
        $media = null;
        if(!is_null($this->request->data('medium.'.$collection["name"]))) {
            foreach ($this->request->data['medium'][$collection["name"]] as $mediumItem) {
                $medium = new stdClass();
                $medium->id = $mediumItem["id"];
                $medium->file = $mediumItem["file"];
                $medium->name = $mediumItem["name"];
                $medium->thumb = $mediumItem["thumb"];
                $medium->deleted = $mediumItem["deleted"];
                $medium->title = $mediumItem["title"];
                $media[] = $medium;
            }
        } else if(!empty($mediumItems = $object->getMedia($collection['name']))) {
            $media = $mediumItems->map(function($mediumItem) use($supportedLanguages, $defaultLocale) {
                $medium = new stdClass();
                $medium->id = $mediumItem->id;
                $medium->file = '';
                $medium->name = $mediumItem->file_name;
                $medium->thumb = $mediumItem->getUrl();
                $medium->deleted = 0;
                foreach ($supportedLanguages as $language => $languageSettings):
                    if($languageSettings['locale'] == $defaultLocale) {
                        $medium->title[$languageSettings['locale']] = $mediumItem->title;
                    } else {
                        $medium->title[$languageSettings['locale']] = $mediumItem->translation($languageSettings['locale'])->title;
                    }
                endforeach;
                return $medium;
            });
            $media = $media->toArray();
        }
        $multiple = isset($collection['multiple']) ? $collection['multiple'] : false;
        if($collection['type'] == 'gallery') {
            $multiple = true;
        }
    ?>
    <div id="<?= $collection["name"] ?>"
         class="fileUpload"
         data-use-template="#<?= $collection["template"]; ?>"
         data-collection-name="<?= $collection["name"]; ?>"
         data-upload-url="<?= $this->Url->build(['controller' => 'Uploader', 'action' => 'upload', 'plugin' => 'DejwCake/Media', 'prefix' => 'media']) ?>"
         data-media="<?= htmlentities(json_encode($media), ENT_QUOTES, 'UTF-8');?>">
        <!-- The container for the uploaded files -->
        <div class="files" data-provides="files"></div>

        <span class="btn btn-primary fileinput-button" data-provides="fileinput">
            <i class="glyphicon glyphicon-plus"></i>
            <span data-provides="button-text" data-empty-text="<?= __d('media', 'Add files...') ?>" data-selected-text="<?= __d('media', 'Change') ?>">
                <?= __d('media', 'Add files...') ?>
            </span>
            <!-- The file input field used as target for the file upload widget -->
            <input type="file" name="fileinput[]" <?php if($multiple) { echo 'multiple'; } ?> />
            <span data-provides="hiddenInputs"></span>
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