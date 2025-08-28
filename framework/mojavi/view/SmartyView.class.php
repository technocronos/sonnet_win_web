<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Mojavi package.                                  |
// | Copyright (c) 2003, 2004 Sean Kerr.                                       |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.mojavi.org.                             |
// +---------------------------------------------------------------------------+

require_once(MO_SMARTY_DIR.'/libs/Smarty.class.php');

/**
 * $Id: SmartyView.class.php 65 2004-10-26 03:16:15Z seank $
 *
 *
 * @package    mojavi
 * @subpackage view
 *
 * @author    Sean Kerr (skerr@mojavi.org)
 * @copyright (c) Sean Kerr, {@link http://www.mojavi.org}
 * @since     3.0.0
 * @version   $Rev$
 */
abstract class SmartyView extends View
{

    // +-----------------------------------------------------------------------+
    // | PRIVATE VARIABLES                                                     |
    // +-----------------------------------------------------------------------+
	protected $smarty;

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+
	public function __construct()
	{
		$this->smarty = new Smarty();
	}

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+
	public function initialize($context)
	{
        // 親のメソッドを呼ぶ。
        parent::initialize($context);

		$this->smarty->config_dir = MO_CONFIG_DIR;
		$this->smarty->cache_dir = MO_CACHE_DIR;
		$this->smarty->template_dir = $this->getDirectory();
		$this->smarty->compile_dir  = MO_SMARTY_CACHE.'/'.$context->getModuleName();

		if (!file_exists($this->smarty->compile_dir))
			mkdir($this->smarty->compile_dir, 0755, true);

		return true;
	}

	public function clearAttributes ()
	{
		$this->smarty->clear_all_assign();
	}

	protected function decorate (&$content)
	{
		parent::decorate($content);

		$decoratorTemplate = $this->getDecoratorDirectory() . '/' .
								$this->getDecoratorTemplate();

		$retval = $this->getEngine()->fetch($decoratorTemplate);

        return $retval;
    }

	public function getAttributeNames ()
	{
		return array_keys($this->smarty->get_template_vars());
	}

	public function getAttribute ($name)
	{
		return $this->smarty->get_template_vars($name);
	}

	public function removeAttribute ($name)
	{
		$retval = $this->smarty->get_template_vars($name);

		$this->smarty->clear_assign($name);

		return $retval;
	}

	public function setAttribute ($name, $value)
	{

		$this->smarty->assign($name, $value);
	}

	public function setAttributeByRef ($name, &$value)
	{
		$this->smarty->assign_by_ref($name, $value);
	}

	public function setAttributes ($attributes)
	{
		$this->smarty->assign($attributes);
	}

	public function setAttributesByRef (&$attributes)
	{
		$this->smarty->assign_by_ref($attributes);
	}

	public function getEngine ()
	{
		return $this->smarty;
	}

	public function render ()
	{

		$retval = null;

		// execute pre-render check
		$this->preRenderCheck();

		// get the render mode
		$mode = $this->getContext()->getController()->getRenderMode();

		if ($mode == View::RENDER_CLIENT && !$this->isDecorator())
		{
			$this->getEngine()->display($this->getTemplate());
		}
		else if ($mode != View::RENDER_NONE)
		{
			$retval = $this->getEngine()->fetch($this->getTemplate());

			if ($this->isDecorator())
			{
				$retval =& $this->decorate($retval);
			}
			if ($mode == View::RENDER_CLIENT)
			{
				echo $retval;
				$retval = null;
			}
			if ($mode == View::RENDER_OUTPUT)
			{
				$fl = fopen($this->getOutputDirectory() . '/' . $this->getOutputFilename(), "w");
				fwrite($fl, $retval);
				fflush($fl);
				fclose($fl);
				$retval = null;
			}
		}

		return $retval;
	}

}
