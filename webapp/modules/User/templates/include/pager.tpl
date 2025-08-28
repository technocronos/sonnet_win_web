{*
    ページャを表示するテンプレート。
    ページ切り替えのリンクは、現在のURLを元に生成する。
    また、ページ番号はGETパラメータ "page" で指定されているものとする。

    パラメータ)
        current     現在のページ番号。0スタート。
                    省略時はGETパラメータ "page" から取得。
        totalPages  全体ページ数。
        type        ページャのタイプ。以下のいずれか。省略時は neighbors。
                        none        なし
                        more        「もっと見る」
                        neighbors   「次へ」「前へ」
        moreUrl     type=more の場合の「もっと見る」のリンク先
*}
{php}
    // パラメータのデフォルト値を設定。
    if( is_null($this->get_template_vars('type')) )
        $this->assign('type', 'neighbors');
    if( is_null($this->get_template_vars('current')) )
        $this->assign('current', (int)$_GET['page']);

    // 現在のURLをもとにページ変え用のURLを取得。ページ番号は "--page--" にしておく。
    $this->assign('url', Common::genContainerURL(array(
        '_self' => true,
        'page' => '--page--',
    )));
{/php}


{if $totalPages > 1}

  {if $type=="neighbors"}

    <div style="text-align:center">

      {if $current > 0}
        <a href="{$url|replace:'--page--':$current-1}" accesskey="*" class="buttonlike next" pc-style="line-height:1.5em; padding:1px 1ex">*前ﾍﾟｰｼﾞ</a>
      {/if}

      {if $current > 0  &&  $current+1 < $totalPages}
      |
      {/if}

      {if $current+1 < $totalPages}
        <a href="{$url|replace:'--page--':$current+1}" accesskey="#" class="buttonlike next" pc-style="line-height:1.5em; padding:1px 1ex">次ﾍﾟｰｼﾞ#</a>
      {/if}
    </div>

  {elseif $type=="more"}

    <div style="text-align:right">
      <a href="{$moreUrl}" class="buttonlike next" pc-style="line-height:1.5em; padding:1px 1ex">もっと見る⇒</a>
    </div>

  {/if}
{/if}
