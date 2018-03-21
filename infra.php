<?php
use infrajs\event\Event;
use akiyatkin\catalog\drive\Sheets;
use infrajs\excel\Xlsx;
use infrajs\catalog\Catalog;

Event::handler('Catalog.oninit', function(&$data){
	if (!Sheets::$conf['folder']) return;
	$options = Catalog::getOptions();
	$data2 = Sheets::init(Sheets::$conf['folder'], $options);
	$data = Xlsx::merge([$data,$data2]);
	
	Xlsx::runGroups($data, function &(&$group) {
		$r = null;
		unset($group['parent']);
		return $r;
	});
	Xlsx::runPoss($data, function &(&$pos) {
		$r = null;
		unset($pos['parent']);
		return $r;
	});
});