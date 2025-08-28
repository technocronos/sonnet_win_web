{*
    指定されたhistory_logレコードを表示するHTMLを出力するテンプレート
    historyCore.tpl と違い、履歴番号、レス、称賛リンクなども表示する。

    パラメータ)
        history     表示したいhistory_logレコード。
                    compactを指定しない限り、"short_user_name" 列が追加されている必要がある。
                    "thumbnail_url" 列が追加されていれば利用するが、省略も可能。
        compact     trueを指定すると、主体者を隠す。
        hide_reply  レス数を隠すかどうか。省略時は表示する。
        hide_quote  レス先を隠すかどうか。省略時は表示する。
*}
{php}

    // この履歴をすでに称賛しているかどうかを取得する。
    $history = $this->get_template_vars('history');
    $admired = (bool)Service::create('History_Admiration')->getRecord($history['history_id'], $this->get_template_vars('userId'));
    $this->assign('admired', $admired);

{/php}

  {$history.create_at|datetime} No{$history.history_id}{if !$compact} <a href="{url_for module='User' action='HisPage' userId=`$history.user_id` _backto=true}" class="buttonlike label">{$history.short_user_name}</a>{/if}<br />

  {if !$compact}{platform_thumbnail src=`$history.thumbnail_url` id=`$history.user_id` size='M' float='left'}{/if}
  {if !$hide_quote && $history.reply_to}
    {foreach from=`$history.reply_to` item='replyTo'}
      <a href="{url_for action='CommentTree' top=`$replyTo` _backto=true}" class="buttonlike label">&lt;&lt;No{$replyTo}</a>
    {/foreach}<br />
  {/if}
  {include file='include/historyCore.tpl' history=`$history`}{if !$hide_reply && $history.reply_count} <a href="{url_for action='CommentTree' top=`$history.history_id` _backto=true}" class="buttonlike label">&lt;&lt;ﾚｽ{$history.reply_count}</a>{/if}
  <br clear="all" /><div style="clear:both"></div>

  <div>
    {if $history.goodness}{if $smarty.const.SONNET_NOW_OPEN}<a href="{url_for action='Admirers' id=`$history.history_id` _backto=true}" class="buttonlike label">{$history.goodness}</a>{/if}{/if}
    {if !$admired && $history.user_id != $userId}{if $smarty.const.SONNET_NOW_OPEN}<a href="{url_for action='HistoryTouch' touch='admire' id=`$history.history_id` _backto=true}" class="buttonlike label">{$smarty.const.ADMIRATION_NAME}</a>{/if}{/if}
    {if !$history.deleted_at && $history.user_id==$userId}<a href="{url_for action='HistoryTouch' touch='delete' id=`$history.history_id` _backto=true}" class="buttonlike label">削除×</a>{/if}
    {if !$history.deleted_at}{if $smarty.const.SONNET_NOW_OPEN}<a href="{url_for action='Comment' for=`$history.history_id` _backto=true}" class="buttonlike label">ﾚｽする⇒</a>{/if}{/if}
  </div>
