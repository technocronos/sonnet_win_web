<?php

/**
 * パラメータで指定されたイメージのタグを返す。
 * retina対応してあるのでwidth、heightは指定しないこと。指定した場合はそのままの値が使われる。
 */
function smarty_function_img($params, $smarty) {

    //srcを絶対パスに直す
    $src = APP_WEB_ROOT . $params["src"];

    list($width, $height, $type, $attr) = getimagesize(MO_HTDOCS . "/" . $params["src"]);

    $cls = $params["class"];
    $id = $params["id"];

    $device_ratio = 0.48;

    $styleWH = "width:" . ($width * $device_ratio) . "px;" . "height:" . ($height * $device_ratio) . "px;";

    //Styleを処理する
    if($params["style"]){
        $stylechange = true;

        //Styleを分解して解析する
        $arr = explode(";", $params["style"]);
        foreach($arr as $key=>$val){
            $arr2 = explode(":" , $val);
            //attrにwidthがある場合はそのまま
            if($arr2[0] == "width"){
                $stylechange = false;
                break;
            }
        }

        if($stylechange)
            $style = $params["style"] . ";" . $styleWH;
        else
            $style = $params["style"];
    }else{
        $style = $styleWH;
    }

    // 出力。
    return sprintf('<img id="%s" class="%s" src="%s" style="%s" />', $id, $cls, $src, $style);

}
