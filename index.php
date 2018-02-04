<?php

use infrajs\rest\Rest;
use infrajs\ans\Ans;
use akiyatkin\catalog\drive\Sheets;

require_once('index/catalog-drive/Sheets.php');
$res = Rest::get(function($id){
	return Sheets::init($id);
});


return Ans::ans($res);
