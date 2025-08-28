<?php

/**
 * User���W���[���̋��ʃr���[�B
 */
class UserBaseView extends BaseView {

    //-----------------------------------------------------------------------------------------------------
    // �e��initialize���\�b�h���I�[�o�[���C�h�B
    public function initialize ($context)
    {
        // �e�̓����\�b�h���ĂԁB
        if( !parent::initialize($context) )
            return false;

        // Smarty�I�u�W�F�N�g���擾�B
        $smarty = $this->getEngine();

        //�R���t�B�O�t�@�C�����[�h�B
        $smarty->config_load($this->getDirectory().'/include/colors.ini');

        // �|�X�g�t�B���^��ݒ�B
        $smarty->register_outputfilter( array($this,'filterGenerally') );

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * execute���I�[�o�[���C�h�B
     */
    public function execute () {

        // �A�N�V�����̃v���p�e�BuserInfo���r���[�ł��g����悤�ɂ���B
        $action = $this->getContext()->getController()->getActionStack()->getLastEntry()->getActionInstance();
        $this->setAttribute('userInfo', $action->userInfo);
        $this->setAttribute('userId', $action->userInfo ? $action->userInfo['user_id'] : null);

        // �L�����A�𔻕ʂ���B
        $carrier = Common::getCarrier();

        // ���ʂŃZ�b�g����Smarty�ϐ����Z�b�g�B
        $this->setAttribute('appId', isset($_REQUEST["opensocial_app_id"]) ? $_REQUEST["opensocial_app_id"] : null);
        $this->setAttribute('carrier', $carrier);
        $this->setAttribute('encode', Common::getEncoding($carrier));
        switch($carrier) {
            case 'docomo':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.3) 1.0//EN" "i-xhtml_4ja_10.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'x-small');
                break;
            case 'au':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//OPENWAVE//DTD XHTML 1.0//EN" "http://www.openwave.com/DTD/xhtml-basic.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'x-small');
                break;
            case 'softbank':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//J-PHONE//DTD XHTML Basic 1.0 Plus//EN" "xhtml-basic10-plus.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'small');
                break;
            case 'pc':
                $this->setAttribute("doctype", '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">');
                $this->setAttribute("css_medium", 'medium');
                $this->setAttribute("css_small", 'small');
                break;
        }

        // ���Ƃ͐e�ɔC����B
        return parent::execute();
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * smarty �Őݒ肵�Ă���A�E�g�v�b�g�t�B���^�B�S�L�����A�P�\�[�X�̂��߂̒������s���B
     */
    public function filterGenerally($output, $smarty) {

        $normalize = sprintf('<div style="font-size:%s">'
            , htmlspecialchars($smarty->get_template_vars('css_small'), ENT_QUOTES)
        );

        // �e�[�u���Z���̕������W���T�C�Y�ɂȂ��Ă��܂��̂��C������B
        $output = preg_replace('/<td(?:\s[^>]*)?>/', '$0'.$normalize, $output);
        $output = str_replace('</td>', '</div></td>', $output);

        // docomo �̏ꍇ�ɁA<input> �� istyle ��XHTML�p�̂��̂ɕϊ�����B
        if(Common::getCarrier() == 'docomo') {
            $output = preg_replace('/(<input\b.*?)istyle="3"(.*?>)/is', '$1style="-wap-input-format:&quot;*&lt;ja:en&gt;&quot;"$2', $output);
            $output = preg_replace('/(<input\b.*?)istyle="4"(.*?>)/is', '$1style="-wap-input-format:&quot;*&lt;ja:n&gt;&quot;"$2', $output);
        }

        // PC�̏ꍇ��...
        if(Common::getCarrier() == 'android' || Common::getCarrier() == 'iphone') {

            // <input type="submit"> �� <button></button> �ɕϊ�����B�������Ȃ��ƊG������<img>��
            // �ϊ����ꂽ�Ƃ��� <input> �^�O������B
            //$output = preg_replace('/<input type="submit"(.*?)value="([^"]*)"([^>]*)>/is', '<button type="submit"$1 value="1" $3>$2</button>', $output);

            // ���p�J�i��S�p�J�i�ɕϊ��B���[�U�����͂����e�L�X�g���ĕ\������Ƃ��Ƃ��A����ł����̂��Ǝv��
            // ��ʂ����邪�A�܂�������B
            $output = mb_convert_kana($output, 'KV', 'UTF-8');
        }

        return $output;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * setTemplate�Ńe���v���[�g���w�肷��Ƃ��A���������g���q���w�肵�Ȃ��Ă��ǂ��悤�ɂ���B
     */
    public function setTemplate($template_file)
    {
        // �L�����A�𔻕ʂ���B
        $carrier = Common::getCarrier();
        $this->setAttribute("template_file", $template_file);

        //�l�C�e�B�u�pHTML������ꍇ�͂�����g��
        if(PLATFORM_TYPE == "nati"){
            $contents_file = MO_HTDOCS . "/html/contents/native/" . $template_file . ".html";
            if(!file_exists($contents_file)){
                $contents_file = MO_HTDOCS . "/html/contents/" . $template_file . ".html";
            }
        }else{
            $contents_file = MO_HTDOCS . "/html/contents/" . $template_file . ".html";
        }
        $this->setAttribute("contents_file", $contents_file);


        //���b��Ή��B�w���v�̎��������̂܂܂̃e���v���[�g�g��
        //���o�O���̗F�B���҂�ajax�ŃR�[������HTML�łł��Ȃ��̂Ŏd���Ȃ��E�E
        if($template_file == "HelpContent")
            parent::setTemplate($template_file);
        else if($carrier == "iphone" || $carrier == "android")
            parent::setTemplate("BaseContainer");
        else
            parent::setTemplate($template_file);

    }
}
