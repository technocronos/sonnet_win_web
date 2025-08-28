<?php

/**
 * ステータス画面の情報を取得する
 */
class StatusApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // ステータス画面、装備の情報をセット
        $charaSvc = new Character_InfoService();

        $avatar = $charaSvc->needAvatar($this->user_id, true);
        $gradeinfo = Service::create('Grade_Master')->needRecord($avatar['grade_id']);

        // アバター画像情報を取得。
        $spec = CharaImageUtil::getSpec($avatar);
        $avatar['imageUrl'] = sprintf('%s.%s.gif', $spec, 'full');

        $array["chara"] = $avatar;
        $array["grade"] = $gradeinfo;

        // 指定されているキャラクタを取得。
        $chara = Service::create('Character_Info')->needExRecord($avatar['character_id']);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);

        foreach($mounts as $mount){
            if($chara['equip'][$mount['mount_id']]['set_id']){
                //セット情報を追加する
                $set_data = Service::create('Set_Master')->getRecord($chara['equip'][$mount['mount_id']]['set_id']);

                $chara['equip'][$mount['mount_id']]['mount_id'] = $mount["mount_id"];
                $chara['equip'][$mount['mount_id']]['mount_name'] = $mount["mount_name"];

                $chara['equip'][$mount['mount_id']]['set_id'] = $set_data["set_id"];
                $chara['equip'][$mount['mount_id']]['rear_id'] = $set_data['rear_id'];
                $chara['equip'][$mount['mount_id']]['set_name'] = $set_data['set_name'];
                $chara['equip'][$mount['mount_id']]['set_text'] = $set_data['set_text'];
            }
        }

        //自分の現在の装備一覧
        $array["PLAEQP"] = $chara['equip'];

        $targetId = Service::create('Character_Info')->needAvatarId($this->user_id);

        //特殊効果
        $effectExpires = Service::create('Character_Effect')->getEffectExpires($targetId);

        //初期化        
        $array['effectExpires'][Character_EffectService::TYPE_EXP_INCREASE] = "";
        $array['effectExpires'][Character_EffectService::TYPE_HP_RECOVER] = "";
        $array['effectExpires'][Character_EffectService::TYPE_ATTRACT] = "";
        $array['effectExpires'][Character_EffectService::TYPE_DTECH_POWUP] = "";

        $array['effectExpires'] = $effectExpires;

        $flagSvc = new Flag_LogService();
        $array['paramupItemStatus']["param1"] = (int)$flagSvc->getValue(Flag_LogService::PARAM_UP, $targetId, 1201);
        $array['paramupItemStatus']["param2"] = (int)$flagSvc->getValue(Flag_LogService::PARAM_UP, $targetId, 1202);
        $array['paramupItemStatus']["param3"] = (int)$flagSvc->getValue(Flag_LogService::PARAM_UP, $targetId, 1203);

        $array['result'] = 'ok';

        return $array;

    }
}
