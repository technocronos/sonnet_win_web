{*
    経験値取得によるレベルアップを表示するテンプレート

    パラメータ)
        before    経験値取得前のキャラクター情報。
        after     経験値取得後のキャラクター情報。
*}
{php}


    $before = $this->get_template_vars('before');
    $after = $this->get_template_vars('after');

    // アバターキャラがレベルアップしている場合。
    if($before['entry'] == 'AVT'  &&  $before['level'] < $after['level']) {

        // 仲間上限が上がっていないかチェック。
        $levelSvc = new Level_MasterService();
        $beforeLevel = $levelSvc->needRecord($before['race'], $before['level']);
        $afterLevel = $levelSvc->needRecord($after['race'], $after['level']);

        if($beforeLevel['member_limit'] < $afterLevel['member_limit'])
            $this->assign('memberLimit', $afterLevel['member_limit']);

        // 新しいヘルプ項目がリリースされていないかチェック。
        $helps = Service::create('Help_Master')->checkRelease($before['level'], $after['level']);
        $this->assign('helps', $helps);
    }

{/php}


{if $before.level < $after.level}
  <div style="text-align:center">

    {image_tag file='levelup.gif'}<br />
    ﾚﾍﾞﾙ<span style="color:{#statusValueColor#}">{$after.level}</span>になりました<br />

    {if $after.attack1-$before.attack1 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_att1.gif'}攻撃(炎)</span>が<span style="color:{#statusValueColor#}">{$after.attack1-$before.attack1}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.attack2-$before.attack2 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_att2.gif'}攻撃(水)</span>が<span style="color:{#statusValueColor#}">{$after.attack2-$before.attack2}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.attack3-$before.attack3 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_att3.gif'}攻撃(雷)</span>が<span style="color:{#statusValueColor#}">{$after.attack3-$before.attack3}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.defence1-$before.defence1 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_def1.gif'}防御(炎)</span>が<span style="color:{#statusValueColor#}">{$after.defence1-$before.defence1}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.defence2-$before.defence2 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_def2.gif'}防御(水)</span>が<span style="color:{#statusValueColor#}">{$after.defence2-$before.defence2}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.defence3-$before.defence3 > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_def3.gif'}防御(雷)</span>が<span style="color:{#statusValueColor#}">{$after.defence3-$before.defence3}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.speed-$before.speed > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_speed.gif'}ｽﾋﾟｰﾄﾞ</span>が<span style="color:{#statusValueColor#}">{$after.speed-$before.speed}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.hp_max-$before.hp_max > 0}<span style="color:{#statusNameColor#}">{image_tag file='picon_hp.gif'}最大HP</span>が<span style="color:{#statusValueColor#}">{$after.hp_max-$before.hp_max}</span>ｱｯﾌﾟ<br />{/if}
    {if $after.param_seed-$before.param_seed > 0}<span style="color:{#statusNameColor#}">ｽﾃｰﾀｽpt</span><span style="color:{#statusValueColor#}">{$after.param_seed-$before.param_seed}</span>ｹﾞｯﾄ <a href="{url_for action='ParamUp' _backto=true}" class="buttonlike next">振り分け⇒</a><br />{/if}
    {if $memberLimit}仲間上限が<span style="color:{#statusValueColor#}">{$memberLimit}</span>にｱｯﾌﾟ<br />{/if}

    {foreach from=`$helps` item='help'}
      ﾍﾙﾌﾟ<a href="{url_for action='Help' id=`$help.help_id` _backto=true}" class="buttonlike label">{$help.help_title}</a>ﾘﾘｰｽ<br />
    {/foreach}
  </div>
  <br />
{/if}
