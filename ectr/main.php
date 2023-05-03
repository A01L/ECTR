<?php
/* 
	-Easy Controll (ECTR)-
	Author: Just Adil
	Group: Just a Code Space
	GitHub: https://GitHub.com/A01L
	Version: ECTR 0.3v Beta
 */


//Set all config for global
require_once "config.php";
include('qrlib.php');

//For function QrGen
$qr = new QR_BarCode();

                        // data base control

	/*
		@param string $db => Connect to DataBase
		@param string $table => Table in DateBase
		@param string $data => Data for getting
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function get_data_db($db, $table, $data, $index, $index2){
        $querry = mysqli_query($db, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

	/*
		@param string $db => Connect to DataBase
		@param string $table => Table in DateBase
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function del_data_db($db, $table, $index, $index2){
    	mysqli_query($db, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

	/*
		Only controll main config DataBase!
		@param string $table => Table in DateBase
		@param string $data => Data for getting
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function get_data($table, $data, $index, $index2){
    	global $ectr_connect;
        $querry = mysqli_query($ectr_connect, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
    }

	/*
		Only controll main config DataBase!
		@param string $table => Table in DateBase
		@param string $index => Name column in table for Equile
		@param string $index2 => Equile value in table column value (IDENTY)
	*/
    function del_data($table, $index, $index2){
    	global $ectr_connect;
    	mysqli_query($ectr_connect, "DELETE FROM `$table` WHERE `$index` = $index2");
    }

    					// data control

	/*
		@param integer $start => count simbols for cutting start string
		@param string $text => Text
		@param integer $end => count simbols for cutting end string
	*/
    function cut_text($start, $text, $end){
    $t1 = mb_eregi_replace("(.*)[^.]{".$end."}$", '\\1', $text);
    $t2 = mb_eregi_replace("^.{".$start."}(.*)$", '\\1', $t1);
    $text = $t2;
    return $text;
	}

	/*
		@param string $type => type send data GET[g] or POST[p]
	*/
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

	/*
		@param string $file => fullname (file) 
	*/
	function format($file){
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

	/*
		@param string $path => path for save file
		@param string $name => (form->input->attr->name) name input in forms  
		@param string $newn => rename file before save
	*/
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

	/*
		@param string $dir => path to dir for scanning 
		@param integer $i => array index 
	*/
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

	/*
		@param string $link => link for sending GET data
		@param array $data => array data ['name' = 'value', ...]
	*/
	function ssg_data($link, $data){
			$ch = curl_init("$link?" . http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$return = curl_exec($ch);
			curl_close($ch);
			return $return;
		
	}
	
	/*
		@param string $link => link for sending POST data
		@param array $data => array data ['name' = 'value', ...]
	*/
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

	/*
		@param integer $length => length simbols for generate [Default value = 6]
	*/
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

	/*
		@param integer $length => length number for generate [Default value = 6]
	*/
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

	/*
		@param integer $length => length hybrid simbols and number for generate [Default value = 6]
	*/
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

	// Module function for zip controll system 
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

	/*
		@param string $name => new zip file Name
		@param string $file => path to file for add zip
	*/
	function file_to_zip($name, $file){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE);
		$zip->addFile("$file", "$file");
		$zip->close();
	}

	/*
		@param string $name => new zip file Name
		@param string $dir => path to dir for add zip
	*/
	function dir_to_zip($name, $dir){
		$zip = new ZipArchive();
		$zip->open("$name.zip", ZipArchive::CREATE|ZipArchive::OVERWRITE);
		addFileRecursion($zip, "$dir");
		$zip->close();
	}

	/*
		@param string $file => zip file for unzip 
		@param string $path => path to extract files in zip
	*/
	function un_zip($file, $path){
		$zip = new ZipArchive();
		$zip->open("$file");
		$zip->extractTo("$path");
		$zip->close();
	}

						// system control

	/*
		@param string $url => URL for redirecting 
		@param integer $sleep => sleep timer
	*/
	function redirect($url, $sleep = 0){
		header('Refresh: '.$sleep.'; url='.$url);
		exit();
	}

	/*
		Getting full locate to runned script file
	*/
	function locate_full(){
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $actual_link;
	}

	/*
		@param string $path => (subdir) dir before hostname
	*/
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

/*
		@param string $name => session name
		@param integer $id => set ID to session 
		@param string $key => set KEY to session
*/
	function creat_sess($name, $id, $key){
		$_SESSION["$name"] = [
            "id" => $id,
            "key" => $key,
        ];
	}

/*
		@param string $name => session name for closing
*/
	function close_sess($name){
		unset($_SESSION["$name"]);
	}

/*
		@param string $name => valid session name
		@param string $locate => link for redirect if session name no valid
*/
	function route_not_sess($name, $locate){
		if (!$_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

/*
		@param string $name => session name
		@param string $locate => link for redirect
*/
	function route_sess($name, $locate){
		if ($_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

/*
		@param string $name => session name for checking status [0=not, 1=yes]
*/
	function check_sess($name){
		if (!$_SESSION["$name"]) {
    		return 0;
		}
		else{
			return 1;
		}
	}

						// Api controll

/*
		@param string $data => data for encode to QR image
		@param string $path => path for saving result QR image
*/
	function gen_qr($data, $path){
		global $qr;
        $qr->info($data);
        $qr->qrCode(500, $path);
	}

/*
		@param string $number => phone number for redirect 
		@param string $msg => message for sending
*/
	function wa_api($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}

/*
		@param string $currency_code => official country currency code 
		@param string $format => format for getting (n.000) [Default = 0]
*/
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

									//Three JS controll on php

/*
		@param string $display => class name block for render
		@param string $path => path to model .gltf
		@param string $paf => if sript run in other dir, use it param for locate path to ECTR lib
		@param string $bg => background color for render scene 
		@param string<int $py => place level [Default = -1]
*/
function add_gltf($display, $path, $zoom=60, $paf="", $bg="#ffffff", $py="-1"){
	echo'
	
<script type="importmap">
        {
            "imports": {
                "three": "./'.$paf.'ectr/build/three.module.js",
                "OrbitControls": "./'.$paf.'ectr/jsm/controls/OrbitControls.js",
                "GLTFLoader": "./'.$paf.'ectr/jsm/loaders/GLTFLoader.js",
                "RectAreaLightHelper": "./'.$paf.'ectr/jsm/helpers/RectAreaLightHelper.js",
                "RectAreaLightUniformsLib": "./'.$paf.'ectr/jsm/lights/RectAreaLightUniformsLib.js"
            }
        }
    </script>

    <script type="module">

        import * as THREE from "three";
        import { OrbitControls } from "OrbitControls";
        import { GLTFLoader } from "GLTFLoader";
        import { RectAreaLightHelper } from "RectAreaLightHelper"
        import { RectAreaLightUniformsLib } from "RectAreaLightUniformsLib";

        function init() {
            let container = document.querySelector(".'.$display.'");

            const scene = new THREE.Scene()
            scene.background = new THREE.Color("'.$bg.'");

            const camera = new THREE.PerspectiveCamera('.$zoom.', window.innerWidth / window.innerHeight, 0.1, 3000);
            camera.position.set(1, 0.5, 1)
            

            const renderer = new THREE.WebGLRenderer({antialias: true})
            renderer.setSize(window.innerWidth, window.innerHeight)
            container.appendChild(renderer.domElement)

            let plain;
            {
                plain = new THREE.Mesh(
                    new THREE.PlaneGeometry(1000, 1000),
                    new THREE.MeshBasicMaterial({color: "'.$bg.'"})
                )
                plain.reciveShadow = true;
                plain.position.set(0, '.$py.', 0)
                plain.rotateX(-Math.PI / 2);
                scene.add(plain)
            }

            {
                const loader = new GLTFLoader();
                loader.load("./'.$path.'", gltf => {
                scene.add(gltf.scene);
                }, 
                    function (error) {
                        console.log("Error: " + error)
                    }
                )
            }
            
            {
                const light = new THREE.DirectionalLight(0xffffff, 1)
                light.position.set(-2, 0, 10)
                light.lookAt(1, -1, 0)
                scene.add(light)

                // Helper
                // const helper = new THREE.DirectionalLightHelper(light, 5)
                // scene.add(helper)
            }

            {
                const light = new THREE.DirectionalLight(0xffffff, 1)
                light.position.set(2, 0, 5)
                light.lookAt(0, 1, 0)
                scene.add(light)

                // Helper
                // const helper = new THREE.DirectionalLightHelper(light, 5)
                // scene.add(helper)
            }

            RectAreaLightUniformsLib.init();
            {
                const rectLight = new THREE.RectAreaLight(0xffffff, 1, 100, 100);
                rectLight.position.set(-10,0,0)
                rectLight.rotation.y = Math.PI + Math.PI/4;
                scene.add(rectLight)
            }

            {
                const rectLight = new THREE.RectAreaLight(0xffffff, 1, 100, 100);
                rectLight.position.set(10,0,0)
                rectLight.rotation.y = Math.PI - Math.PI/4;
                scene.add(rectLight)
            }
            

            const controls = new OrbitControls(camera, renderer.domElement);
            controls.autoRotate = true;
            controls.autoRotateSpeed = 5;
            controls.enableDamping = true;

            window.addEventListener("resize", onWindowResize, false)
            
            function onWindowResize() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize(window.innerWidth, window.innerHeight)
            }

            function animate() {
                requestAnimationFrame(animate)
                controls.update();
                renderer.render(scene, camera)
            }
            animate()
        }
        init()
    </script>';
}

/*
		@param string $display => class name block for render
		@param string $path => path to model .vox
		@param integer $zoom => camera zoom
		@param string $paf => if sript run in other dir, use it param for locate path to ECTR lib
		@param string $bg => background color for render scene 
*/
function add_vox($display, $path, $zoom=50, $paf="", $bg="#ffffff"){
    echo '<script async src="'.$paf.'ectr/es-module-shims.js"></script>

		<script type="importmap">
			{
				"imports": {
					"three": "./'.$paf.'ectr/build/three.module.js",
					"three/addons/": "./'.$paf.'ectr/jsm/"
				}
			}
		</script>

		<script type="module">

			import * as THREE from "three";

			import { OrbitControls } from "three/addons/controls/OrbitControls.js";
			import { VOXLoader, VOXMesh } from "three/addons/loaders/VOXLoader.js";

			let camera, controls, scene, renderer;

			init();

			function init() {

               

				camera = new THREE.PerspectiveCamera( '.$zoom.', window.innerWidth / window.innerHeight, 0.01, 10 );
				camera.position.set( 0.175, 0.075, 0.175 );

				scene = new THREE.Scene();
                scene.background = new THREE.Color("'.$bg.'");
				scene.add( camera );

				// light

				const hemiLight = new THREE.HemisphereLight( 0xcccccc, 0x444444, 1 );
				scene.add( hemiLight );

				const dirLight = new THREE.DirectionalLight( 0xffffff, 0.75 );
				dirLight.position.set( 1.5, 3, 2.5 );
				scene.add( dirLight );

				const dirLight2 = new THREE.DirectionalLight( 0xffffff, 0.5 );
				dirLight2.position.set( - 1.5, - 3, - 2.5 );
				scene.add( dirLight2 );

				const loader = new VOXLoader();
				loader.load( "'.$path.'", function ( chunks ) {

					for ( let i = 0; i < chunks.length; i ++ ) {

						const chunk = chunks[ i ];

						// displayPalette( chunk.palette );

						const mesh = new VOXMesh( chunk );
						mesh.scale.setScalar( 0.0015 );
						scene.add( mesh );

					}

				} );

				// renderer

				let container = document.querySelector(".'.$display.'");

				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );

				// controls

				controls = new OrbitControls( camera, renderer.domElement );
				controls.minDistance = .1;
				controls.maxDistance = 0.5;

				//

				window.addEventListener( "resize", onWindowResize );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			function animate() {

				requestAnimationFrame( animate );

				controls.update();

				renderer.render( scene, camera );

			}
			animate();


		</script>';
}


}

?>
