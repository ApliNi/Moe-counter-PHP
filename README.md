# Moe-counter-PHP

\[即开即用\] Moe-counter 的 PHP 版本

![Moe-counter](https://ipacel.cc/+/MoeCounter/?u=github&t=xml)

---

使用方法: 将MoeCounter目录复制到您的网站任意一个目录下即可

要求: 安装PHP(我测试时使用php8.0版本), 开启SQLite扩展

---

配置: 
打开 index.php, 第4行开始的数组就是软件配置

URL: 
```
?u=name # 定义一个用于计数的名称
可选 ?c=cat # 自定义猫图片前缀 (默认使用配置中的值)
可选 ?t=xml # 使用 base64 传输图片, 用于解决 github 等网站无法加载的问题 (默认使用html)
```
例子: `https://ipacel.cc/+/MoeCounter/?u=name`



