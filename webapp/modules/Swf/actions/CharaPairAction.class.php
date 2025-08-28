<?php

class CharaPairAction extends SwfBaseAction {

    protected function doExecute() {

        //イメージの差し替え
        $this->replaceImages[3] = CharaImageUtil::getImageFromSpec($_GET['chara1'], 'swf');
        $this->replaceImages[5] = CharaImageUtil::getImageFromSpec($_GET['chara2'], 'swf');
    }
}
