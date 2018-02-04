<?php
use infrajs\config\Config;
use infrajs\catalog\Catalog;
use infrajs\sequence\Sequence;

$r = 'catalog-cost';
Sequence::add(Catalog::$conf['dependencies'],[],$r);
Sequence::set(Config::$sys, ['catalog','dependencies'], Catalog::$conf['dependencies']); 

