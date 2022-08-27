<?php

/// 配置 ///
$c = array(
	// 是否允许自动创建记录
	'createRecord' => true,
	// 允许自动创建的最大记录数量, 达到此值将不再继续创建
	'maxRecordNum' => 520000, // -1 禁用
	// 名称最大长度
	'maxNameLength' => 24,
	// 图片显示的最小数字长度
	'minNumLength' => 7,


	// 存放图片的目录, 普通路径结尾需要添加斜杠
	//'imgPath-html' => 'https://ipacel.cc/+/MoeCounter/img/',
	'imgPath-html' => 'https://cdn.jsdelivr.net/gh/ApliNi/Moe-counter-PHP@main/MoeCounter/img/',
	'imgPath-xml' => 'img/',
	// 图片名称前缀 `xxx{0-9}.png`, 也可以使用原版Moe-counter的目录 `xxx/`
	'imgNamePrefix' => 'gelbooru',
	// 图片格式
	'imgFormat' => 'gif',


	//图片宽高
	'imgWidth' => 45,
	'imgHeight' => 100,
);


// 计数器
$Num = 0;
// 获取URL中的名称 ?u=xxx
$Name = isset($_GET['u']) ? $_GET['u'] : '';
// 获取URL中的猫图片前缀
$imgPrefix = isset($_GET['c']) ? $_GET['c'] : $c['imgNamePrefix'];
// 指定数据格式, 用于在github等网站中显示
$imgType = isset($_GET['t']) ? $_GET['t'] : 'xml';
// html格式时指定显示位置
$html_imgLocation = isset($_GET['l']) ? $_GET['l'] : 'center';


// SQL特殊字符转义
$Name = SQLite3::escapeString($Name);
// 传入字符串检查
if (
	strlen($Name) >= $c['maxNameLength']
	|| strlen($imgPrefix) > 256
	|| strlen($imgType) > 5
	|| strlen($html_imgLocation) > 7
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
if (!$db) {exit();}



// 判断是否存在记录
$ret = $db->query("SELECT Num FROM Counter WHERE Name = '$Name' LIMIT 1;");
$num = $ret->fetchArray(SQLITE3_ASSOC);

// 更新记录
if (isset($num['Num'])) {
	// +1
	$start = $db->exec("UPDATE Counter SET Num = Num +1 WHERE Name = '$Name';");
	// if($start !== true){
	// 	echo '更新记录失败';
	// 	exit;
	// }

	$Num = $num['Num'];



// 创建记录
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



// 输出图片
$iM = '';
// 补 0. 按每个字分割为数组
$Num = str_pad($Num, $c['minNumLength'], "0", STR_PAD_LEFT);
$Num = str_split($Num);
// 图片尺寸
$width = $c['imgWidth'];
$height = $c['imgHeight'];
$allWidth = $c['minNumLength'] * $c['imgWidth'];


// 模式
if ($imgType === 'xml') {
	foreach ($Num as $key => $value) {
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



// 模式
} else if ($imgType === 'html') {

	foreach ($Num as $key => $value) {
		$img = $c['imgPath-html'] . $imgPrefix . $value . '.' . $c['imgFormat'];

		$iM .= <<< EOF
		<img src="$img" width="$width" height="$height" />
		EOF;
	};

	$iM = <<< EOF
	<body style="margin:0; padding:0;"><div style="min-width:$allWidth; height:$height; text-align:$html_imgLocation;">$iM</div></body>
	EOF;

	header("Content-Type: text/html; charset=utf-8");
}



// 输出
header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
echo $iM;
