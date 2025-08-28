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
 * @version   $Id: FileAppender.class.php 65 2004-10-26 03:16:15Z seank $
 */
class FileAppender extends Appender
{

	// +-----------------------------------------------------------------------+
    // | PRIVATE VARIABLES                                                     |
    // +-----------------------------------------------------------------------+

	/**
	 * Whether or not the file pointer is opened in append mode.
	 *
	 * @access private
	 * @since  2.0
	 * @type   bool
	 */
	private $append;

	/**
	 * An absolute file-system path to the log file.
	 *
	 * @access private
	 * @since  2.0
	 * @type   string
	 */
	private $file;

	/**
	 * A pointer to the log file.
	 *
	 * @access private
	 * @since  2.0
	 * @type   resource
	 */
	private $fp;

	/**
	 * The conversion pattern to use with this layout.
	 *
	 * @access private
	 * @since  2.0
	 * @type   ConversionPattern
	 */
	private $pattern;

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+

	/**
	 * Create a new FileAppender instance.
	 *
	 * <br/><br/>
	 *
	 * Conversion characters:
	 *
	 * <ul>
	 *	 <li><b>%C{constant}</b> - the value of a PHP constant</li>
	 *	 <li><b>%d{format}</b>   - a date (uses date() format)</li>
	 * </ul>
	 *
	 * @param Layout A Layout instance.
	 * @param string An absolute file-system path to the log file.
	 * @param bool   Whether or not the file pointer should be opened in
	 *			   appending mode (if false, all data is truncated).
	 *
	 * @access public
	 * @since  2.0
	 */
	public function FileAppender ($layout, $file, $append = true)
	{

		parent::Appender($layout);

		$this->append  =  $append;
		$this->file	=  $file;
		$this->pattern = new ConversionPattern($file);

		$this->openFP();

	}

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

	/**
	 * ConversionPattern callback method.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This should never be called manually.
	 * </note>
	 *
	 * @param string A conversion character.
	 * @param string A conversion parameter.
	 *
	 * @return string A replacement for the given data.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function & callback ($char, $param)
	{

		switch ($char)
		{

			case 'C':

				$data = (defined($param)) ? constant($param) : '';

				break;

			case 'd':

				// get the date
				if ($param == null)
				{

					$param = 'd_j_y';

				}

				$data = date($param);

		}

		return $data;

	}

	/**
	 * Close the file pointer.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This should never be called manually.
	 * </note>
	 *
	 * @access public
	 * @since  2.0
	 */
	function cleanup ()
	{

		if ($this->fp != null)
		{

			@fflush($this->fp);
			@fclose($this->fp);

			$this->fp = null;

		}

	}

	/**
	 * Open the file pointer.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This should never be called manually.
	 * </note>
	 *
	 * @access private
	 * @since  2.0
	 */
	public function openFP ()
	{

		// register callback method
		// this cannot be done in the constructor
		$this->pattern->setCallbackObject($this, 'callback');

		$this->file = $this->pattern->parse();
		$this->fp   = @fopen($this->file, ($this->append) ? 'a' : 'w');

		if ($this->fp === false)
		{

			$error = 'Failed to open log file ' . $this->file . ' for writing';

			throw new LoggingException($error);

		}

	}

	/**
	 * Write a message to the log file.
	 *
	 * <br/><br/>
	 *
	 * <note>
	 *	 This should never be called manually.
	 * </note>
	 *
	 * @param string The message to write.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function write (&$message)
	{

		@fputs($this->fp, $message);
		@fflush($this->fp);

	}

	/**
	 * Close the log file.
	 *
	 * @access public
	 * @author t.wakuda <wakuda@withit.co.jp>
	 */
	public function shutdown()
	{
		
		@fclose($this->fp);
		$this->fp = null;
		
	}
}

?>