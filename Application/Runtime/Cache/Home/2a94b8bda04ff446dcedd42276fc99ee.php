<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>


    <input type="button" id="btn" value="点击">
</form>
</body>
</html>
<script>
$('#btn').click(function () {
    $.post("<?php echo U('xcx_add');?>",{goods_name:"abc",goods_price:111},function () {

    },'json')
});

</script>