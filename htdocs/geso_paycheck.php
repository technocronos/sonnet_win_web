<?php
/**
 * �Q�\�Ă�̃T���h�{�b�N�X�ɓo�^����item_check_url(���ϑO�R�[���o�b�NURL)�B
 * �݌ɂ̊m�F�ȂǂɎg���̂����c����Ȃ��̂͂Ȃ��̂ŌŒ�I�ȃ��X�|���X��OK�B.html �ɂ��Ă��ǂ����炢���c
 */

    error_reporting(E_ALL ^ E_NOTICE);
    require_once("../webapp/config.php");


    # varLog("geso_paycheck");
    # varLog($_SERVER);
    # varLog($_POST);


    function varLog($var) {

        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();

        file_put_contents(MO_LOG_DIR.'/debug.log', $output, FILE_APPEND);
    }

?>
{"appResponseCode":"ok", "appMessage":"no problem!!"}
