<?php
use infrajs\event\Event;
use akiyatkin\catalog\drive\Sheets;
use infrajs\excel\Xlsx;

Event::handler('Catalog.oninit', function(&$data){

	$data2 = Sheets::init('1Q34TcZvy-MexZPJS8gl3GYo8dK-6l5uU');
	$data = Xlsx::merge([$data,$data2]);
	
});