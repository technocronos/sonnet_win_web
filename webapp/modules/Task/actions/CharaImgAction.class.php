<?php

/**
 * /img/chara/ 以下へのリクエストを mod_rewrite によって受け取るアクション。
 */
class CharaImgAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // "/img/chara/" よりも下のパスから、画像の情報を取得。
        if(0 == preg_match('/^(.*?)\.(\w+)\.gif$/i', $_GET['file'], $matches))
            throw new MojaviException('画像情報がおかしい');

        // CharaImageUtil::getSpec から取得した情報と、サイズ情報を得る。
        $spec = $matches[1];
        $size = $matches[2];

        
        if(strpos($size,'_') !== false){
            $array = explode("_",$size);
            $size = $array[0];
            $bg = $array[1];
        }

        // 画像の物理パスを得る。
        if($size == 'full'){
          $type = 'web';
          $imgtype = 'normal';
        }else if($size == 'large'){
          $type = 'web';
          $imgtype = 'large';
        }else{
          $type = 'nail';
          $imgtype = 'normal';
        }

        $imagePath = CharaImageUtil::getImageFromSpec($spec, $type, $imgtype, $bg);

        // Content-Type を gif 用に調整して出力。
        header('Content-Type: image/gif');
        readfile($imagePath);

        return View::NONE;
    }
}
