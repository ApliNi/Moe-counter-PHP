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

### URL
```
?u=name # 定义一个用于保存数字的名称
可选 ?c=cat # 自定义猫图片文件名前缀 (默认使用配置中的值)
可选 ?t=html # 使用其他格式传输图片 (默认使用xml)
    xml 格式适用于 github 等图片经过中转的网站.  
    html 格式支持使用CDN托管图片, 适合放在自己的网页里.  
可选 ?l=left | right | counter # 使用html格式时可定义图片位置, 默认counter
```

例子: `https://ipacel.cc/+/MoeCounter/?u=name`

#### HTML 格式调用方法
```
<iframe src="https://ipacel.cc/+/MoeCounter/?u=name&t=html&l=right" frameborder="0" scrolling="no" width="100%" height="100px"></iframe>
```
如果您的主机带宽足够小, 可以使用html格式节省流量. 

---

图片来源: https://github.com/journey-ad/Moe-counter



