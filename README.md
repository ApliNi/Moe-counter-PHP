# Moe-counter-PHP

即开即用的 Moe-counter (PHP 版本)

![Moe-counter](https://ipacel.cc/+/MoeCounter/?u=github&t=xml)

---

### 使用方法

将MoeCounter目录复制到您的网站任意一个目录下即可. 

要求: 服务器有安装php, 启用sqlite扩展. 

---

### 配置
打开 index.php, 第4行开始的数组就是软件配置

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
`https://ipacel.cc/+/MoeCounter/?name=name`
`https://ipacel.cc/+/MoeCounter/?mode=ADD_NUM&name=name&out_mode=html&img_prefix=gelbooru&align=counter`

#### HTML 格式调用方法
```
<iframe src="---URL---" frameborder="0" scrolling="no" width="100%" height="100px"></iframe>
```
如果您的主机带宽足够小, 可以使用html格式节省流量. 

---

图片来源: https://github.com/journey-ad/Moe-counter



