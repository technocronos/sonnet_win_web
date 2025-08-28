<?php
/**
 * ApplicatioException is thrown when an error occurs in a application.
 * 
 * @access public
 * @package mojavi
 * @subpackage exception
 * @author Tsutomu Wakuda <wakuda@withit.co.jp>
 * @sourcefile
 *
 */
class ApplicationException extends MojaviException
{

    /**
     * コンストラクタ
     * @access public
     * @param String $message エラーメッセージ
     * @param String $code エラーコード
     * 
     */
    public function __construct ($message = null, $code = 0)
    {

        parent::__construct($message, $code);

        $this->setName('ApplicationException');

    }
}
?>