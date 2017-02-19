<?php
    echo $this->element('DejwCake/Media.Template/file-template');
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'image'):
            echo $this->element('DejwCake/Media.file-standard-row', ['collection' => $collection, 'object' => $object]);
        endif;
    endforeach;