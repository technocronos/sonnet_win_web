{*
    ユーザに設定されているアイテム効果の期限を表示するテンプレート
*}
{php}

    // アイテム効果の期限を取得。
    $avatarId = Service::create('Character_Info')->needAvatarId( $this->get_template_vars('userId') );
    $this->assign('effectExpires', Service::create('Character_Effect')->getEffectExpires($avatarId));
{/php}


{foreach from=`$effectExpires` item="effect"}
  <span style="color:{#termColor#}">{$effect.effect_name}</span>あと<span style="color:{#statusValueColor#}">{$effect.seconds|interval}</span><br />
{/foreach}
