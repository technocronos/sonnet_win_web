<?php

/**
 * プラットフォーム記事の投稿を行うためのHTMLを返す。
 *
 * パラメータ)
 *      body    デフォルトの本文
 *      return  ユーザが投稿を行った後に返ってくるURL
 *
 * 使用例)
 *     {article_form return='http://some.domain/xxx' body='デフォルトの本文'}投稿ボタンの前のHTML %button% 投稿ボタンの後のHTML{/article_form}
 */
function smarty_block_article_form($params, $content, &$smarty, &$repeat) {

    // 開始タグは無視。
    if($repeat)
        return;

    // 投稿のための <form> タグの開始部分を取得。
    $formHead = PlatformApi::getArticleFormHead($params['return'], $params['body']);

    // ボタンのキャプションを取得。
    $caption = (PLATFORM_TYPE == 'mixi') ? 'つぶやく' : '書く';
    $button = sprintf('<input type="submit" value="%s" />', htmlspecialchars($caption, ENT_QUOTES));

    // ブロックのコンテンツの "%button%" の部分を送信ボタンHTMLに置き換える。
    $content = str_replace('%button%', $button, $content);

    // <form> タグを作成して返す。
    return $formHead . $content . '</form>';
}
