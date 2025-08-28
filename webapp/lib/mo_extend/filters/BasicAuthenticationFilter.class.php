<?php
/**
 * Implement Basic Authentication
 *
 * @package Filter
 * @category Filter
 * @author Kyoichi Ozaki <ozaki @ kyoichi.jp>
 * @sourcefile
 *
 */

class BasicAuthenticationFilter extends Filter {

  // change to your local setting.
  const HTPASSWORDFILE = '/modules/Admin/.htpasswd';

  // change to your local setting.
  const REALM = 'mojavi 3 sample';

  // display this when not authorized.
  const ERROR_401_MESSAGE = '<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Authentication required!</title>
<style type="text/css"><!--/*--><![CDATA[/*><!--*/
    body { color: #000000; background-color: #FFFFFF; }
    a:link { color: #0000CC; }
    p, address {margin-left: 3em;}
    span {font-size: smaller;}
/*]]>*/--></style>
</head>

<body>
<h1>Authentication required!</h1>
<p>
    This server could not verify that you are authorized to access.
    You either supplied the wrong credentials (e.g., bad password), or your
    browser doesn\'t understand how to supply the credentials required.
  </p>
<p>
    In case you are allowed to request the document, please
    check your user-id and password and try again.
</p>

<h2>Error 401</h2>
</body>
</html>
';

  public function execute($filterChain) {

    $bool = false;

    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

      $bool =& $this->isValidUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

    }

    if ($bool) {

      $filterChain->execute();

    } else {

      header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
      header('HTTP/1.0 401 Unauthorized');
      echo self::ERROR_401_MESSAGE;

    }

  }

  /**
   * Check user's password
   *
   * @access private
   */
  private function isValidUser ($userId, $password) {
    $ret = false;

    if ($userId == '') {
      return $ret;
    }

    $fp = fopen(MO_WEBAPP_DIR . self::HTPASSWORDFILE, "r");
    if ($fp) {
      while(!feof($fp)) {
        $buffer = trim(fgets($fp, 4096));
        if ($buffer != "") {
          list($fileUser, $filePass) = explode(":", $buffer);
          if ($fileUser === $userId) {
            $salt = substr($filePass, 0, 2);
            if ($filePass === crypt($password, $salt)) {
              $ret = true;
              break;
            }
          }
        }
      }
      fclose($fp);
    }
    return $ret;
  }

}

?>