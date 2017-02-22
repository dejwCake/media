<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'file'):
            echo $this->element('DejwCake/Media.Form/single', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
        endif;
    endforeach;