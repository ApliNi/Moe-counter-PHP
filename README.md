# Moe-counter-PHP

\[即开即用\] Moe-counter 的 PHP 版本

![Moe-counter](https://ipacel.cc/+/MoeCounter/?u=github&t=base64&p=2)

---

使用方法: 将MoeCounter目录复制到您的网站任意一个目录下即可

要求: 安装PHP(我测试时使用php8.0版本), 开启SQLite扩展

---

功能: 
```
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
	// 仅以下Name可以使用Base64功能
	'base64WhiteList' => [
		'github',
	],


	// 存放图片的目录, 普通路径结尾需要添加斜杠
	'imgPath' => 'https://ipacel.cc/+/MoeCounter/img/',
	// 图片名称前缀, xxx{0-9}.png
	'imgNamePrefix' => 'gelbooru',
	// 图片格式
	'imgFormat' => 'gif',


	//图片宽高
	'imgWidth' => 45,
	'imgHeight' => 100,
);
```

```
?u=name # 定义一个用于计数的名称
可选 ?c=cat # 自定义猫图片前缀
可选 ?t=base64 # 使用 base64 传输图片, 用于解决 github 等网站无法加载的问题, 不支持自定义图片目录 "imgPath"
```



