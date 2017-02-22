<dt><?= $collection['title'] ?></dt>
<dd>
<?php
    $conversion = (!empty($collection['conversions']) && array_key_exists('thumb', $collection['conversions'])) ? 'thumb' : '';
    if(!empty($media = $object->getMedia($collection['name']))):
        foreach ($media as $mediaItem):
            echo $this->element('DejwCake/Media.View/Template/template_'.$collection['type'], ['url' => $mediaItem->getUrl($conversion), 'title' => $collection['title']]);
        endforeach;
    endif;
?>
</dd>