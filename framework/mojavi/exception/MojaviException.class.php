<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Mojavi package.                                  |
// | Copyright (c) 2003, 2004 Sean Kerr.                                       |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.mojavi.org.                             |
// +---------------------------------------------------------------------------+

/**
 * MojaviException is the base class for all Mojavi related exceptions and
 * provides an additional method for printing up a detailed view of an
 * exception.
 *
 * @package    mojavi
 * @subpackage exception
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Id: MojaviException.class.php 707 2004-12-28 17:48:28Z seank $
 */
class MojaviException extends Exception
{

    // +-----------------------------------------------------------------------+
    // | PRIVATE VARIABLES                                                     |
    // +-----------------------------------------------------------------------+

    private
        $name = null;

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+

    /**
     * Class constructor.
     *
     * @param string The error message.
     * @param int    The error code.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function __construct ($message = null, $code = 0)
    {

        parent::__construct($message, $code);

        $this->setName('MojaviException');

    }

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    /**
     * Retrieve the name of this exception.
     *
     * @return string This exception's name.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function getName ()
    {

        return $this->name;

    }

    // -------------------------------------------------------------------------

    /**
     * Print the stack trace for this exception.
     *
     * @param string The format you wish to use for printing. Options
     *               include:
     *               - html
     *               - plain
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    public function printStackTrace ($format = 'html')
    {
        // オリジナルから完全に書き直し。

        // ログに記録。
        $this->logError();

        // display_errorsの設定にしたがって、エラー内容をどう表示するかを決める。
        if( ini_get('display_errors') ) {

            $output = $this->getMessage() . "\n" . $this->getTraceAsString();

            if(!headers_sent())
                header("Content-Type: text/plain");

        }else {

            $output =
                  '<html><body><br />'
                . "申しわけありません。エラーが発生しました。<br />\n"
                . "エラーコード: " . trim(`hostname`) . '-' . date('mdHis')
                . '<br /><br /></body></html>';

            if(!headers_sent())
                header("Content-Type: text/html");
        }

        if(preg_match("/^DoCoMo/", $_SERVER['HTTP_USER_AGENT']) || preg_match("/^UP.Browser|^KDDI/", $_SERVER['HTTP_USER_AGENT']))
            $output = mb_convert_encoding($output, 'SJIS-WIN', 'UTF-8');

        echo $output;

        exit;
    }

    /**
     * この例外で表されているエラーをログファイルに記述する。
     */
    public function logError() {

        global $logger;

        // ロガーがちゃんと作成されているならログに記録。
        if($logger) {

            $log = $this->getMessage() . "\n"
                 . $this->getTraceAsString() . "\n"
                 . 'SERVER: ' . print_r($_SERVER, true)
                 . 'POST: ' . print_r($_POST, true);

            $logger->WARNING($log);
        }
    }


    // -------------------------------------------------------------------------

    /**
     * Set the name of this exception.
     *
     * @param string An exception name.
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  3.0.0
     */
    protected function setName ($name)
    {

        $this->name = $name;

    }

}

?>