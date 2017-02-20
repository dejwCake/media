<?php
    echo $this->element('DejwCake/Media.Resource/media_css');
    echo $this->element('DejwCake/Media.Resource/media_script');
    $this->append('css');
        echo $this->fetch('mediaCss');
    $this->end();
    $this->append('scriptBottom');
        echo $this->fetch('mediaScript');
    $this->end();