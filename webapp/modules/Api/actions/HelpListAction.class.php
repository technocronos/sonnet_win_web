<?php

/**
 * ---------------------------------------------------------------------------------
 * ヘルプリストを送信する
 * @param id　
 * ---------------------------------------------------------------------------------
 */
class HelpListAction extends SmfBaseAction {

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
        foreach($helps as &$help) {

            if($help["help_id"] == "other-shoutai" && !FRIEND_INVITE_OPEN)
                continue;

            if( !array_key_exists($help['group_id'], $helpTree) )
                $helpTree[ $help['group_id'] ] = array();
            $help["help_title"] = AppUtil::getText("help_master_help_title_" . $help["help_id"]);
            $help["help_body"] = AppUtil::getText("help_master_help_body_" . $help["help_id"]);

            $helpTree[ $help['group_id'] ][] = $help;

        }

        $array['helpTree'] = $helpTree;

        // 項目グループの一覧をビューでも使えるようにする。
        foreach(Help_MasterService::$GROUPS as $key=>$value){
            $array['groups'][$key] = AppUtil::getText($value);
        }

        $array['result'] = 'ok';

        return $array;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたヘルプの表示準備を行う。
     */
    private function executeContent() {

        // 指定されている項目を取得。
        $help = Service::create('Help_Master')->needRecord($_GET['id']);

        $help["help_title"] = AppUtil::getText("help_master_help_title_" . $help["help_id"]);

        $help["help_body"] = array();
        $help["help_body"][] = AppUtil::getText("help_master_help_body_" . $help["help_id"]);

        for ($i = 1; $i < 10; $i++) {
            $str = AppUtil::getText("help_master_help_body_" . $help["help_id"] . "_" . $i);
            if($str != null){
                $help["help_body"][] = $str;
            }
        }

        $array['help'] = $help;

        return $array;
    }

}
