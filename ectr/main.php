<?php 
// Just a Code Space
// Author: Just Adil

require_once "config.php";
include('qrlib.php');
$qr = new QR_BarCode();

                        // data base control
    function db_connect($log, $pass, $name, $server = 'localhost'){
        $connect = mysqli_connect("$server", "$log", "$pass", "$name");

        if (!$connect) {
            $connect = 'Error connect to Data base!';
        }
        return $connect;
    }
    function get_data_db($db, $table, $data, $index, $index2){
        $querry = mysqli_query($db, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

    function del_data_db($db, $table, $index, $index2){
    	mysqli_query($db, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

    function get_data($table, $data, $index, $index2){
    	global $ectr_connect;
        $querry = mysqli_query($ectr_connect, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

    function del_data($table, $index, $index2){
    	global $ectr_connect;
    	mysqli_query($ectr_connect, "DELETE FROM `$table` WHERE `$index` = $index2");
    }
    					// data control
    function cut_text($start, $text, $end){
    $t1 = mb_eregi_replace("(.*)[^.]{".$end."}$", '\\1', $text);
    $t2 = mb_eregi_replace("^.{".$start."}(.*)$", '\\1', $t1);
    $text = $t2;
    return $text;
	}

	function get_form($type){
		if ($type == "post" OR $type == "p") {
			$a = array();
			if (isset($_POST)){
			    foreach ($_POST as $key=>$value){
			        $a[$key]=$value;
			    }
			}
			print_r($a);
		}
		elseif ($type == "get" OR $type == "g") {
			$a = array();
			if (isset($_GET)){
			    foreach ($_GET as $key=>$value){
			        $a[$key]=$value;
			    }
			}
			print_r($a);
		}
		else{
			echo "Error type!";
		}
	}

	function format($file){
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

	function get_file($path, $name, $newn = "null"){
		if (!@copy($_FILES["$name"]['tmp_name'], $path.$_FILES["$name"]['name'])){
			return 1;
			}
		else {
			$fn = $_FILES["$name"]['name'];
			$type = format($fn);
			if ($newn != "null") {
				rename("$path$fn", "$path$newn.$type");
				return "$newn.$type";
			}
			else{
				$fnn=str_replace( " " , "_" , $_FILES["$name"]['name']);
				rename("$path$fn", "$path$fnn");
				return "$fnn";
			}
		}
	}

	function ls($dir, $i){
		$files = scandir($dir);
		$array = count($files);
		if ($i >= $array) {
			return "Out chain data!";
		}
		else{
			return $files[$i];
		}
	}	

	function ssg_data($link, $data){
			$ch = curl_init("$link?" . http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$return = curl_exec($ch);
			curl_close($ch);
			return $return;
		
	}

	function ssp_data($link, $data){
			$ch = curl_init("$link");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$re = curl_exec($ch);
			curl_close($ch);	
			return $re;
		
	}

	function rand_s($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	function rand_n($length = 6)
	{
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	function rand_sn($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	function addFileRecursion($zip, $dir, $start = '')
	{
		if (empty($start)) {
			$start = $dir;
		}
		
		if ($objs = glob($dir . '/*')) {
			foreach($objs as $obj) { 
				if (is_dir($obj)) {
					addFileRecursion($zip, $obj, $start);
				} else {
					$zip->addFile($obj, str_replace(dirname($start) . '/', '', $obj));
				}
			}
		}
	}

	function file_to_zip($name, $file){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE);
		$zip->addFile("$file", "$file");
		$zip->close();
	}

	function dir_to_zip($name, $dir){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE|ZipArchive::OVERWRITE);
		addFileRecursion($zip, "$dir");
		$zip->close();
	}

	function un_zip($file, $path){
		$zip = new ZipArchive();
		$zip->open("$file");
		$zip->extractTo("$path");
		$zip->close();
	}

						// system control
	function redirect($url, $sleep = 0){
		header('Refresh: '.$sleep.'; url='.$url);
		exit();
	}

	function locate_full(){
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $actual_link;
	}

	function locate($path = ""){
		if ($path) {
			$link="/$path";
		}
		else {
			$link=null;
		}
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$link";		
		
		return $actual_link;
	}

						// Session control

	function creat_sess($name, $id, $key){
		$_SESSION["$name"] = [
            "id" => $id,
            "key" => $key,
        ];
	}

	function close_sess($name){
		unset($_SESSION["$name"]);
	}

	function route_not_sess($name, $locate){
		if (!$_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	function route_sess($name, $locate){
		if ($_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	function check_sess($name){
		if (!$_SESSION["$name"]) {
    		return 0;
		}
		else{
			return 1;
		}
	}

						// Api controll

	function gen_qr($data, $path){
		global $qr;
        $qr->info($data);
        $qr->qrCode(500, $path);
	}

	function wa_api($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}

	function currency($currency_code, $format = 0) {

		$date = date('d/m/Y');
		$cache_time_out = 14400; 

		$file_currency_cache = './currency.xml'; 

		if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 0);

			$out = curl_exec($ch);

			curl_close($ch);

			file_put_contents($file_currency_cache, $out);

		}

		$content_currency = simplexml_load_file($file_currency_cache);

		return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);

	}

 ?>
