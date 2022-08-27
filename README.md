# Moe-counter-PHP

即开即用的 Moe-counter (PHP 版本)

![Moe-counter](https://ipacel.cc/+/MoeCounter2/?name=github)


---

### 使用方法

将MoeCounter目录复制到您的网站任意一个目录下即可. 

要求: 服务器有安装php, 启用sqlite扩展. 

---


### 配置
打开 index.php, 第4行开始的数组就是软件配置

<details><summary>点击展开: 默认配置</summary>

```
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
```

</details>



### URL参数
参数的默认值可以在配置文件中找到
```
# 运行模式
?mode=
	ADD_NUM #加计数器, 每次访问+1, 新建记录为0
		&name=name #定义一个用于计数的名称
	MONITOR #数字显示模式, 此模式不需要数据库
		&num=112 #需要显示的数字
	RECORD_NUM #显示数据库中的记录总数

# 渲染模式
&out_mode=
	xml #XML图片, 适用于Github等代理获取图片的场景
		&img_prefix=gelbooru #猫图片名称前缀 `xxx{0-9}.png`, 也可以使用原版Moe-counter的目录 `xxx/{0-9}.png`
	html #HTML代码, 适用于自己的网站和可以嵌入页面的场景, 支持使用CDN外链图片, 带宽占用很低
		&img_prefix=gelbooru #猫图片名称前缀 `xxx{0-9}.png`, 也可以使用原版Moe-counter的目录 `xxx/{0-9}.png`
		&align=counter #使用HTML格式时的图片位置, left | right | counter
	string #输出字符串, 意义不明, 最省带宽
```

例子:   
`https://ipacel.cc/+/MoeCounter2/?name=name`  
`https://ipacel.cc/+/MoeCounter2/?mode=ADD_NUM&name=name&out_mode=html&img_prefix=gelbooru&align=counter`  
`https://ipacel.cc/+/MoeCounter2/?mode=MONITOR&num=12345678901234`  

#### HTML 格式调用方法
```
<iframe src="---URL---" frameborder="0" scrolling="no" width="100%" height="100px"></iframe>
```
如果您的主机带宽足够小, 可以使用html格式节省流量. 

---
### v2版本的变化
添加了更多功能, 使用完整URL参数名. 此版本与v1的URL完全不兼容, 数据库不影响. 

---

图片来源: https://github.com/journey-ad/Moe-counter



