<?php

use infrajs\rest\Rest;
use infrajs\ans\Ans;
use infrajs\access\Access;
use infrajs\nostore\Nostore;
use akiyatkin\catalog\drive\Sheets;

Access::test(true);
Nostore::on();
$res = Rest::get(function( $id = false ){
	if (!$id) $id = Sheets::$conf['folder'];
	return Sheets::init($id);
});


return Ans::ans($res);
