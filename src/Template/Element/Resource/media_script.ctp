<?php 
    $this->start('mediaScript');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryUI/jquery-ui.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.iframe-transport.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-process.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-image.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/jQueryFileUpload/jquery.fileupload-validate.js');
        echo $this->Html->script('DejwCake/AdminLTE./plugins/mustache/mustache.min.js');
        echo $this->Html->script('DejwCake/Media./js/fileUpload.js');
    $this->end();