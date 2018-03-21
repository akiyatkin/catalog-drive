<?php
namespace akiyatkin\catalog\drive;
use infrajs\path\Path;
use Google_Client;
use Google_Service_Drive;
use infrajs\load\Load;
use infrajs\excel\Xlsx;
use akiyatkin\boo\Cache;
use akiyatkin\boo\BooCache;
use infrajs\nostore\Nostore;

class Sheets {
	public static $conf = array(
		'folder' => false,
		'certificate'=>'~.client_secret.json'
	);
	public static function getClient() 
	{
		putenv("GOOGLE_APPLICATION_CREDENTIALS=".Path::resolve(static::$conf['certificate']));
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->setScopes(Google_Service_Drive::DRIVE);

		return $client;
	}
	public static function getServiceDrive() 
	{
		$client = Sheets::getClient();
		$service = new \Google_Service_Drive($client);
		return $service;
	}
	public static function getServiceSheets() 
	{
		$client = Sheets::getClient();
		$service = new \Google_Service_Sheets($client);
		return $service;
	}
	/*public static function makeHead($values) {
		$data = array();
		$head = false;
		foreach ($values as $k => $row) {
			if (!$head && sizeof($row) > 2) {
				$head = $row;
				continue;
			}
			if (!$head) {
				if (!isset($row[1])) $row[1] = '';
				if (!isset($row[0])) continue;
				$descr[$row[0]] = $row[1];
			} else {
				$r = array();
				foreach ($head as $n => $name) {
					if (!isset($row[$n])) $row[$n] = '';
					$r[$head[$n]] = $row[$n];
					
				}
				$data[] = $r;	
			} 
		}
		return $data;
	}*/
	public static function listFolder($id) {
		return BooCache::exec('Список файлов в папке Google', function ($id) {
			$service = Sheets::getServiceDrive();
			$result = array();
			$pageToken = NULL;
			$folder = $service->files->get($id);
			BooCache::setTitle($folder['name']);
			do {
				try {
				  $parameters = array('q' => "'".$id."' in parents and trashed=false");
				  if ($pageToken) {
						$parameters['pageToken'] = $pageToken;
				  }

				  $files = $service->files->listFiles($parameters);
				  $result = array_merge($result, $files->files);
				  $pageToken = $files->getNextPageToken();
				} catch (\Exception $e) {
				  break;
				}
			} while ($pageToken);
			return $result;
		},array($id));
	}
	public static function init($id, $options = array()) {
		$res = BooCache::exec('Данные каталога из Таблиц Google', function ($id) { 
			$result = Sheets::listFolder($id);
			BooCache::setTitle($id);
			$res = array();
			foreach ($result as $k => $file) {
					
				if ($file['mimeType'] != 'application/vnd.google-apps.spreadsheet') continue;
				$fd = Load::nameInfo($file['name']);
				
				/*$data = array();

				$data['name'] = $fd['name'];
				$data['id'] = $fd['id']; 
				$data['driveid'] = $file['id'];
				$data['date'] = $fd['date'];*/
				
				$d = Sheets::readBook($file['id']);
				$res[] = Xlsx::make($d,$fd['name']);
			}
			return $res;
		}, array($id));

		$data = Xlsx::initData($res, $options);
		
		return $data;
		
	}
	public static function readBook($id) {
		return BooCache::exec('Разбор Таблицы Google', function($id){

			$servsheet = Sheets::getServiceSheets();

			$response = $servsheet->spreadsheets->get($id);
			BooCache::setTitle($response->properties->title);

			$sheets = array();
			$ranges = array();
			foreach ($response->sheets as $s) {
			    $ranges[$s['properties']['title']] = $s['properties']['title'].'!A:Z';
			    $sheets[] = $s['properties']['title'];
			}
			$parameters = array('ranges' => array_values($ranges));
			$response = $servsheet->spreadsheets_values->batchGet($id, $parameters);
			
			$d = array();
			foreach($response['valueRanges'] as $k=>$range) {
				$d[$sheets[$k]] = $range['values'];
			}
			return $d;
		}, array($id));
	}
}