<script id="fileTemplate" type="x-tmpl-mustache">
    <div data-provide="file-info" data-index="{{ index }}">
        <span data-provides="file-name">{{ filename }}</span>
        <div class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <button class="btn btn-danger" data-provides="remove-button"><?= __d('media', 'Remove');?></button>
    </div>
</script>