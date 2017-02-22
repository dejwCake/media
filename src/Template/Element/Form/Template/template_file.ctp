<script id="fileTemplate" type="x-tmpl-mustache">
    <div class="row file-info image" data-provides="file-info" data-index="{{ index }}">
        <div class="col-sm-8 file-name">
            <span data-provides="file-name">{{ filename }}</span>
            <div class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
        </div>
        <div class="col-sm-4 remove-button text-right">
            <button class="btn btn-danger" data-provides="remove-button"><?= __d('media', 'Remove');?></button>
        </div>
    </div>
</script>