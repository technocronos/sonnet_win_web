{*
    ユーザの一覧を表示するテンプレート。
    パラメータ)
        list        ユーザの一覧
        with        一覧に含む内容を以下の値でカンマ区切りで示す。
                        chara   キャラクター名と階級とレベル
        params      他者のページへのリンクに追加するパラメータ。
*}
{php}

    // backto パラメータが指定されていないなら自動的に取得。
    $params = $this->get_template_vars('params');
    if( is_null($params['backto']) )
        $params['backto'] = ViewUtil::serializeBackto();
    $this->assign('params', $params);

    // withパラメータで指定されている要素をキーとする配列を取得。
    $with = array_fill_keys(preg_split('/\s*,\s*/', $this->get_template_vars('with')), true);
    $this->assign('with', $with);

    // リストを一旦取得。
    $list = $this->get_template_vars('list');

    // アバターURLを一覧の列に追加する。
    Common::embedThumbnailColumn($list);

    // "with" に "chara" が指定されているならキャラ情報を取得。
    if( isset($with['chara']) ) {
        $charaSvc = new Character_InfoService();
        foreach($list as &$row) {
          $row['character'] = $charaSvc->needAvatar($row['user_id']);
        }unset($row);
    }

    // 一覧をアサイン。
    $this->assign('list', $list);
{/php}


{if $list}

  {* リスト表示 *}
  {image_tag file='hr.gif'}<br />
  {foreach from=`$list` item="row"}

    {if ($carrier == 'iphone' || $carrier == 'android')}
        {fieldset color='brown' width='95%'}
          <legend>{$row.short_name}</legend>
          <div>
	        {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}

            {if $with.chara}
              {text id=`$row.character.name_id`} <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.character.level}</span><br />
              {text grade=`$row.character.grade_id`}
            {/if}

            <br clear="all" /><div style="clear:both"></div>

			<div style="text-align:right">
   　　　　　   <a href="{url_for module='User' action='HisPage' userId=`$row.user_id` _params=`$params`}" class="buttonlike next">プロフを見る</a>
            </div>

          </div>
        {/fieldset}<br />

    {else}

      {platform_thumbnail src=`$row.thumbnail_url` size='M' float='left'}

      <a href="{url_for module='User' action='HisPage' userId=`$row.user_id` _params=`$params`}">{$row.short_name}</a>

      {if $with.chara}
        <br />
        {text id=`$row.character.name_id`} <span style="color:{#statusNameColor#}">Lv</span><span style="color:{#statusValueColor#}">{$row.character.level}</span><br />
        {text grade=`$row.character.grade_id`}
      {/if}

      <br clear="all" /><div style="clear:both"></div>
      {image_tag file='hr.gif'}<br />
    {/if}
  {/foreach}

{else}
  <br />
  <div style="text-align:center">まだいません。</div>
  <br />
{/if}
