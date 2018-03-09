<?php
use infrajs\config\Config;
use infrajs\catalog\Catalog;
use infrajs\sequence\Sequence;

$r = 'catalog-drive';
Sequence::add(Catalog::$conf['dependencies'],[],$r);
Sequence::set(Config::$sys, ['catalog','dependencies'], Catalog::$conf['dependencies']); 

