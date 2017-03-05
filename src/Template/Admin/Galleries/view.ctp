<section class="content-header">
    <h1>
        <?php echo __d('media', 'Gallery'); ?>
    </h1>
    <ol class="breadcrumb">
        <li>
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> ' . __d('media', 'Back'), ['action' => 'index'], ['escape' => false])?>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-info"></i>
                    <h3 class="box-title"><?php echo __d('media', 'Information'); ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <dt><?= __d('media', 'Title ({0})', $supportedLanguages[$defaultLanguage]['title']) ?></dt>
                        <dd>
                            <?= h($gallery->title) ?>
                        </dd>
                        <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                            <?php if($languageSettings['locale'] == $defaultLocale) { continue; } ?>
                        <dt><?= __d('media', 'Title ({0})', $languageSettings['title']) ?></dt>
                        <dd>
                            <?= h($gallery->translation($languageSettings['locale'])->title) ?>
                        </dd>
                        <?php endforeach; ?>
                        <dt><?= __d('media', 'Slug ({0})', $supportedLanguages[$defaultLanguage]['title']) ?></dt>
                        <dd>
                            <?= h($gallery->slug) ?>
                        </dd>
                        <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                            <?php if($languageSettings['locale'] == $defaultLocale) { continue; } ?>
                        <dt><?= __d('media', 'Slug ({0})', $languageSettings['title']) ?></dt>
                        <dd>
                            <?= h($gallery->translation($languageSettings['locale'])->slug) ?>
                        </dd>
                        <?php endforeach; ?>
                        <?php //Media part ?>
                        <?= $this->element('DejwCake/Media.View/media_all', ['collections' => $collections, 'object' => $gallery]);?>
                        <dt><?= __d('media', 'Enabled In Locales') ?></dt>
                        <dd>
                            <?= $gallery->enabled_in_locales_text ?>
                        </dd>
                        <dt><?= __d('media', 'Sort') ?></dt>
                        <dd>
                            <?= $this->Number->format($gallery->sort) ?>
                        </dd>
                        <dt><?= __d('media', 'Text ({0})', $supportedLanguages[$defaultLanguage]['title']) ?></dt>
                        <dd>
                            <?= $this->Text->autoParagraph($gallery->text) ?>
                        </dd>
                        <?php foreach ($supportedLanguages as $language => $languageSettings): ?>
                            <?php if($languageSettings['locale'] == $defaultLocale) { continue; } ?>
                        <dt><?= __d('media', 'Text ({0})', $languageSettings['title']) ?></dt>
                        <dd>
                            <?= $this->Text->autoParagraph($gallery->translation($languageSettings['locale'])->text) ?>
                        </dd>
                        <?php endforeach; ?>
                        <dt><?= __d('media', 'Created') ?></dt>
                        <dd>
                            <?= h($gallery->created) ?>
                        </dd>
                        <dt><?= __d('media', 'Modified') ?></dt>
                        <dd>
                            <?= h($gallery->modified) ?>
                        </dd>
                    </dl>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- ./col -->
    </div>
    <!-- div -->
</section>