<?php if ( !isset($_COOKIE['user_key']) ) { header("Location: /index.php"); exit(); } ?>
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
			<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"><a href="/console.php">WeiUZ</a></strong> / <small>Console</small></div>
		</div></div><hr>
		<div class="am-container">

<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
require_once 'cloudflare.class.php';
$cloudflare = new CloudFlare;
$action = $_GET['action'];

switch ($action) {
	case 'add':
	if(isset($_POST['submit']))
	{
		$zone_name = $_POST['domain'];
		$res = $cloudflare->zoneSet($zone_name,'example.com','www');
		if ( $res['result'] == 'success' )
		{
			$msg = '添加成功，<a href="/console.php">点击返回</a>管理中心';
		}else
		{
			$msg = $res['msg'];
		}
	}
?>
<form method="POST" action="" class="am-form am-form-horizontal">
	<div class="am-form-group">
		<label for="doc-ipt-3" class="am-u-sm-2 am-form-label">域名</label>
		<div class="am-u-sm-10">
			<input type="text" id="doc-ipt-3" name="domain" placeholder="输入你的域名">
		</div>
	</div>
	<div class="am-form-group">
		<div class="am-u-sm-10 am-u-sm-offset-2">
			<button type="submit" name="submit" class="am-btn am-btn-default">点击添加</button>
		</div>
	</div>
</form>
<?php
		echo $msg;
		break;
	case 'del':
		$zone_name = $_GET['domain'];
		$res = $cloudflare->zoneDelete($zone_name);
		if ( $res['response']['zone_deleted'] == true )
		{
			$msg = '删除成功，<a href="/console.php">点击返回</a>管理中心查看';
		}else
		{
			$msg = $res['msg'];
		}
?>

<?php
		echo $msg;
		break;
	case 'zones':
	$zone_name = $_GET['domain'];
	$res = $cloudflare->zoneLookup($zone_name);
?>
<strong><?php echo strtoupper($zone_name); ?></strong> / <small><a href='/console.php?action=edit&domain=<?php echo $zone_name; ?>'>编辑</a></small><hr>
<div class="am-scrollable-horizontal">
<table class="am-table am-table-striped am-table-hover am-table-striped am-text-nowrap">
	<thead>
		<tr>
			<th>域名</th>
			<th>回源地址</th>
			<th>CANME记录</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($res['response']['hosted_cnames'] as $key => $cnames) {
			echo '<tr>
			<td>'.$key.'</td>
			<td>'.$cnames.'</td>
			<td>'.$res['response']['forward_tos']["$key"].'</td>';
		}
		?>
	</tbody>
</table>
</div>
<?php
		break;
	case 'edit':
	$zone_name = $_GET['domain'];
	$res = $cloudflare->zoneLookup($zone_name);
	foreach ($res['response']['hosted_cnames'] as $key => $cnames)
	{
		$nameCount = strlen($zone_name)+1;
		$sub = substr($key,0,strlen($str)-$nameCount);
		if ( $sub != false )
		{
			$zoneAll .=  $sub.':'.$cnames.',';
		}
	}
	$root_res =$res['response']['hosted_cnames']["$zone_name"];
	$zoneAll = substr($zoneAll,0,strlen($str)-1);
	if (isset($_POST['submit']))
	{
		$subdomains = $_POST['subdomains'];
		$root_resolving = $_POST['root_resolving'];
		$res = $cloudflare->zoneSet($zone_name,$root_resolving,$subdomains);
		if ( $res['result'] == 'success' )
		{
			$msg = '更新成功，<a href="/console.php?action=zones&domain='.$zone_name.'">点击返回</a>管理中心查看';
		}else
		{
			$msg = $res['msg'];
		}
	}else{
?>
<form method="POST" action="" class="am-form">
  <fieldset>
    <legend>CNAME解析</legend>
    <div class="am-form-group">
      <label for="doc-ipt-email-1">请输入 @<?php echo $zone_name; ?> 回源地址，不更新无需修改</label>
      <input type="text" name="root_resolving" class="" value="<?php echo $root_res; ?>">
    </div>
    <div class="am-form-group">
      <label for="doc-ta-1">请务必严格按照如下格式填写 【 域名:回源地址 】英文,分割</label>
      <textarea name="subdomains" class="" rows="5" id="doc-ta-1"><?php echo $zoneAll; ?></textarea>
    </div>
    <p><button type="submit" name="submit" class="am-btn am-btn-default">提交更改</button></p>
  </fieldset>
</form>


<?php
	}
		echo $msg;
		break;
	default:
	$res = $cloudflare->userLookup();
?>

<a href="/console.php?action=add" class="am-btn am-btn-success am-round target="_blank"">添加域名</a>
<table class="am-table am-table-striped am-table-hover">
	<thead>
		<tr>
			<th>域名</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($res['response']['hosted_zones'] as $key => $value)
	{
		echo '<tr>
		<td>'.$value.'</td>
		<td><a href="/console.php?action=zones&domain='.$value.'">管理</a>丨<a href="/console.php?action=del&domain='.$value.'">删除</a></td>';
	}
	?>
	</tbody>
</table>

<?php
		break;
}
?>

<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?d9feea4b7cdd6f224be0f4c88a81c4f3";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>


<hr>
Powered By <a href="http://cloudflare.weiuz.com">WeiUZ.Com</a>
</div>
</body>
</html>