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
	 * ���󥹥ȥ饯�� 
	 * 
	 * @param Layout $layout Layout���󥹥��� 
	 * @param string $to �᡼�밸�襢�ɥ쥹 
	 * @param string $subject �᡼���̾ 
	 * @param string $from �᡼�뺹�пͥ��ɥ쥹 
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
	 * ��å�������᡼��˽񤯡ʥ᡼��������<br /> 
	 * 
	 * <note>�褷�Ƽ�ư�ǸƤ�ǤϤ����ʤ�</note> 
	 * 
	 * @param string �񤫤���å����� 
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