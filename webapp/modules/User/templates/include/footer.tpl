{*
    ユーザページのフッタを表示するテンプレート。
    パラメータ)
        hideNavigator     ナビゲータリンクを隠したいなら true を指定する。
        showCompanyName   提供会社名を表示したいなら true を指定する。
        showCopyRight     コピーライトを表示したいなら true を指定する。
        isTop             トップページなら trueを指定

*}
{php}

    // トップページの場合はパラメータを自動的に調整する。
    if( $this->get_template_vars('isTop') ) {
        $this->assign('hideNavigator', true);
        $this->assign('showCopyRight', true);
    }

{/php}


    {if !$hideNavigator}
      <hr />
	  {if $carrier != 'iphone' && $carrier != 'android'}
      <a href="{url_for action='Index'}" accesskey="1">ｿﾈｯﾄTOP</a>
	  {/if}
      {if $userInfo.user_id}
        {if $userInfo.tutorial_step >= constant('User_InfoService::TUTORIAL_END')}

	  {if $carrier != 'iphone' && $carrier != 'android'}
          {$smarty.const.SHORTCUT_IND_MENU}<a href="{url_for action='Main'}" accesskey="{$smarty.const.SHORTCUT_KEY_MENU}">ｿﾈｯﾄﾒﾆｭｰ</a><br />
          {$smarty.const.SHORTCUT_IND_SUB1}<a href="{url_for action='Status'}" accesskey="{$smarty.const.SHORTCUT_KEY_SUB1}">ｽﾃｰﾀｽ</a>
	  {else}
<!--
       {image_tag file='page_bottom.gif'}
        <ul class="pc_menu">
          <li><a href="{url_for action='Index'}">{image_tag file='menu_top.png'}</a></li>
          <li><a href="{url_for module='Swf' action='Main'}">{image_tag file='menu_main.png'}</a></li>
          <li><a href="{url_for action='QuestList'}">{image_tag file='menu_quest.png'}</a></li>
          <li><a href="{url_for action='Status'}">{image_tag file='menu_status.png'}</a></li>
          <li><a href="{url_for action='Shop'}">{image_tag file='menu_shop.png'}</a></li>
        </ul>
        <hr />
-->
	  {/if}
        {else}
          {$smarty.const.SHORTCUT_IND_MENU}<a href="{url_for module='Swf' action='Tutorial'}" accesskey="{$smarty.const.SHORTCUT_KEY_MENU}">ﾁｭｰﾄﾘｱﾙ</a>
        {/if}
      <br />
      {/if}

    {/if}

    {if $showCompanyName || $showCopyRight}
      <hr />
      <div style="text-align:center">
        {if $showCompanyName}
          {if $smarty.const.PLATFORM_OPERATOR_URL}
            <a href="{$smarty.const.PLATFORM_OPERATOR_URL}">{if PLATFORM_TYPE!='mixi'}ｻｰﾋﾞｽ提供:{$smarty.const.COMPANY_NAME}{else}特定商取引法に基づく表記{/if}</a>
          {else}
            ｻｰﾋﾞｽ提供:{$smarty.const.COMPANY_NAME}
          {/if}
        {/if}
        {if $showCopyRight}
          <div style="font-size: 0.8em;color:#776451;">(C){$smarty.const.COMPANY_NAME}</div><br />
        {/if}
      </div>
      {if $isTop && $smarty.const.PLATFORM_TYPE=='gree'}
        <div style="text-align:right; font-size:x-small">
          <a href="{url_for action='DiaryList'}">コッソリ開発日誌</a>{if $diaryUpData}{$diaryUpData|datetime}{image_tag file='up.gif'}{/if}
        </div>
      {/if}
    {/if}

    {if $carrier != 'iphone' && $carrier != 'android'}
        {image_tag file='page_bottom.gif'}
    {/if}

  </div>
</body>
</html>
