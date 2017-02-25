<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        echo $this->element('DejwCake/Media.View/block', ['collection' => $collection, 'object' => $object]);
    endforeach;