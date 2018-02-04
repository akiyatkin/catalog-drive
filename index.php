<?php

use infrajs\rest\Rest;
use infrajs\ans\Ans;
use akiyatkin\catalog\drive\Sheets;

$res = Rest::get(function($id){
	return Sheets::init($id);
});


return Ans::ans($res);
