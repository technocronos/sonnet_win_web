<?php

/**
 * 寸劇型のクエストに対する操作を提供するクラス。
 * 直接 new するのではなく、QuestCommon::factory でインスタンスを得て使用する。
 */
class DramaQuest extends QuestCommon {

    const FLAGON_KEY = '\avt0<QfcW9Nl(9Pc-CdPJdRIFu/7x';


    //-----------------------------------------------------------------------------------------------------
    /**
     * 再生する寸劇のIDを取得する。
     *
     * @return int      寸劇ID
     */
    public function getDramaId() {

        return $this->quest['content_id'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プログラムによるフローの変更を行う。
     * 寸劇が再生される直前に呼ばれる。
     *
     * @param reference     drama_master.flow の値を格納した変数への参照。
     *                      変更する必要がある場合はこの変数を直接操作する。
     */
    public function changeFlow(&$flow) {

        // 基底では特に変更しない。
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド
     * 第2引数はコード付きの "!URLGO" コマンドのコード値。
     * コード値に、"flag-nnn-xxx" (nnnは任意の数の数字、xxxは16進数文字) が指定されている場合は、
     * フラググループ:6 で、"(クエストID)nnn" のIDのフラグがONになる。
     * xxxには、バリデーションコードを指定する。
     */
    public function endQuest($success, $code) {

        // 行動ptを減じる。
        Service::create('User_Info')->plusValue($this->userId, array(
            'action_pt' => -1 * $this->quest['consume_pt'],
        ));

        // "!URLGO" コマンドのコード値で、汎用フラグオンが指定されている場合。
        if( preg_match('/^flag-(\d+)-(\w*)$/', $code, $matches) ) {

            // フラグIDを取得。
            $flagId = $this->quest['quest_id'] . $matches[1];

            // バリデーションコードを検証する。
            if( $matches[2] != sha1($flagId.self::FLAGON_KEY) )
                throw new MojaviException('寸劇用フラグオンのバリデーションコードが不正');

            // 指定されたフラグをONにする。
            Service::create('Flag_Log')->flagOn(Flag_LogService::FLAG, $this->userId, $flagId);
        }

        // あとは基底に任せる。
        parent::endQuest($success, $code);
    }
}
