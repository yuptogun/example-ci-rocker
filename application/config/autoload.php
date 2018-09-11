<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 항상 필요하다 싶은 것들은 오토로딩해 두면 좋습니다.
// 여기서나 컨트롤러에서 로딩한 적이 없는데 자동 로딩되는 것은 /ci/application/config/config.php 에서 composer_autoload 설정에 의해 불러와지는 것입니다.
// 오토로딩 매뉴얼: http://www.ciboard.co.kr/user_guide/kr/general/autoloader.html

// Ion Auth를 기본으로 가져옵니다.
// Ion Auth는 아래 경로에 설치돼 있습니다.
$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'session', 'form_validation', 'jwtauth');

$autoload['drivers'] = array();
$autoload['helper'] = array('url', 'file', 'cookie', 'form', 'app', 'view');
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array();
