<?php

/**
 * 事前登録を処理するアクション。
 */
class PreRegLogAction extends SmfBaseAction {

    protected function doExecute($params) {
        $tokuten_code = $_GET['code'];

Common::varLog("事前登録特典付与：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code);

        if($tokuten_code == ""){
            //特典コードが入力されていない
Common::varLog("事前登録特典付与エラー reason4：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code);
            return 4;
        }

        $svc = new Pre_Reg_LogService();

        //そのユーザーのログがすでにあるかどうか調べる
        $log_count = $svc->countUser($this->user_id);

        //ログが無い場合
        if($log_count == 0){
            //特典コードがリストにあるか調べる
            if(in_array($tokuten_code, Pre_Reg_LogService::$TOKUTENCODE)){
                if($svc->countCode($tokuten_code) == 0){
                    $record = array(
                        'user_id' => $this->user_id,
                        'code' => $tokuten_code,
                    );

                    $svc->insertRecord($record);

                    $userItemSvc = new User_ItemService();
                    $userItemSvc->gainItem($this->user_id, Gacha_MasterService::FREETICKET_ID, Pre_Reg_LogService::ITEMCOUNT);

Common::varLog("事前登録特典付与完了：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code);

                    //OK
                    return 1;
                }else{
                    //すでに他のユーザーが使っている。
Common::varLog("事前登録特典付与エラー reason5：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code);
                    return 5;
                }
            }else{
              //コードが無い
Common::varLog("事前登録特典付与エラー reason3：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code);
              return 3;
            }
        }else{
            //すでに登録済み
Common::varLog("事前登録特典付与エラー reason2：ユーザーID：" . $this->user_id . " 特典コード:" . $tokuten_code . " log_count=" . $log_count);
            return 2;
        }

    }
}
