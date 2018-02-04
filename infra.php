<?php
use infrajs\event\Event;
use akiyatkin\catalog\drive\Sheets;
use infrajs\excel\Xlsx;
use infrajs\catalog\Catalog;

Event::handler('Catalog.oninit', function(&$data){
	$options = Catalog::getOptions();
	$data2 = Sheets::init('1Q34TcZvy-MexZPJS8gl3GYo8dK-6l5uU', $options);
	$data = Xlsx::merge([$data,$data2]);
	
});