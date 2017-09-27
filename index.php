<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
require_once 'cloudflare.class.php';
if (isset($_POST['submit']))
{
	$cloudflare_email = $_POST['cloudflare_email'];
	$cloudflare_pass = $_POST['cloudflare_pass'];
	$cloudflare = new CloudFlare;
	$res = $cloudflare->userCreate($cloudflare_email,$cloudflare_pass);
	if ($res['result'] == 'success')
	{
		setcookie('cloudflare_email',$res['response']['cloudflare_email']);
		setcookie('user_key',$res['response']['user_key']);
		header("location: /console.php");
	}else
	{
		$msg = $res['msg'];
	}
}
?>

<!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="WeiUZ CloudFlare Partners">
	<meta name="keywords" content="WeiUZ">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WeiUZ CloudFlare Partners</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="icon" type="image/png" href="/assets/i/favicon.png">
	<link rel="stylesheet" href="/amazeui.min.css">
</head>
<body>
	<div class="am-container">
		<div class="am-cf am-padding am-padding-bottom-0 data-am-sticky">
			<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href="/">WeiUZ</a></strong> / <small>Index</small></div>
		</div>
	</div><hr>

	<div class="am-container">
		<form method="POST" action="" class="am-form am-form-horizontal">
			<div class="am-form-group">
				<label for="doc-ipt-3" class="am-u-sm-2 am-form-label">电子邮件</label>
				<div class="am-u-sm-10">
					<input type="email" id="doc-ipt-3" name="cloudflare_email" placeholder="输入你的电子邮件">
				</div>
			</div>

			<div class="am-form-group">
				<label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">密码</label>
				<div class="am-u-sm-10">
					<input type="password" id="doc-ipt-pwd-2" name="cloudflare_pass" placeholder="设置一个密码吧">
				</div>
			</div>

			<div class="am-form-group">
				<div class="am-u-sm-10 am-u-sm-offset-2">
					<button type="submit" name="submit" class="am-btn am-btn-default">提交登入 / 注册</button>
				</div>
			</div>
		</form>
		<?php echo $msg ?>;

<hr>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?d9feea4b7cdd6f224be0f4c88a81c4f3";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

Powered By <a href="//cloudflare.weiuz.com">WeiUZ.Com</a>
	</div>

</body>
</html>