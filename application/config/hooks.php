<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 라우트가 호출한 컨트롤러가 이제 막 생성되고 나면 다음 filepath에 있는 다음 filename 파일의 class 클래스 내 function 메소드를 작동시킵니다.
// libraries/JWTAuth.php 의 JWTAuth->authenticate() 메소드를 보세요.
$hook['post_controller_constructor'] = [
	'class'    => 'JWTAuth',
    'function' => 'authenticate',
    'filename' => 'JWTAuth.php',
    'filepath' => 'libraries',
];