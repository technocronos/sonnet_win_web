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
 * @version   $Id: Message.class.php 764 2005-01-31 18:40:48Z seank $
 */
class Message extends ParameterHolder
{

    // +-----------------------------------------------------------------------+
    // | CONSTRUCTOR                                                           |
    // +-----------------------------------------------------------------------+

	public function Message ($params = null)
	{
		$this->setParameters(($params == null) ? array() : $params);
	}

    // +-----------------------------------------------------------------------+
    // | METHODS                                                               |
    // +-----------------------------------------------------------------------+

    // 2007/05/16 t.wakuda ParameterHolder�Τ�Τ�Ѿ����ƻ��Ѥ���
}

?>