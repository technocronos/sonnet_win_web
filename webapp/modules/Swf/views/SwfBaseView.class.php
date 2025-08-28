<?php

/**
 * Swfモジュールの共通ビュー。スマホ用
 */
class SwfBaseView extends BaseView {

    public function execute()
    {

        $request = $this->getContext()->getRequest();

        // テンプレートはこれを使う。
        $this->setTemplate('BaseContainer');

        // Requestオブジェクトにセットされている Attribute をすべてテンプレートに伝播する。
        foreach($request->getAttributeNames() as $attrName) {
            $this->setAttribute($attrName, $request->getAttribute($attrName));
        }

    }

}
