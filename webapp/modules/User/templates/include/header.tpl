{*
    ユーザページのヘッダを表示するテンプレート。
    パラメータ)
        title   タイトルテキスト。省略可能。
        isTop   トップページなら trueを指定

*}
<?xml version="1.0" encoding="{$encode}" ?>
{$doctype|smarty:nodefaults}
<html>

<head>
  <title>{$smarty.const.SITE_NAME}</title>
  <meta http-equiv="Content-Type" content="text/html; charset={$encode}" />

  {if $carrier == 'au'}
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
  {/if}

</head>

<style type="text/css">{literal}
  a:link    {color: {/literal}{#aLinkTextColor#}{literal};}
  /* a:focus   {color: #FFFFFF;} */
  a:visited {color: {/literal}{#aLinkTextColor#}{literal};}
{/literal}</style>

{if $smarty.const.PLATFORM_JS}<script type="text/javascript" src="{$smarty.const.PLATFORM_JS}"></script>{/if}

<body style="background-color:{#mainBgColor#}; color:{#mainTextColor#}; margin:0px; padding:0px">
  <div style="font-size:{$css_small};">

    {if !$isTop}
      {image_tag file='header1.gif'}<br />
      {if $title}
	        <div style="text-align:center; color:{#titleTextColor#}">{$title}<br />{image_tag file='pHeaderBottom.gif'}</div>
      {/if}
    {/if}
