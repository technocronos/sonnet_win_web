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
 *
 *
 * @package    mojavi
 * @subpackage logging
 *
 * @author     Sean Kerr (skerr@mojavi.org)
 * @copyright  (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since      3.0.0
 * @version    $Id: MailAppender.class.php 65 2004-10-26 03:16:15Z seank $
 */
class MailAppender extends Appender
{

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+
    
	/** 
	 * コンストラクタ 
	 * 
	 * @param Layout $layout Layoutインスタンス 
	 * @param string $to メール宛先アドレス 
	 * @param string $subject メール件名 
	 * @param string $from メール差出人アドレス 
	 * @access public 
	 * @since  2.0 
	 */ 
	public function MailAppender($layout, $to, $subject = "Message from Mojavi", $from = null) { 
		parent::Appender($layout); 
		$this->to = $to; 
		$this->subject = $subject; 
		$this->from = $from; 
	} 

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+
    
	/** 
	 * メッセージをメールに書く（メールを送る）<br /> 
	 * 
	 * <note>決して手動で呼んではいけない</note> 
	 * 
	 * @param string 書かれるメッセージ 
	 * @access public 
	 * @since  2.0 
	 */ 
	public function write($message) { 
		$header = "X-Mailer: MailAppender\r\n"; 
		if ($this->from) { $header .= "From: ".$this->from; } 
		if (!mb_send_mail($this->to, $this->subject, $message, $header)) { 
			throw new LoggingException("Failed to send mail to ".$this->to); 
		} 
	} 

}

?>