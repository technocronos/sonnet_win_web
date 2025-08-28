<?php

/**
 * アプリケーション全体のView親クラス。
 */
class BaseView extends SmartyView {

    // public メンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    // 親のinitializeメソッドをオーバーライド。
    public function initialize ($context)
    {
        // 親の同メソッドを呼ぶ。
        if( !parent::initialize($context) )
            return false;

        // Smartyオブジェクトを取得。
        $smarty = $this->getEngine();

        // プラグインディレクトリを追加。
        $smarty->plugins_dir[] = $this->getDirectory() . '/plugins';
        $smarty->plugins_dir[] = MO_WEBAPP_DIR . '/templates/plugins';

        // Smartyの変数出力に、デフォルトでエスケープがかかるようにする。
        // このエスケープ動作を抑制したい変数出力時は {$var|smarty:nodefaults} のようにする。
        $smarty->default_modifiers[] = 'escape_custom';

        // trueを返す。
        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ビューの典型的なexecuteの挙動をデフォルトのものとして実装しておく。
     */
    public function execute () {

        $request = $this->getContext()->getRequest();

        // Requestオブジェクトにセットされている Attribute をすべてテンプレートに伝播する。
        foreach($request->getAttributeNames() as $attrName) {
            $this->setAttribute($attrName, $request->getAttribute($attrName));
        }

        // doExecuteが定義されているならばそれもコールしておく。
        if(method_exists($this, 'doExecute'))
            $this->doExecute($request);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * setTemplateでテンプレートを指定するとき、いちいち拡張子を指定しなくても良いようにする。
     */
    public function setTemplate($template_file)
    {
        parent::setTemplate($template_file . ".tpl");

    }
}
