<div class="form-group">
    <label class="col-sm-2 control-label" for="title"><?= $collection["title"] ?></label>
    <div class="col-sm-10" id="<?= $collection["name"] ?>" data-upload-url="<?= $this->Url->build(['controller' => 'Uploader', 'action' => 'upload', 'plugin' => 'DejwCake/Media', 'prefix' => 'media']) ?>">
        <?php //$collectionUrl = $object->getFirstMediaUrl($collection["name"]) ?>
        <?php if((empty($collectionUrl)) && (is_null($this->request->data('medium.'.$collection["name"])))): ?>
            <div class="fileinput fileinput-new" data-provides="fileinput">
        <?php else: ?>
            <div class="fileinput fileinput-exists" data-provides="fileinput">
        <?php endif; ?>
                <div class="input-group">
                    <div class="form-control uneditable-input span3" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename">
                            <?php
                                if(!is_null($this->request->data('medium.'.$collection["name"]))) {
                                    echo $this->request->data('medium.'.$collection["name"]);
                                } elseif(!empty($collectionUrl)) {
                                    echo $collectionUrl;
                                }
                            ?>
                        </span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new"><?= __d('media', 'Choose file') ?></span>
                        <span class="fileinput-exists"><?= __d('media', 'Change') ?></span>
                        <input type="file" name="fileinput">
                        <input type="hidden" name="medium[<?= $collection["name"] ?>]" <?php if(!is_null($this->request->data('medium.'.$collection["name"]))) { echo "value=".$this->request->data('medium.'.$collection["name"]).""; } ?> class="<?= $collection["name"] ?>Input">
                        <input type="hidden" name="hasDeleted[<?= $collection["name"] ?>]" id="hasDeletedInput<?= $collection["name"] ?>" value="0" />
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><?= __d('media', 'Remove') ?></a>
                </div>
                <div class="loadingDiv">
                    <div class="spinner spinner-rectangle-bounce">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
            </div><!-- /fileinput -->
    </div>
</div>

<?php $this->append('css'); ?>
    <?php echo $this->Html->css('DejwCake/AdminLTE./plugins/jqueryFileupload/jquery.fileupload.css'); ?>
<?php $this->end(); ?>
<?php $this->append('scriptBottom'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryUI/jquery-ui.js'); ?>
    <?php echo $this->Html->script('DejwCake/AdminLTE./plugins/jqueryFileupload/jquery.fileupload.js'); ?>
    <script type="text/javascript">
        $(function() {
            FileUpload_<?= md5($collection["name"]) ?>.init($("#<?= $collection["name"] ?>"));
            $('[data-provides="fileinput"]').on('clear.bs.fileinput', function(){
                $(this).find('#hasDeletedInput_<?= $collection["name"] ?>').val(1);
            })
        });

        var FileUpload_<?= md5($collection["name"]) ?> = {
            init: function(context) {
                console.log(context.attr('data-upload-url'));
                context.fileupload({
                    dropZone: $('.<?= $collection["name"] ?>-fileinput-preview'),
                    url : context.attr('data-upload-url'),

                    // This function is called when a file is added to the queue;
                    // either via the browse button, or via drag/drop:
                    add: function (e, data) {
                        console.log(context.attr('data-upload-url'));
                        context.children(".fileinput").addClass("loading");

                        // Automatically upload the file once it is added to the queue
                        var jqXHR = data.submit();
                    },

                    done: function(e, data){
                        dataResponse = data;
                        setTimeout(function(){
                            originalUrl = dataResponse.result.original_filepath;
                            context.children(".fileinput").removeClass("loading");
                            $(".<?= $collection["name"] ?>Input").val(originalUrl);
                            context.find('#hasDeletedInput_<?= $collection["name"] ?>').val(0);
                        }, 1300);
                    },

                    fail:function(e, data){
                        // TODO doriesit chybu pri uploade
                    }
                });
            }
        };
    </script>
<?php $this->end(); ?>