<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>{$title}</title>
<link rel="stylesheet" href="css/design.css" type="text/css" media="all" />
</head>
<body>
<table class="wrapper">
<tr>
	<td>
{foreach from=$messages item=message}
{if ($message != '')}<font class="strong">{$message}</font><br />{/if}
{foreachelse}
{/foreach}
{foreach from=$errors item=error}
{if ($error != '')}<font color=red>{$error}</font><br />{/if}
{/foreach}
	</td>
</tr>
<tr>
	<td>
		<table class="contents">
		<tr>
			<td>
{$content|smarty:nodefaults}
			</td>
		</tr>
		</table>
		<table class="footer">
		<tr>
			<td class="footer_color">
			<table class="footer_intable">
			<tr>
				<td class="footer_img">&nbsp;</td>
				<td><span class="strong">Supported by ウィジット株式会社</span><br />Produced by WithIT Co., Ltd. </td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>