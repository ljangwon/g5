<?php

/*******************************************************************************
 ** 공통 변수, 상수, 코드
 *******************************************************************************/
error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('G5_SET_TIME_LIMIT')) define('G5_SET_TIME_LIMIT', 0);
@set_time_limit(G5_SET_TIME_LIMIT);

if (version_compare(PHP_VERSION, '5.2.17', '<')) {
    die(sprintf('PHP 5.2.17 or higher required. Your PHP version is %s', PHP_VERSION));
}

//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array(
    'PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
    'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
    'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS'
);
$ext_cnt = count($ext_arr);
for ($i = 0; $i < $ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================


function g5_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
    $result['path'] = str_replace('\\', '/', $chroot . dirname(__FILE__));
    $server_script_name = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_NAME']));
    $server_script_filename = preg_replace('/\/+/', '/', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $server_script_name);
    $document_root = str_replace($tilde_remove, '', $server_script_filename);
    $pattern = '/.*?' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':' . $_SERVER['SERVER_PORT'];
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $server_script_filename), '', $server_script_name);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if (isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http . $host . $port . $user . $root;
    return $result;
}

$g5_path = g5_path();

include_once($g5_path['path'] . '/config.php');   // 설정 파일
echo var_dump($g5_path) . "<br>";

unset($g5_path);
