<?php

class MonsterAction extends SwfBaseAction {

    protected function doExecute() {

        // 指定されたモンスターの情報を取得。
        $monster = Service::create('Monster_Master')->needRecord($_GET['id']);

        // 置き換え文字列をセット
        $this->replaceStrings['name'] = Text_LogService::get($monster['name_id']);
        $this->replaceStrings['text'] = $monster['flavor_text'];
        $this->replaceStrings['rare'] = $monster['rare_level'];

        //イメージの差し替え
        $character = Service::create('Character_Info')->needExRecord($_GET['id']);
        $this->replaceImages[1] = CharaImageUtil::getImageFromChara($character, 'swf');
    }
}
