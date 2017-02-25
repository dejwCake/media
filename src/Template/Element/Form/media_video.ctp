<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        if($collection['type'] == 'video'):
            echo $this->element('DejwCake/Media.Form/block', ['collection' => $collection, 'object' => $object]);
        endif;
    endforeach;