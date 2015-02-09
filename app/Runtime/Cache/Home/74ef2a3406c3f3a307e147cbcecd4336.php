<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章编辑-<?php echo ($data['title']); ?></title>
</head>
<body>
<div align="center">
  <h1>文章编辑</h1>
  <form name="form1" method="post" action="/index.php/Home/Index/update">
  <input type="hidden" name="id" value="<?php echo ($data['id']); ?>" />
  <table width="800" border="1">
    <tr>
      <td align="right">标 题：</td>
      <td>
        <input type="text" name="title" id="title" value="<?php echo ($data['title']); ?>">
      </td>
    </tr>
    <tr>
      <td align="right">描 述：</td>
      <td>
        <textarea name="description" cols="60" rows="4" id="description"><?php echo ($data['description']); ?></textarea></td>
    </tr>
    <tr>
      <td align="right">内 容：</td>
      <td><textarea name="content" cols="60" rows="4" id="content"><?php echo ($data['content']); ?></textarea></td>
    </tr>
    <tr>
      <td align="right"></td>
      <td><input type="submit" name="submit" id="submit" value="更 新"></td>
    </tr>
  </table></form>
</div>
</body>
</html>