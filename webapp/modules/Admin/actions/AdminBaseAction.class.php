<?php

/**
 * Adminモジュールの全アクションの親クラス。
 * まだ特に何もしていない。
 */
abstract class AdminBaseAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    /**
     * initializeメソッドオーバーライド。
     */
    public function initialize ($context) {

        if( !parent::initialize($context) )
            return false;

        return true;
    }
}
