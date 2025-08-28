<?php
/**
 * �Q�\�Ă�̃T���h�{�b�N�X�ɓo�^����item_buy_url(���ϊm�莞�R�[���o�b�NURL)�B
 *
 * ���̃v���b�g�t�H�[���Ȃ猈��API�Ăяo�����ɓ��I�ɐ�������URL�ɂȂ�̂����A�Q�\�Ă�ł̓T���h�{�b�N�X�ɌŒ�I�ɓo�^���邱�Ƃ����o���Ȃ��B
 * {item_id} �̕����ɖ{���̊m�莞�R�[���o�b�NURL����������ł���̂ŁA�����łȂ�Ƃ��[���I�ɃR�[������B
 */

    require_once("../webapp/config.php");
    require_once(MOJAVI_FILE);

    // main() �Ŏ傽�鏈���B���s������ "geso_paysettle.log" �Ƃ������O�ɋL�^����B
    try {
        $result = main();
        $message = array('ok'=>'no problem!!', 'ce'=>'crypto error')[$result];
    }
    catch(Exception $e) {

        $log = sprintf('%s %s:%d %s', get_class($e), $e->getFile(), $e->getLine(), $e->getMessage()) . "\n"
             . $e->getTraceAsString() . "\n"
             . 'SERVER: ' . print_r($_SERVER, true)
             . 'POST: ' . print_r($_POST, true);

        file_put_contents(MO_LOG_DIR.'/geso_paysettle.log', $log, FILE_APPEND);

        $result = 'pe';
        $message = $log;
    }

    //----------------------------------------------------------------------------------------------------------
    function main() {

        // �f�o�b�O�p�Ɏc���Ă����B���N�G�X�g�������O�B
        # varLog("geso_paysettle");
        # varLog($_SERVER);
        # varLog($_POST);

        // ���N�G�X�g�͂���Ȋ���
        # $_SERVER=> array(50) {
        #   ["HOSTNAME"]=> string(21) "localhost.localdomain"
        #   ["TERM"]=> string(5) "xterm"
        #   ["SHELL"]=> string(9) "/bin/bash"
        #   ...
        #   ["HTTP_CONNECTION"]=> string(10) "Keep-Alive"
        #   ["HTTP_HOST"]=> string(30) "test.geso.sonnet.crns-game.net"
        #   ["CONTENT_TYPE"]=> string(48) "application/x-www-form-urlencoded; charset=UTF-8"
        #   ["HTTP_CONTENT_LENGTH"]=> string(3) "235"
        #   ["REMOTE_PORT"]=> string(5) "54442"
        #   ["REMOTE_ADDR"]=> string(14) "163.44.190.254"
        #   ["SERVER_NAME"]=> string(30) "test.geso.sonnet.crns-game.net"
        #   ["SERVER_ADDR"]=> string(15) "111.171.203.167"
        #   ["SERVER_PORT"]=> string(2) "80"
        #   ["GATEWAY_INTERFACE"]=> string(7) "CGI/1.1"
        #   ["SERVER_SOFTWARE"]=> string(15) "lighttpd/1.4.43"
        #   ["SERVER_PROTOCOL"]=> string(8) "HTTP/1.1"
        #   ["REQUEST_METHOD"]=> string(4) "POST"
        #   ["DOCUMENT_ROOT"]=> string(47) "/srv/www/test.geso.sonnet.crns-game.net/htdocs/"
        #   ["SCRIPT_FILENAME"]=> string(65) "/srv/www/test.geso.sonnet.crns-game.net/htdocs/geso_paysettle.php"
        #   ["SCRIPT_NAME"]=> string(19) "/geso_paysettle.php"
        #   ["REDIRECT_STATUS"]=> string(3) "200"
        #   ["REQUEST_URI"]=> string(19) "/geso_paysettle.php"
        #   ["QUERY_STRING"]=> string(0) ""
        #   ["CONTENT_LENGTH"]=> string(3) "235"
        #   ["FCGI_ROLE"]=> string(9) "RESPONDER"
        #   ["PHP_SELF"]=> string(19) "/geso_paysettle.php"
        #   ["REQUEST_TIME_FLOAT"]=> float(1526621764.8946)
        #   ["REQUEST_TIME"]=> int(1526621764)
        # }
        # $_POST=> array(8) {
        #   ["order_id"]=> string(36) "5a3ea4ec-790f-4c97-a3bc-6c9ad7f3de27"
        #   ["owner_id"]=> string(8) "12257739"
        #   ["app_id"]=> string(4) "8653"
        #   ["item_id"]=> string(54) "dataId-8fxa2xxMA7xVL5OrLgvm3mxyLoxLCAmH_action-BuyItem"
        #   ["item_price"]=> string(3) "200"
        #   ["amount"]=> string(1) "2"
        #   ["time"]=> string(14) "20180518143604"
        #   ["signature"]=> string(30) "hJk0RrOX/Ttm/o2DBp8o/TTTbUI=
        # "
        # }

        // �������؁BNG�Ȃ烊�^�[���B
        if( !validateSignature() )  return 'ce';

        // {item_id} �̕����ɖ{���̌��ϑ҂���URL�Ɋւ�����𖄂ߍ���ł���̂� $params �ɕ�������B
        // �ڍׂ� GesoApi::readyPayment() �̎������Q�ƁB
        $item = str_replace(['-','_'], ['=','&'], $_POST['item_id']);
        parse_str($item, $params);

        // ���ߍ��܂�Ă���p�����[�^�ɑΉ�����A�N�V�������R�[���o����悤�ɂ���...
        $_GET = array_merge($_GET, $params);
        $_GET['module'] = 'Event';
        # varLog($_GET);

        // �Ȃ�Ƃ��[���I�ɃR�[������B
        $class = $_GET['action'] . 'Action';
        $src = MO_BASE_DIR . sprintf('/webapp/modules/%s/actions/%s.class.php', $_GET['module'], $class);

        require_once($src);
        $action = new $class();

        ob_start();
        $action->execute();
        # $output = ob_get_contents();
        # varLog($output);
        ob_end_clean();

        return 'ok';
    }

    //------------------------------------------------------------------------------------------------------
    function validateSignature() {

        // 1���ԈȑO�̏ꍇ�G���[�B
        if($_POST['time'] + 60*60 <= time())  return false;

        // �����̂��ƂɂȂ镶������쐬�B
        $params[] = $_POST['order_id'];
        $params[] = $_POST['owner_id'];
        $params[] = $_POST['app_id'];
        $params[] = $_POST['item_id'];
        $params[] = $_POST['amount'];
        $params[] = $_POST['time'];
        $base = implode(':', $params);

        // ���������B
        $signature = hash_hmac('sha1', $base, CONSUMER_SECRET, true);

        // ��r�B
        return $signature == base64_decode($_POST['signature']);
    }

    //------------------------------------------------------------------------------------------------------
    function varLog($var) {

        ob_start();
        var_dump($var);
        $output = ob_get_contents();
        ob_end_clean();

        file_put_contents(MO_LOG_DIR.'/debug.log', $output, FILE_APPEND);
    }

?>
{"appResponseCode":"<?php echo $result ?>","appMessage":"<?php echo $message ?>"}
