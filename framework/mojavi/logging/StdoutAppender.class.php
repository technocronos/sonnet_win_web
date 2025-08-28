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
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     1.0.0
 * @version   $Id: StdoutAppender.class.php 65 2004-10-26 03:16:15Z seank $
 */
class StdoutAppender extends Appender
{

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+

	/**
	 * Create a new FileAppender instance.
	 *
	 * @param Layout A Layout instance.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function StdoutAppender ($layout)
	{

		parent::Appender($layout);

	}
    
    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

	/**
	 * Write a message directly to the requesting client.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This should never be called manually.
	 * </note>
	 *
	 * @param string The message to be written.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function write (&$message)
	{

		echo $message;

	}
    
}

?>