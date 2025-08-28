<?php

/**
 * 師匠の本棚あさり
 */
class Quest11004 extends DramaQuest {

    // フラグの値。
    const FIND_EROBON = 110040001;
    const GET_SAVINGS = 110040002;

    // ヘソクリの額
    const SAVINGS = 150;


    //-----------------------------------------------------------------------------------------------------
    /**
     * changeFlow() をオーバーライド
     */
    public function changeFlow(&$flow) {

        $flagSvc = new Flag_LogService();

        // エロ本を見つけているか、すでにヘソクリを手に入れているかを取得。
        $findErobon = $flagSvc->getValue(Flag_LogService::FLAG, $this->userId, self::FIND_EROBON);
        $getSavings = $flagSvc->getValue(Flag_LogService::FLAG, $this->userId, self::GET_SAVINGS);

        // ヘソクリ発見後のフローをエロ本を見つけているかどうかで切り替える。
        $flow = str_replace('%handle_savings%', $findErobon ? 'bad_girl' : 'good_girl', $flow);

        // ヘソクリ取得メッセージを、すでにヘソクリを入手しているかどうかで切り替える。
        $flow = str_replace(
            '%savings_result%',
            $getSavings ? AppUtil::getText("sphere_text_get_gold_already") : sprintf(AppUtil::getText("sphere_text_get_gold"), self::SAVINGS),
            $flow
        );

        // あとは基底に任せる。
        return parent::changeFlow($flow);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuest() をオーバーライド
     */
    public function endQuest($success, $code) {

        $flagSvc = new Flag_LogService();

        // エロ本発見した場合はフラグを立てる。
        if($code == 'find_erobon')
            $flagSvc->flagOn(Flag_LogService::FLAG, $this->userId, self::FIND_EROBON);

        // ヘソクリ取得時は...
        if($code == 'get_savings') {

            $flag = $flagSvc->getValue(Flag_LogService::FLAG, $this->userId, self::GET_SAVINGS);

            // まだ取得していない場合のみ処理する。
            if(!$flag) {

                // フラグをONに。
                $flagSvc->flagOn(Flag_LogService::FLAG, $this->userId, self::GET_SAVINGS);

                // ユーザにお金を付加。
                Service::create('User_Info')->plusValue($this->userId, array(
                    'gold' => self::SAVINGS
                ));
            }
        }

        // あとは基底に任せる。
        parent::endQuest($success, $code);
    }
}
