<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'file'):
            echo $this->element('DejwCake/Media.View/single', ['collection' => $collection, 'object' => $object]);
        endif;
    endforeach;