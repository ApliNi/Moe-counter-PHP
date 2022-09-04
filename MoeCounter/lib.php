<?php


// 获取URL参数中的name
function getName(){
	global $c;
	$name = isset($_GET['name']) ? $_GET['name'] : '';
	$name = SQLite3::escapeString($name);
	if (mb_strlen($name, 'UTF8') >= $c['maxNameLength']) {
		echo '参数超出长度限制';
		exit;
	}

	return $name;
}


// 初始化数据库
function openDB(){
	// 初始化数据库
	if (file_exists('Counter.db') === false) {
		echo '数据库不存在';
		exit();
	}
	$db = new SQLite3('Counter.db');
	$db->busyTimeout(2000);
	if (!$db) {exit();}

	return $db;
}


// 获取数值
function getNum($name){
	global $db;
	$ret = $db->query("SELECT Num FROM Counter WHERE Name = '$name';");
	$num = $ret->fetchArray(SQLITE3_ASSOC);

	return isset($num['Num'])? $num['Num'] : -1;
}


// 获取记录总数
function getSum(){
	global $db;
	$ret = $db->query("SELECT max(rowid) FROM Counter;");
	$sum = $ret->fetchArray(SQLITE3_ASSOC);

	return isset($sum['max(rowid)'])? $sum['max(rowid)'] : '';
}


// 修改记录
function setNum($name, $num){
	global $db;
	$start = $db->exec("UPDATE Counter SET Num = '$num' WHERE Name = '$name';");

	return $start;
}


// 创建新记录
function addName($name, $num = 0){
	global $db;
	$state = $db->exec("INSERT INTO Counter (Name, Num) VALUES ('$name', '$num');");

	return $state;
}


// 判断是否可以创建记录
function CANcreateRecord(){
	global $c;
	$m = 'ok';

	// 判断是否允许创建记录 createRecord
	if ($c['createRecord'] !== true) {
		$m = '服务器不允许创建记录';
	}

	// 判断最大记录数 maxRecordNum
	if ($c['maxRecordNum'] !== -1  &&  getSum() >= $c['maxRecordNum']) {
		$m = '达到记录创建限制';
	}

	return $m;
}


// 渲染图片
function renderImg($outNum){
	global $c;
	// 渲染图片
	$iM = '';
	// 补 0, 转换为字符串
	$outNum = str_pad($outNum, $c['minNumLength'], "0", STR_PAD_LEFT);

	// 指定输出图片的格式
	$outMode = isset($_GET['out_mode']) ? $_GET['out_mode'] : $c['out_mode'];
	// 获取URL中的猫图片前缀
	$imgPrefix = isset($_GET['img_prefix']) ? $_GET['img_prefix'] : $c['img_prefix'];

	// 图片尺寸
	$width = $c['imgWidth'];
	$height = $c['imgHeight'];
	$allWidth = strlen($outNum) * $c['imgWidth'];


	if ($outMode === 'xml') { // XML 图片格式
		// 按每个字分割为数组
		$outNum = str_split($outNum);

		foreach ($outNum as $key => $value) {
			$_width = $key * $width;

			$img = 'data:image/' . $c['imgFormat'] . ';base64,' . base64_encode(file_get_contents($c['imgPath-xml'] . $imgPrefix . $value . '.' . $c['imgFormat']));

			$iM .= <<< EOF
			<image x="$_width" y="0" width="$width" height="$height" xlink:href="$img" />
			EOF;
		};

		// 添加xml标志
		$iM = <<< EOF
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="$allWidth" height="$height" version="1.1">
			<title>[IpacEL]/ MoeCount</title>
			<g>
				$iM
			</g>
		</svg>
		EOF;

		header("Content-Type: image/svg+xml; charset=utf-8");


	} else if ($outMode === 'html') { // 输出 HTML 代码
		// html格式时指定显示位置
		$html_imgLocation = isset($_GET['align']) ? $_GET['align'] : $c['html_align'];
		
		// 按每个字分割为数组
		$outNum = str_split($outNum);

		foreach ($outNum as $key => $value) {
			$img = $c['imgPath-html'] . $imgPrefix . $value . '.' . $c['imgFormat'];

			$iM .= <<< EOF
			<img src="$img" width="$width" height="$height" />
			EOF;
		};

		$iM = <<< EOF
		<body style="margin:0; padding:0;"><div style="min-width:$allWidth; height:$height; text-align:$html_imgLocation;">$iM</div></body>
		EOF;

		header("Content-Type: text/html; charset=utf-8");


	}else if($outMode === 'string'){ //输出字符串
		$iM = $outNum;
	}



	// 输出
	header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
	return $iM;
}
