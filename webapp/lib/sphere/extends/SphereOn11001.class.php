<?php

/**
 * チュートリアルフィールドの特殊処理を記述する
 */
class SphereOn11001 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * createStateをオーバーライド
     * 独自フラグを追加しておく。
     */
    protected function createState($roomName, $enterUnits, $reason) {

        parent::createState($roomName, $enterUnits, $reason);

        $this->state['x_ExplainStep'] = 0;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressAfterCommをオーバーライド
     */
    protected function progressAfterComm(&$leads) {

        // 主人公ユニットしかいないはずだけど、一応、チェック。
        if($this->getUnit()->getCode() == 'avatar') {

            switch($this->state['x_ExplainStep']) {

                // まだ初めての移動が行われていない場合...
                case 0:

                    // 移動した場合。
                    if( isset($this->state['command']['move']) ) {

                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11001_11001000_second")));

                        // 説明段階を次へ。
                        $this->state['x_ExplainStep']++;

                    // 移動していない場合。
                    }else {
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11001_11001000_second2")));
                    }

                    break;

                // 移動したあとの段階の場合。
                case 1:
                    $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_11001_11001000_find_tres")));

                    // 説明段階を次へ。
                    $this->state['x_ExplainStep']++;
            }
        }

        return parent::progressAfterComm($leads);
    }
}
