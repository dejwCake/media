<dt><?= $collection['title'] ?></dt>
<dd>
<?php
    $conversion = (!empty($collection['conversions']) && array_key_exists('thumb', $collection['conversions'])) ? 'thumb' : '';
    if(!empty($mediaUrl = $object->getFirstMediaUrl($collection['name'], $conversion))):
        echo $this->element('DejwCake/Media.View/Template/template_'.$collection['type'], ['url' => $mediaUrl, 'title' => $collection['title']]);
    endif;
?>
</dd>