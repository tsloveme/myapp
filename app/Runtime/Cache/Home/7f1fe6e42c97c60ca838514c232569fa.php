<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章添加</title>
</head>
<body>
<div align="center">
  <h1>新增</h1>
  <form name="form1" method="post" action="/index.php/Home/Index/addArticle">
  <table width="800" border="1">
    <tr>
      <td align="right">标 题：</td>
      <td>
        <input type="text" name="title" id="title">
      </td>
    </tr>
    <tr>
      <td align="right">描 述：</td>
      <td>
        <textarea name="description" cols="60" rows="4" id="description"></textarea></td>
    </tr>
    <tr>
      <td align="right">内 容：</td>
      <td><textarea name="content" cols="60" rows="4" id="content"></textarea></td>
    </tr>
    <tr>
      <td align="right"></td>
      <td><input type="submit" name="submit" id="submit" value="提交"></td>
    </tr>
  </table></form>
</div>
</body>
</html>