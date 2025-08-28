<?php

/**
 * ---------------------------------------------------------------------------------
 * ヘルプリストを送信する
 * @param id　
 * ---------------------------------------------------------------------------------
 */
class HelpListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        if($_GET['id'])
            return $this->executeContent();

        // 現在のユーザのレベルを取得。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $array['avatar'] = $avatar;

        // レベルから、アクセスできるヘルプを取得。
        $helps = Service::create('Help_Master')->getList($avatar['level']);

        // キー: group_id
        // 値:   そのグループに属するヘルプの序数配列
        // …になるような配列を作成する。
        $helpTree = array();
        foreach($helps as $help) {

            if( !array_key_exists($help['group_id'], $helpTree) )
                $helpTree[ $help['group_id'] ] = array();

            if(PLATFORM_TYPE == "nati"){
                //ネイティブの場合は招待は表示しない
                if($help["help_id"] == "other-shoutai")
                    continue;
            }else{
                //ネイティブ以外の場合は端末引き継ぎ、事前登録は表示しない
                if($help["help_id"] == "other-inherit" || $help["help_id"] == "other-jizentouroku")
                    continue;
            }

            $helpTree[ $help['group_id'] ][] = $help;
        }

        $array['helpTree'] = $helpTree;

        // 項目グループの一覧をビューでも使えるようにする。
        $array['groups'] = Help_MasterService::$GROUPS;

        return $array;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたヘルプの表示準備を行う。
     */
    private function executeContent() {

        // 指定されている項目を取得。
        $help = Service::create('Help_Master')->needRecord($_GET['id']);
        $array['help'] = $help;

        return $array;
    }

}
