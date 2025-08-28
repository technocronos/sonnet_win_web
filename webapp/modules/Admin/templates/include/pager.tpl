{*
    ページャを表示するテンプレート。
    ページ切り替えのリンクは、現在のURLを元に生成する。
    また、ページ番号はGETパラメータ "page" で指定されているものとする。

    パラメータ)
        current     現在のページ番号。0スタート。
                    省略時はGETパラメータ "page" から取得。
        totalPages  全体ページ数。
*}
{php}
    // パラメータのデフォルト値を設定。
    if( is_null($this->get_template_vars('current')) )
        $this->assign('current', (int)$_GET['page']);

    // ページ変え用のURLを取得。ページ番号は "--page--" にしておく。
    $this->assign('url', Common::genURL(array(
        '_self' => true,
        'page' => '--page--',
    )));
{/php}


{if $totalPages > 1}

  {if $current > 0}
    <a href="{$url|replace:'--page--':'0'}">&lt;&lt;</a>
    <a href="{$url|replace:'--page--':$current-1}">&lt;</a>
  {else}
    &lt;&lt;
    &lt;
  {/if}

  {section name='pages' loop=$totalPages}
    {if $smarty.section.pages.index != $current}
      <a href="{$url|replace:'--page--':$smarty.section.pages.index}">{$smarty.section.pages.index+1}</a>
    {else}
      {$smarty.section.pages.index+1}
    {/if}
  {/section}

  {if $current < $totalPages-1}
    <a href="{$url|replace:'--page--':$current+1}">&gt;</a>
    <a href="{$url|replace:'--page--':$totalPages-1}">&gt;&gt;</a>
  {else}
    &gt;&gt;
    &gt;
  {/if}

{/if}
