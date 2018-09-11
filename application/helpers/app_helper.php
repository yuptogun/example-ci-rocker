<?php

// 이 CI 앱이 뷰에서 공통으로 사용할 자료 배열을 스캐폴딩합니다.
function returnAppData($user = null)
{
	// 내용은 순전히 임의로 꾸민 것입니다.
	// 예컨대 Bootstrap Admin Panel 패키지가 요구하는 형식이 따로 있을 경우 적절히 변용해서 쓰면 됩니다.
	return [

		// 사이트 정보
		'site' => [
			'name' => [
				'full' => 'CI CMS 예제 사이트',
				'short' => 'CMS',
			],

			// 메뉴에 null을 넣으면 상단 메뉴바를 아예 안 만들 수 있습니다. views/templates/bootstrap/1_navbar.php 를 참조하세요.
			'menu' => returnMenu($user),
			'bodyClass' => [],

			// 각 태그 맨 끝에 추가로 html을 넣을 수 있습니다.
			'extra' => [
				'head' => [],
				'body' => [],
			]
		],
		'user' => $user,
	];
}

// 사용자 정보에 따라서 메뉴를 구성합니다.
function returnMenu($user = null)
{
	// 왼쪽 메뉴 기본값
	$menu = [
		'left' => [
			'bookings' => [
				'name' => '예약 관리',
				'route' => '/bookings',
			]
		],
	];

	// 사용자가 있으면
	if ($user) {

		// 우측에 사용자 관련 메뉴 추가
		$menu['right'] = [
			'me' => [
				'name' => $user->username,
				'route' => 'me',
			],
		];

		// 사용자가 최고관리자면
		if ($user->role == 'superadmin') {

			// 우측메뉴에 CMS 관리메뉴 추가
			array_unshift($menu['right'], [
				'name' => 'CMS관리',
				'route' => 'admin',
			]);
		}
	}
	return $menu;
}