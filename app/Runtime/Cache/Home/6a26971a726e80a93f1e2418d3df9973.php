<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ($detial['title']); ?></title>
</head>
<body>
	<div style="width:800px; margin:0 auto; padding:15px; border:1px #e0e0e0 solid">
    	<h1 style="line-height:1.2; "><?php echo ($detial['title']); ?></h1>
        <p style="color:#999"><small><?php echo ($detial['description']); ?></small></p>
        <hr>
        <br />
        <div>
		<?php echo ($detial['content']); ?>
        </div>
    </div>
</body>
</html>