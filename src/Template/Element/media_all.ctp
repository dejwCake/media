<?php
    foreach ($collections as $collectionName => $collection):
        $collection = $collection + ['name' => $collectionName,];
        switch ($collection['type']) {
            case 'image':
            case 'file':
            case 'video':
                if(!empty($collection['multiple']) && $collection['multiple']) {
                    echo $this->element('DejwCake/Media.Type/multiple', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
                } else {
                    echo $this->element('DejwCake/Media.Type/single', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
                }
                break;
            case 'gallery':
                echo $this->element('DejwCake/Media.Type/multiple', ['collection' => $collection, 'object' => $object, 'template' => $collection['type'].'Template']);
                break;
        }
    endforeach;