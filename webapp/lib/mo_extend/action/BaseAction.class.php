<?php

/**
 * アプリケーション全体のAction親クラス。
 */
abstract class BaseAction extends Action
{
    /**
     * Controllerオブジェクト
     */
    protected $controller = '';

    /**
     * Contextオブジェクト
     */
    protected $context = '';

    /**
     * Requestオブジェクト
     */
    protected $request = '';

    /* userオブジェクト */
    protected $user = '';


    //-----------------------------------------------------------------------------------------------------
    /**
     * 初期処理
     * @access public
     * @param Object $context context
     * @return boolean 処理結果
     */
    public function initialize ($context)
    {

        parent::initialize($context);

        // アクションの共通処理を実装する
        /* 初期値をセットする */
        $this->context = $context;
        $this->controller =$context->getController();
        $this->request = $context->getRequest();
        $this->user = $context->getUser();

        $GLOBALS['logger']  = $this->controller->getLogger();

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * デフォルトのexecuteを実装。
     */
    public function execute () {
        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * $this->request->setAttribute() のショートカット。
     */
    public function setAttribute($name, $value) {
        $this->request->setAttribute($name, $value);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ログを行う。
     * 引数はログの内容。printfと同じように受け付ける。
     */
    public function log(/* 可変引数 */) {

        $args = func_get_args();
        $log = call_user_func_array('sprintf', $args);

        $this->controller->getLogger()->WARNING($log);
    }
}
