<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'gallery'):
            echo $this->element('DejwCake/Media.Type/multiple', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
        endif;
    endforeach;