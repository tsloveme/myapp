<?php if (!defined('THINK_PATH')) exit();?> <!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章列表</title>
</head>

<body>
<h1><center>文章列表</center></h1>
<div style="width:800px; margin:0 auto;">
<div align="right"><a href="/index.php/Home/Index/add.html">添加文章</a></div>
<!--文章列表循环输出-->
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="border:1px #ccc solid; margin:8px 0;">
    	<h2><a href="/index.php/Home/Index/read/id/<?php echo ($vo["id"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></h2>
        <p><?php echo ($vo["description"]); ?></p>
        <p align="right"><small>
        	<a href="/index.php/Home/Index/read/id/<?php echo ($vo["id"]); ?>">查看</a> 
        	<a href="/index.php/Home/Index/edit/id/<?php echo ($vo["id"]); ?>">编辑</a> 
        	<a href="/index.php/Home/Index/delete/id/<?php echo ($vo["id"]); ?>">删除</a> 
        </small></p>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>

</div>
</body>
</html>