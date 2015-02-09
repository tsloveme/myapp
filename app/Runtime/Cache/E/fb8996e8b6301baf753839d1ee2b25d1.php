<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>员工管理</title>
</head>

<body>
<h1><center>员工管理</center></h1>
<div style="width:800px; margin:0 auto;">
<!--<div align="right"><a href="/index.php/E/Index/add.html">添加文章</a></div>-->
<form method="post" action="/index.php/E/Index/search" style="padding-bottom:8px;">
	<label>名 字:</label>
	<input type="text" name="first_name" placeholder="请输入名字"<?php if($f_name): ?>value="<?php echo ($f_name); ?>"
	<?php else: endif; ?> >
	<label>姓 氏:</label>
	<input type="text" name="last_name" placeholder="请输入姓氏"<?php if($l_name): ?>value="<?php echo ($l_name); ?>"
	<?php else: endif; ?> >
	<label>性 别:</label>
	<input type="radio" name="gender" value="M" <?php if($sex== "M"): ?>checked="checked"
	<?php else: endif; ?> autocomplete="off" />
	<label>男</label>
	<input type="radio" name="gender" value="F"  <?php if($sex== "F"): ?>checked="checked"
	<?php else: endif; ?> autocomplete="off" />
	<label>女</label>
	<input type="submit" value="搜索员工" />
</form>
<table cellspacing="0" border="1" width="100%">
	<tr>
		<th>员工号</th>
		<th>名 字</th>
		<th>姓 氏</th>
		<th>性 别</th>
		<th>入职日期</th>
		<th>出生日期</th>
	</tr>
	<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr <?php if(($mod) == ""): ?>style=" background-color:#e0e0e0;"<?php endif; ?> >
		<td><?php echo ($vo["emp_no"]); ?></td>
		<td><?php echo ($vo["first_name"]); ?></td>
		<td><?php echo ($vo["last_name"]); ?></td>
		<td><?php if($vo["gender"] == 'F'): ?>女
		<?php else: ?> 男<?php endif; ?></td>
		<td><?php echo ($vo["hire_date"]); ?></td>
		<td><?php echo ($vo["birth_date"]); ?></td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	<tr>
		<td colspan="6">
		<div><?php echo ($page); ?></div>
		</td>
	</tr>
</table>
</div>
</body>
</html>