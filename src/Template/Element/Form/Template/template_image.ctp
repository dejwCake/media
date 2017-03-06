<script id="imageTemplate" type="x-tmpl-mustache">
    <div class="row file-info image" data-provides="file-info" data-index="{{ index }}">
        <div class="col-sm-2">
            <img src="" alt="thumb image" data-provides="thumb-image" class="hidden img-thumbnail" />
        </div>
        <div class="col-sm-6 file-name">
            <div class="form-group row">
                <label class="col-xs-12 col-sm-4" for="title"><?= __d('dejw_cake_media', 'File name') ?></label>
                <div class="col-xs-12 col-sm-8" data-provides="file-name">{{ filename }}</div>
            </div>
            <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                <div class="form-group row">
                    <label class="col-xs-12 col-sm-4" for="title"><?= __d('dejw_cake_media', 'Title ({0})', $languageSettings['title']) ?></label>
                    <div class="col-xs-12 col-sm-8">
                        <input class="form-control" type="text" name="title[<?= $languageSettings['locale'] ?>]" data-locale="<?= $languageSettings['locale'] ?>" maxlength="255" data-provides="title" />
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
        </div>
        <div class="col-sm-4 remove-button text-right">
            <button class="btn btn-danger" data-provides="remove-button"><?= __d('dejw_cake_media', 'Remove');?></button>
        </div>
    </div>
</script>