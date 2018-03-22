<?php

use infrajs\rest\Rest;
use infrajs\ans\Ans;
use infrajs\access\Access;
use akiyatkin\catalog\drive\Sheets;

$res = Rest::get(function( $id = false ){
	if (!$id) $id = Sheets::$conf['folder'];
	return Sheets::init($id);
});


return Ans::ans($res);
