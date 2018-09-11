<?php

// CI 기본 코드에 항상 들어 있는 보안 처리 코드. 사실 없어도 됩니다.
defined('BASEPATH') OR exit('No direct script access allowed');

// 모든 웹앱은 (실무적으로는) 라우팅에서 시작합니다.
// CI는 $route 변수에 라우트 규칙을 지정합니다.
// http://www.ciboard.co.kr/user_guide/kr/general/routing.html

// 첫 페이지를 다룰 컨트롤러 이름입니다. application/controller/Welcome.php 를 찾으세요.
$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/notFound';
$route['translate_uri_dashes'] = FALSE;
// 이상 3가지 라우트 정의는 제일 앞에 나와야 합니다.

// bookings, me 관련 라우트는 따로 정의하지 않았습니다.
// 왜냐하면 CI는 기본적으로 /컨트롤러/메소드/변수1/변수2 형식의 라우팅을 해주기 때문입니다.
// 위 자동라우팅 규칙 외의 자체 규칙을 만들고 싶을 때 라우팅을 정의합니다.

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';