<?php
/**
 * E�᡼�륢�ɥ쥹�����å��ʷ������å᡼�륢�ɥ쥹�б���
 * E�᡼�륢�ɥ쥹�����å��ܷ������å᡼�륢�ɥ쥹�����å�
 * 
 * (validate����ե�����ѥ�᡼��)
 * email_error������E�᡼�륢�ɥ쥹�ν񼰤Ǥʤ�����ɽ�������å�����
 * max������E�᡼�륢�ɥ쥹�κ�����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * max_error�������������򥪡��Ф�������ɽ�����륨�顼��å�����
 * min������E�᡼�륢�ɥ쥹�κǾ����(ͭ���͡�0��)(�ǥե���ȡ�-1)
 * min_error�������Ǿ�����򥪡��Ф�������ɽ�����륨�顼��å�����
 * can_pc������PC�᡼�륢�ɥ쥹����Ĥ��뤫(true:����,false:�Ե���)(�ǥե����:false)
 * can_pc_err������PC�᡼�륢�ɥ쥹�����Ĥ���ʤ��Ȥ���ɽ�������å�����
 * can_mobile�������������å᡼�륢�ɥ쥹����Ĥ��뤫(true:����,false:�Ե���)�ʥǥե����:true��
 * can_mobile_err�������������å᡼�륢�ɥ쥹�����Ĥ���ʤ��Ȥ���ɽ�������å�����
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Shinya Oooka <oooka@withit.co.jp>
 * @sourcefile
 */
class MobileEmailValidator extends EmailValidator
{
  // ���ӥ᡼�륢�ɥ쥹����ɽ���ꥹ��
  private static $mobile_domain_regex = array(
    '@docomo\.ne\.jp$',                       // DoCoMo
    '@([a-z0-9]+\.)?mnx\.ne\.jp$',            // 10�ߥ᡼��
    '@([a-z0-9]+\.)?ezweb\.ne\.jp$',          // AU,Tu-Ka
    '@(ez.\.|cmail\.)?ido\.ne\.jp$',          // IDO
    '@email\.sky\.(tdp|kdp|cdp)\.ne\.jp$',    // �ǥ�����ۥ���פ�Τ�����
    '@(sky|cara)\.tu-ka\.ne\.jp$',            // �ġ�����
    '@([a-z0-9]+\.)?sky\.(tkk|tkc)\.ne\.jp$', // �ġ������ۥ�������쳤
    '@email\.sky\.dtg\.ne\.jp$',              // �ǥ�����ġ�����
    '@([a-z0-9]+\.)?em\.nttpnet.ne.jp$',      // ��NTT�ѡ����ʥ�
    '@cm(chuo|hokkaido|tohoku|tokai|kansai|chugoku|shikoku|kyusyu)\.nttpnet\.ne\.jp$',
                                              // ��NTT�ѡ����ʥ�
    '@(..\.)?pdx.ne.jp',                       // Willcom
    '@phone\.ne\.jp$',                        // �����ƥ�
    '@[a-z0-9]+\.mozio\.ne\.jp$',             // �����ƥ�
    '@softbank\.ne\.jp$',                     // Softbank mobile
    '@[dhtcrknsq]\.vodafone\.ne\.jp$',        // Vodafone
    '@jp-[dhtcrknsq]\.ne\.jp$',               // J-Phone
    );
  private $mailaddress = '';
  
  // +-----------------------------------------------------------------------+
  // | METHODS                                                               |
  // +-----------------------------------------------------------------------+
  
  /**
   * �᡼�륢�ɥ쥹���������äΤ�Τ������å�
   * @access private
   * @return boolean �����å����
   */
  private function isMobile() {
    $address = strtolower($this->mailaddress);
    foreach ( self::$mobile_domain_regex as $regex ) {
      if ( preg_match("/$regex/", $address ) ) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * �Х�ǡ�������¹Ԥ���
   * @access public
   * @param string &$value �Х�ǡ�����󤹤�ǡ���
   * @param string &$error ���顼����(���顼��å���������Ǽ�����)
   * @return boolean �Х�ǡ��������
   */
  public function execute (&$value, &$error) {
    $this->mailaddress = $value;
    
    // Email���ɥ쥹�Ȥ��������������å��ʿƥ᥽�åɸƤӽФ���
    $result = parent::execute($value, $error);
    if ( $result ) { // �᡼�륢�ɥ쥹������Ǥ���Х����å���³
      if ( $this->isMobile() ) { // ���ӥ��ɥ쥹�ξ��
        $can_mobile = $this->getParameter('can_mobile');
        if ( ! $can_mobile ) { // �����Ե��ġ����ӥ��ɥ쥹�ξ��ϥ��顼
          $error  = $this->getParameter('can_mobile_err');
          $result = false;
        }
      } else { // PC���ɥ쥹�ξ��
        $can_pc = $this->getParameter('can_pc');
        if ( ! $can_pc ) { // PC�Ե��ġ�PC���ɥ쥹�ξ��ϥ��顼
          $error  = $this->getParameter('can_pc_err');
          $result = false;
        }
      }
    }
    return $result;
  }
  
  /**
   * �������
   * @access public
   * @param Object $context context
   * @param array $parameters �Х�ǡ����ѥ�᡼��
   * @return boolean �������
   */
  public function initialize ($context, $parameters = null)
    {
      /* �ǥե�����ͥ��å� */
      $this->setParameter('can_pc',false);
	  $this->setParameter('can_pc_err', "Can't use pc mailaddress!");
      $this->setParameter('can_mobile', true);
      $this->setParameter('can_mobile_err', "Can't use mobile mailaddress!");
      
      /* �Х�ǡ����ѥ�᡼���򥻥åȤ��� */
      return parent::initialize($context, $parameters);
    }
}
?>