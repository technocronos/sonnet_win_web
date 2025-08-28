{*
    昇格／降格を表示するテンプレート

    パラメータ)
        beforeId    変化前の階級
        afterId     変化後の階級
*}
{php}

    $before = $this->get_template_vars('beforeId');
    $after = $this->get_template_vars('afterId');

    // 昇格している場合...
    if($before < $after) {

        // 変化後の階級情報を取得。
        $grade = Service::create('Grade_Master')->needRecord($after);

        // 必殺技が設定されているならその情報を取得。
        if($grade['dtech_id'])
            $this->assign('dtech', Service::create('Dtech_Master')->needRecord($grade['dtech_id']));
    }

{/php}


{if $beforeId != $afterId}

  <div style="text-align:center">
    {if $beforeId < $afterId}
      {image_tag file='syoukaku.gif'}<br />
      <span style="color:{#termColor#}">{text grade=`$result.character.grade_id`}</span>に昇格しました<br />
      {if $dtech}<span style="color:{#termColor#}">{$dtech.dtech_name}</span>が使えるようになりました{/if}
    {else}
      {image_tag file='koukaku.gif'}<br />
      <span style="color:{#termColor#}">{text grade=`$result.character.grade_id`}</span>に降格しました
    {/if}
  </div>
{/if}
