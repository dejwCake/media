<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'image'):
            echo $this->element('DejwCake/Media.Type/single', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
        endif;
    endforeach;