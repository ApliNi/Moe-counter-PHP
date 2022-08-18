<?php

/// 配置 ///
$c = array(
	// 是否允许自动创建记录
	'createRecord' => true,
	// 允许自动创建的最大记录数量, 达到此值将不再继续创建
	'maxRecordNum' => 520000, // -1 禁用
	// 名称最大长度
	'maxNameLength' => 24,
	// 显示的最小数字长度
	'minNumLength' => 7,


	// 存放图片的目录, 普通路径结尾需要添加斜杠
	'imgPath-html' => 'https://cdn.jsdelivr.net/gh/From-pErfo/Moe-counter-PHP@main/MoeCounter/img/',
	
	'imgPath-xml' => 'img/',
	// 图片名称前缀, xxx{0-9}.png
	'imgNamePrefix' => 'gelbooru',
	// 图片格式
	'imgFormat' => 'gif',


	//图片宽高
	'imgWidth' => 45,
	'imgHeight' => 100,
);


// 全局变量
$Name = '';
$Num = 0;

// 获取URL中的名称 ?u=xxx
$Name = isset($_GET['u']) ? $_GET['u'] : '';
// 获取URL中的猫图片前缀
$imgPrefix = isset($_GET['c']) ? $_GET['c'] : $c['imgNamePrefix'];
// 指定数据格式, 用于在github等网站中显示
$imgType = isset($_GET['t']) ? $_GET['t'] : 'html';


// SQL特殊字符转义
$Name = SQLite3::escapeString($Name);
// 传入字符串检查
if (
	strlen($Name) >= $c['maxNameLength']
	|| strlen($imgPrefix) > 256
	|| strlen($imgType) > 10
) {
	echo '参数超出长度限制';
	exit;
};


// 初始化数据库
if (file_exists('Counter.db') === false) {
	echo '数据库不存在';
	exit();
}
$db = new SQLite3('Counter.db');
$db->busyTimeout(2000);
if (!$db) {
	exit();
}



// 判断是否存在记录
$ret = $db->query("SELECT Num FROM Counter WHERE Name = '$Name' LIMIT 1;");
$num = $ret->fetchArray(SQLITE3_ASSOC);

if (isset($num['Num'])) {
	// 更新记录 +1
	$start = $db->exec("UPDATE Counter SET Num = Num +1 WHERE Name = '$Name';");
	/*if($start !== true){
		//echo '记录更新失败';
		//exit;
	}*/

	$Num = $num['Num'];
} else {
	// 判断是否允许创建记录 createRecord
	if ($c['createRecord'] !== true) {
		echo '服务器不允许创建记录';
		exit;
	}

	// 判断最大记录数 maxRecordNum
	if ($c['maxRecordNum'] !== -1) {
		$ret = $db->query("SELECT max(rowid) FROM Counter;");
		$max_rowid = $ret->fetchArray(SQLITE3_ASSOC);
		if ($max_rowid['max(rowid)'] >= $c['maxRecordNum']) {
			echo '达到记录创建限制';
			exit;
		}
	}

	// 创建记录
	$state = $db->exec("INSERT INTO Counter (Name, Num) VALUES ('$Name', '1');");
	if ($state !== true) {
		echo '创建记录失败';
		exit;
	}

	$Num = 0;
};



// 渲染xml图片
$iM = '';
$Num = str_pad($Num, $c['minNumLength'], "0", STR_PAD_LEFT);

$width = $c['imgWidth'];
$height = $c['imgHeight'];
$allWidth = $c['minNumLength'] * $c['imgWidth'];


// 模式
if ($imgType === 'xml') {
	$forNum = 0;
	foreach (str_split($Num) as $key => $value) {
		$_width = $forNum * $width;

		$imgUrl = 'data:image/' . $c['imgFormat'] . ';base64,' . base64_encode(file_get_contents($c['imgPath-xml'] . $imgPrefix . $value . '.' . $c['imgFormat']));

		$iM .= <<< EOF
			<image x="$_width" y="0" width="$width" height="$height" xlink:href="$imgUrl" />
		EOF;

		$forNum = $forNum + 1;
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



} else if ($imgType === 'html') {
	$forNum = 0;
	foreach (str_split($Num) as $key => $value) {
		$_width = $forNum * $width;

		$imgUrl = $c['imgPath-html'] . $imgPrefix . $value . '.' . $c['imgFormat'];

		$iM .= <<< EOF
			<image x="$_width" y="0" width="$width" height="$height" xlink:href="$imgUrl" />
		EOF;

		$forNum = $forNum + 1;
	};

	$iM = <<< EOF
		<svg width="$allWidth" height="$height" version="1.1">
			$iM
		</svg>
	EOF;

	header("Content-Type: text/html; charset=utf-8");
}



// 输出
header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
echo $iM;
