<div id="paginator">
<?php
$paging = $paginator->params();
$modulus = 4;
$half = intval($modulus / 2);
$end = $paging['page'] + $half;
if ($end > $paging['pageCount'])
	$end = $paging['pageCount'];
$start = $paging['page'] - ($modulus - ($end - $paging['page']));
if ($start <= 1) {
	$start = 1;
	$end = $paging['page'] + ($modulus  - $paging['page']) + 1;
}
$url_options = isset($url_options) ? $url_options : array();
?>
<?php echo $paginator->prev('« ' . ucfirst(__('previous', true)), array('url' => $url_options)) ?>
<?php if($start > 1): ?>
<?php echo $paginator->link(1, am($url_options, array('page' => 1))) ?>
<?php if($start > 2): ?>
<?php echo $paginator->link(2, am($url_options, array('page' => 2))) ?>
<?php if($start > 3): ?>...<?php endif ?>
<?php endif ?>
<?php endif ?>
<?php echo $paginator->numbers(array('separator' => '', 'modulus' => $modulus, 'url' => $url_options)) ?>
<?php if($end < $paging['pageCount']): ?>
<?php if($end < $paging['pageCount'] - 1): ?>
<?php if($end < $paging['pageCount'] - 2): ?>...<?php endif ?>
<?php echo $paginator->link($paging['pageCount'] - 1, am($url_options, array('page' => $paging['pageCount'] - 1))) ?>
<?php endif ?>
<?php echo $paginator->link($paging['pageCount'], am($url_options, array('page' => $paging['pageCount']))) ?>
<?php endif ?>
<?php echo $paginator->next(ucfirst(__('next', true)) . ' »', array('url' => $url_options)) ?>
</div>