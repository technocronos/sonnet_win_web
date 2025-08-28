{include file='include/header.tpl'}

<h2>NFTtest</h2>




<h2>NFTリスト情報</h2>

<p>
  {if $nft}

    <table>
      <tr>
        <td colspan="5" style="text-align:right; border-style:none">{include file='include/pager.tpl' totalPages=`$list.totalPages`}</td>
      </tr>

      <tr>
        <th style="width:3em">tokenId</th>
        <th style="width:12em">タイプ</th>
<!--
        <th style="width:12em">キャラID</th>
        <th style="width:12em">武器</th>
        <th style="width:12em">鎧</th>
        <th style="width:12em">頭</th>
        <th style="width:12em">アクセ</th>
        <th style="width:12em">背景</th>
        <th style="width:12em">オーナー</th>
-->
        <th style="width:12em">url</th>
        <th style="width:12em">画像</th>
        <th style="width:12em">attributes</th>
        <th style="width:12em">description</th>
      </tr>

      {foreach from=`$nft` item=item}
        <tr>
          <td style="text-align:center">
            {$item.token_id}
          </td>
          <td>{$item.type}</td>
<!--
          <td>{$item.character_id}</td>
          <td>{$item.WPN}</td>
          <td>{$item.BOD}</td>
          <td>{$item.HED}</td>
          <td>{$item.ACS}</td>
          <td>{$item.BG}</td>
          <td>{$item.owner}</td>
-->
          <td><a href="?module=Api&action=NFT&tokenId={$item.token_id}">?module=Api&action=NFT&tokenId={$item.token_id}</a></td>
          <td><image src="{$item.metadata.image}" /></td>
          <td nowrap>
          {foreach from=`$item.metadata.attributes` item=attr}
              {$attr.trait_type} / {$attr.value}<br>
          {/foreach}
          </td>
          <td nowrap>{$item.metadata.description|smarty:nodefaults}</td>
        </tr>
      {/foreach}
    </table>

  {else}

    まだ入力されていません。

  {/if}<br><br>

</p>

{include file='include/footer.tpl'}
