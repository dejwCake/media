<?php
foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        echo $this->element('DejwCake/Media.Form/block', ['collection' => $collection, 'object' => $object]);
    endforeach;