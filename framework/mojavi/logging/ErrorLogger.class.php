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
 * $Id: ErrorLogger.class.php 65 2004-10-26 03:16:15Z seank $
 *
 * @package    mojavi
 * @subpackage logging
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Rev$
 */
class ErrorLogger extends Logger
{

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+
	/**
	 * Create a new ErrorLogger instance.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function ErrorLogger ()
	{

		parent::Logger();

	}

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+
	/**
	 * Log a message with a debug priority.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This has a priority level of 1000.
	 * </note>
	 *
	 * @param string An error message.
	 * @param string The class where message was logged.
	 * @param string The function where message was logged.
	 * @param string The file where message was logged.
	 * @param int	The line where message was logged.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function debug ($message, $class = null, $function = null, $file = null,
							$line = null)
	{

		$message = new Message(array('m' => $message,
									  'c' => $class,
									  'F' => $function,
									  'f' => $file,
									  'l' => $line,
									  'N' => 'DEBUG',
									  'p' => Logger::DEBUG));

		$this->log($message);

	}

	/**
	 * Log a message with an error priority.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This has a priority level of 3000.
	 * </note>
	 *
	 * @param string An error message.
	 * @param string The class where message was logged.
	 * @param string The function where message was logged.
	 * @param string The file where message was logged.
	 * @param int	The line where message was logged.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function error ($message, $class = null, $function = null, $file = null,
							$line = null)
	{

		$message = new Message(array('m' => $message,
									  'c' => $class,
									  'F' => $function,
									  'f' => $file,
									  'l' => $line,
									  'N' => 'ERROR',
									  'p' => Logger::ERROR));

		$this->log($message);

	}

	/**
	 * Log a message with a fatal priority.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This has a priority level of 5000.
	 * </note>
	 *
	 * @param string An error message.
	 * @param string The class where message was logged.
	 * @param string The function where message was logged.
	 * @param string The file where message was logged.
	 * @param int	The line where message was logged.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function fatal ($message, $class = null, $function = null, $file = null,
							$line = null)
	{

		$message = new Message(array('m' => $message,
									  'c' => $class,
									  'F' => $function,
									  'f' => $file,
									  'l' => $line,
									  'N' => 'FATAL',
									  'p' => Logger::FATAL));

		$this->log($message);

	}

	/**
	 * Log a message with a info priority.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This has a priority level of 2000.
	 * </note>
	 *
	 * @param string An error message.
	 * @param string The class where message was logged.
	 * @param string The function where message was logged.
	 * @param string The file where message was logged.
	 * @param int	The line where message was logged.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function info ($message, $class = null, $function = null, $file = null,
						   $line = null)
	{

		$message = new Message(array('m' => $message,
									  'c' => $class,
									  'F' => $function,
									  'f' => $file,
									  'l' => $line,
									  'N' => 'INFO',
									  'p' => Logger::INFO));

		$this->log($message);

	}

	/**
	 * Log an error handled by PHP.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 Do not call this method directly. Call the standard PHP function
	 *	 <i>trigger_error()</i>.
	 * </note>
	 *
	 * @param int	A priority level.
	 * @param string An error message.
	 * @param string The file where the error occured.
	 * @param int	The line where the error occured.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function standard ($level, $message, $file, $line)
	{

		// don't want to print supressed errors
		if (error_reporting() > 0)
		{

			switch ($level)
			{

				case E_USER_NOTICE:

					$this->info($message, null, null, $file, $line);
					break;

				case E_USER_ERROR:

					$this->warning($message, null, null, $file, $line);
					break;

				case E_USER_WARNING:
				default:

					$this->fatal($message, null, null, $file, $line);

			}

		}

	}

	/**
	 * Log a message with a warning priority.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This has a priority level of 4000.
	 * </note>
	 *
	 * @param string An error message.
	 * @param string The class where message was logged.
	 * @param string The function where message was logged.
	 * @param string The file where message was logged.
	 * @param int	The line where message was logged.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function warning ($message, $class = null, $function = null, $file = null,
					  $line = null)
	{

		$message = new Message(array('m' => $message,
									  'c' => $class,
									  'F' => $function,
									  'f' => $file,
									  'l' => $line,
									  'N' => 'WARNING',
									  'p' => Logger::WARN));

		$this->log($message);

	}

}

?>