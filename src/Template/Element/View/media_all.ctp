<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        switch ($collection['type']) {
            case 'image':
            case 'file':
            case 'video':
                if(!empty($collection['multiple']) && $collection['multiple']) {
                    echo $this->element('DejwCake/Media.View/multiple', ['collection' => $collection, 'object' => $object]);
                } else {
                    echo $this->element('DejwCake/Media.View/single', ['collection' => $collection, 'object' => $object]);
                }
                break;
            case 'gallery':
                echo $this->element('DejwCake/Media.View/multiple', ['collection' => $collection, 'object' => $object]);
                break;
        }
    endforeach;