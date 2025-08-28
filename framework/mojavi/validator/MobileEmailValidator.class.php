<?php
/**
 * Eメールアドレスチェック（携帯電話メールアドレス対応）
 * Eメールアドレスチェック＋携帯電話メールアドレスチェック
 * 
 * (validate設定ファイルパラメータ)
 * email_error・・・Eメールアドレスの書式でない時に表示するメッセージ
 * max・・・Eメールアドレスの最大桁数(有効値：0〜)(デフォルト：-1)
 * max_error・・・最大桁数をオーバした時に表示するエラーメッセージ
 * min・・・Eメールアドレスの最小桁数(有効値：0〜)(デフォルト：-1)
 * min_error・・・最小桁数をオーバした時に表示するエラーメッセージ
 * can_pc・・・PCメールアドレスを許可するか(true:許可,false:不許可)(デフォルト:false)
 * can_pc_err・・・PCメールアドレスが許可されないときに表示するメッセージ
 * can_mobile・・・携帯電話メールアドレスを許可するか(true:許可,false:不許可)（デフォルト:true）
 * can_mobile_err・・・携帯電話メールアドレスが許可されないときに表示するメッセージ
 * 
 * @access public
 * @package	mojavi
 * @subpackage validator
 * @author Shinya Oooka <oooka@withit.co.jp>
 * @sourcefile
 */
class MobileEmailValidator extends EmailValidator
{
  // 携帯メールアドレス正規表現リスト
  private static $mobile_domain_regex = array(
    '@docomo\.ne\.jp$',                       // DoCoMo
    '@([a-z0-9]+\.)?mnx\.ne\.jp$',            // 10円メール
    '@([a-z0-9]+\.)?ezweb\.ne\.jp$',          // AU,Tu-Ka
    '@(ez.\.|cmail\.)?ido\.ne\.jp$',          // IDO
    '@email\.sky\.(tdp|kdp|cdp)\.ne\.jp$',    // デジタルホン（要るのか？）
    '@(sky|cara)\.tu-ka\.ne\.jp$',            // ツーカー
    '@([a-z0-9]+\.)?sky\.(tkk|tkc)\.ne\.jp$', // ツーカーホン関西・東海
    '@email\.sky\.dtg\.ne\.jp$',              // デジタルツーカー
    '@([a-z0-9]+\.)?em\.nttpnet.ne.jp$',      // 旧NTTパーソナル
    '@cm(chuo|hokkaido|tohoku|tokai|kansai|chugoku|shikoku|kyusyu)\.nttpnet\.ne\.jp$',
                                              // 旧NTTパーソナル
    '@(..\.)?pdx.ne.jp',                       // Willcom
    '@phone\.ne\.jp$',                        // アステル
    '@[a-z0-9]+\.mozio\.ne\.jp$',             // アステル
    '@softbank\.ne\.jp$',                     // Softbank mobile
    '@[dhtcrknsq]\.vodafone\.ne\.jp$',        // Vodafone
    '@jp-[dhtcrknsq]\.ne\.jp$',               // J-Phone
    );
  private $mailaddress = '';
  
  // +-----------------------------------------------------------------------+
  // | METHODS                                                               |
  // +-----------------------------------------------------------------------+
  
  /**
   * メールアドレスが携帯電話のものかチェック
   * @access private
   * @return boolean チェック結果
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
   * バリデーションを実行する
   * @access public
   * @param string &$value バリデーションするデータ
   * @param string &$error エラー内容(エラーメッセージが格納される)
   * @return boolean バリデーション結果
   */
  public function execute (&$value, &$error) {
    $this->mailaddress = $value;
    
    // Emailアドレスとして正当かチェック（親メソッド呼び出し）
    $result = parent::execute($value, $error);
    if ( $result ) { // メールアドレスが正常であればチェック継続
      if ( $this->isMobile() ) { // 携帯アドレスの場合
        $can_mobile = $this->getParameter('can_mobile');
        if ( ! $can_mobile ) { // 携帯不許可＆携帯アドレスの場合はエラー
          $error  = $this->getParameter('can_mobile_err');
          $result = false;
        }
      } else { // PCアドレスの場合
        $can_pc = $this->getParameter('can_pc');
        if ( ! $can_pc ) { // PC不許可＆PCアドレスの場合はエラー
          $error  = $this->getParameter('can_pc_err');
          $result = false;
        }
      }
    }
    return $result;
  }
  
  /**
   * 初期処理
   * @access public
   * @param Object $context context
   * @param array $parameters バリデータパラメータ
   * @return boolean 処理結果
   */
  public function initialize ($context, $parameters = null)
    {
      /* デフォルト値セット */
      $this->setParameter('can_pc',false);
	  $this->setParameter('can_pc_err', "Can't use pc mailaddress!");
      $this->setParameter('can_mobile', true);
      $this->setParameter('can_mobile_err', "Can't use mobile mailaddress!");
      
      /* バリデータパラメータをセットする */
      return parent::initialize($context, $parameters);
    }
}
?>