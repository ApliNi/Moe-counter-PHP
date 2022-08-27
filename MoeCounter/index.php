<?php
require_once 'lib.php';

// 配置文件
$c = array(
	// 默认运行模式
	/**
	 * ADD_NUM = 加计数器, 每次访问+1, 新建记录为0
	 * MONITOR = 数字显示模式, 此模式不需要数据库
	 * RECORD_NUM = 显示数据库中的记录总数
	 */
	'mode' => 'ADD_NUM',
	// 是否允许用户选择模式
	'selectMode' => true,
	// 默认渲染模式
	/**
	 * xml = XML图片, 适用于Github等代理获取图片的场景
	 * html = HTML代码, 适用于自己的网站和可以嵌入页面的场景, 支持使用CDN外链图片, 带宽占用很低
	 * string = 输出字符串, 意义不明, 最省带宽
	 */
	'out_mode' => 'xml',
	// 使用HTML格式时的图片位置, left | right | counter
	'html_align' => 'center',


	// 是否允许自动创建记录
	'createRecord' => true,
	// 允许自动创建的最大记录数量, 达到此值将不再继续创建
	'maxRecordNum' => 520000, // -1 禁用
	// 名称最大长度
	'maxNameLength' => 24,
	// 图片显示的最小数字长度
	'minNumLength' => 7,


	// 减计数器默认初始值
	'default_MINUS_NUM' => 9999999,


	// 存放图片的目录, 普通路径结尾需要添加斜杠
	//'imgPath-html' => 'https://ipacel.cc/+/MoeCounter/img/',
	'imgPath-html' => 'https://cdn.jsdelivr.net/gh/ApliNi/Moe-counter-PHP@main/MoeCounter/img/',
	'imgPath-xml' => 'img/',
	// 图片名称前缀 `xxx{0-9}.png`, 也可以使用原版Moe-counter的目录 `xxx/{0-9}.png`
	'img_prefix' => 'gelbooru',
	// 图片格式
	'imgFormat' => 'gif',


	//图片宽高
	'imgWidth' => 45,
	'imgHeight' => 100,
);



// 初始化
// 输出数字
$outNum = 0;
// 获取运行模式
$mode = isset($_GET['mode'])? $_GET['mode'] : $c['mode'];


// 运行模式
if($mode === 'ADD_NUM'){
	// 获取数据
	$name = getName();
	// 初始化数据库
	$db = openDB();

	// 获取数据库中的数字, 存在则返回正整数, 否则返回 -1
	$num = getNum($name);
	// 存在
	if($num !== -1){
		$outNum = $num + 1;
		setNum($name, $outNum);

	// 不存在
	}else{
		//是否可以创建记录
		$start = CANcreateRecord();
		if($start === 'ok'){
			// 创建记录
			addName($name, 0);
			$outNum = 0;
		}else{
			echo $start;
			exit;
		}
	}


}else if($mode === 'MONITOR'){
	// 获取数据
	$num = isset($_GET['num'])? $_GET['num'] : 0;
	$outNum = (int)$num;


}else if($mode === 'RECORD_NUM'){
	// 初始化数据库
	$db = openDB();
	// 获取记录总数
	$outNum = getSum();
}



// 渲染图片
$iM = renderImg($outNum);
echo $iM;
