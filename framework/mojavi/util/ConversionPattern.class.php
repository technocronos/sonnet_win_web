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
 * @subpackage util
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     1.0.0
 * @version   $Id: ConversionPattern.class.php 634 2004-12-09 03:44:24Z seank $
 */
class ConversionPattern extends MojaviObject
{

	// +-----------------------------------------------------------------------+
    // | PRIVATE VARIABLES                                                     |
    // +-----------------------------------------------------------------------+
    
	/**
	 * The function that will be called when a conversion character is parsed.
	 *
	 * @access private
	 * @since  2.0
	 * @type   string
	 */
	private $func;

	/**
	 * The object containing the function to be called when a conversion
	 * character is parsed.
	 *
	 * @access private
	 * @since  2.0
	 * @type   object
	 */
	private $obj;

	/**
	 * A pattern containing conversion characters.
	 *
	 * @access private
	 * @since  2.0
	 * @type   string
	 */
	private $pattern;

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+
    
	/**
	 * Create a new ConversionPattern instance.
	 *
	 * @param string A pattern containing conversion characters.
	 *
	 * @return ConversionPattern A ConversionPattern instance.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function ConversionPattern ($pattern = null)
	{

		$this->func	= null;
		$this->obj	 = null;
		$this->pattern = $pattern;

	}

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

	/**
	 * Retrieve a parameter for a conversion character.
	 *
	 * @param int The pattern index at which we're working.
	 *
	 * @return string A conversion character parameter if one one exists,
	 *				otherwise <b>null</b>.
	 *
	 * @access private
	 * @since  2.0
	 */
	private function getParameter (&$index)
	{

		$length = strlen($this->pattern);
		$param  = '';

		// skip ahead to parameter
		$index += 2;

		if ($index < $length)
		{

			// loop through conversion character parameter
			while ($this->pattern{$index} != '}' && $index < $length)
			{

				$param .= $this->pattern{$index};
				$index++;

			}

			if ($this->pattern{$index} == '}')
			{

				return $param;

			}

			// parameter found but no ending }

		}

		// oops, not enough text to go around
		return null;

	}

	/**
	 * Retrieve the conversion pattern.
	 *
	 * @return string A conversion pattern.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function getPattern ()
	{

		return $this->pattern;

	}

	/**
	 * Parse the conversion pattern.
	 *
	 * @return string A string with conversion characters replaced with their
	 *				respective values.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function & parse ()
	{

		if ($this->pattern == null)
		{

			$error = 'A conversion pattern has not been specified';
			
			throw new MojaviException($error);

		}

		$length  = strlen($this->pattern);
		$pattern = '';

		for ($i = 0; $i < $length; $i++)
		{

			if ($this->pattern{$i} == '%' &&
				($i + 1) < $length)
			{

				if ($this->pattern{$i + 1} == '%')
				{

					$data = '%';
					$i++;

				} else
				{

					// grab conversion char
					$char  = $this->pattern{++$i};
					$param = null;

					// does a parameter exist?
					if (($i + 1) < $length &&
						$this->pattern{$i + 1} == '{')
					{

						// retrieve parameter
						$param = $this->getParameter($i);

					}

					if ($this->obj == null)
					{

						$data = $function($char, $param);

					} else
					{

						$object   = &$this->obj;
						$function = &$this->func;

						$data = $object->$function($char, $param);

					}

				}

				$pattern .= $data;

			} else
			{

				$pattern .= $this->pattern{$i};

			}

		}

		return $pattern;

	}

	/**
	 * Set the callback function.
	 *
	 * @param string A function name.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function setCallbackFunction ($function)
	{

		$this->func = $function;

	}

	/**
	 * Set the callback object and function.
	 *
	 * @param object An object holding a callback function.
	 * @param string A function name.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function setCallbackObject (&$object, $function)
	{

		$this->func =  $function;
		$this->obj  =& $object;

	}

	/**
	 * Set the conversion pattern.
	 *
	 * @param string A pattern consisting of one or more conversion characters.
	 *
	 * @access public
	 * @since  2.0
	 */
	public function setPattern ($pattern)
	{

		$this->pattern = $pattern;

	}

}

?>