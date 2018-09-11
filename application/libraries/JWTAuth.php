<?php

// 전통적인 사용자 인증은 쿠키와 세션을 사용했습니다.
// 하지만 쿠키/세션 자체를 못 쓰는 환경(ex: curl 바로 날리기 등)이 있다는 것이 발견되었습니다.
// 이후 많은 사람들이 삽질하여 HTTP 요청에 특정 헤더를 쓰고 그 헤더에 필요한 정보를 얹는 방법을 찾아냅니다.
// 그 정보에 세션값이나 쿠키값을 얹어도 되겠지만 기왕 하는 거 좀더 첨단으로 가보자고 도입된 게 JSON Web Tokens인 것입니다.
// 아닐 수도 있습니다.

// index.php 에서 composer autoload를 하고 있기 때문에 이걸 쓸 수 있습니다.
use \Firebase\JWT\JWT;

// JWT를 사용해서 사용자 인증을 구현해 봅니다.
class JWTAuth {

	// 외부 유출되면 안 되는 고유 암호키
	protected $key = "QpS76DxzA7cMJiFNsv4Cq1XEZqU5Cftr";

	// Auth 헤더값 대신 쓸 쿠키 정보를 지정해 둡니다.
	protected $cookieName = 'token_example_ci';
	protected $cookieBestBefore = 60*60*24*365; // 이 쿠키 유통기한은 1년

	// 이 라우트들은 로그인 안 해도 되도록 해보려고 합니다.
	protected $dontAuthenticate = ['login', 'logout', 'register', 'reset'];

	// 그러면 시작해 볼까요?
	public function __construct()
	{
		// this->CI 라이브러리 등을 써야 하니 CI 인스턴스를 가져옵니다.
		$this->CI =& get_instance();
	}

	// 로그인해야 하는지 안 해도 되는지 체크하기
	// 이 메소드는 config/hooks.php 에서 인용하고 있습니다.
	// PSR-7을 준수하려면 이걸 미들웨어 개념으로 처리해야 하는데 CI에는 그런게 없어서 시스템 훅을 써야 합니다.
	public function authenticate()
	{
		// 꼭 로그인을 해야 하는 라우트라면
		if ($this->shouldAuthenticate()) {

			// 토큰을 찾아봐서
			$jwt = $this->getJWT();

			// 토큰이 없거나 유효하지 않을 경우
			if (empty($jwt) || !$this->decodeJWT($jwt)) {

				// 로그인 페이지로 이동시킵니다.
				// 그러면 로그인 컨트롤러가 알아서 토큰을 만들어주든 말든 하겠지요.
				// controllers/Auth.php 참고
				redirect('login', true);

			// 토큰이 있으며 유효할 경우
			} else {

				// 다시 응답헤더에 토큰을 얹어서 돌려줍니다.
				$this->putJWT($jwt);
			}
		}
	}

	// 토큰 찾아보기
	public function getJWT()
	{
		// 요청 헤더에 Authorization 정보가 있으면
		return $this->CI->input->get_request_header('Authorization') ?

			// "Bearer " 뒤의 값을 가져오고
			substr($this->CI->input->get_request_header('Authorization'), 7) : (

				// 없으면 쿠키를 찾아보고 그마저도 없으면 null을 돌려줍니다.
				get_cookie($this->cookieName) ?? null
			);
	}

	// 토큰 디코딩해서 돌려주기
	public function decodeJWT($jwt)
	{
		// 특정 알고리즘으로 디코딩을 시도하고 결과(원래 포함됐던 정보배열)를 돌려줍니다.
		try {
			return JWT::decode($jwt, $this->key, ['HS256']);

		// 디코딩해 봤는데 유효하지 않을 수 있습니다. (다른 Authorization 헤더나 쿠키값이 있어 재수 없이 겹치는 경우 등) 그러면 Signature verification failed 에러가 떨어지는데 이럴 때는 null을 돌려주기로 합니다.
		} catch (Exception $e) {
			return null;
		}
		
	}

	// users 테이블 쿼리결과 객체를 가지고 토큰 만들기
	public function setJWT($user, $new = false)
	{
		// 해당 유저의 이메일 값을 고유 키로 사용해서 JWT로 만들어 돌려줍니다.
		$jwt = JWT::encode(['email' => $user->email], $this->key);

		// 새로 토큰을 만들거나 새 토큰으로 바꾸는 거라면 그 토큰을 응답에 얹어줍니다.
		if ($new) {
			$this->putJWT($jwt);

		// 아니면 그냥 JWT 자체를 그대로 돌려줍니다.
		} else {
			return $jwt;
		}
	}

	// 응답에 토큰 얹기
	public function putJWT($jwt)
	{
		// 쿠키를 굽습니다.
		set_cookie($this->cookieName, $jwt, $this->cookieBestBefore);

		// 헤더를 만들어줍니다.
		$this->CI->output->set_header('Authorization: Bearer '.$jwt);
	}

	// 현재 인증 정보 파기하기
	public function unauthenticate()
	{
		// 쿠키를 없애고
		set_cookie($this->cookieName, null);

		// 헤더를 없앱니다.
		header_remove('Authorization');
	}

	// 꼭 로그인을 해야 하는 라우트인지 체크하기
	// false가 나오면 인증을 안 거칩니다.
	public function shouldAuthenticate()
	{
		// 일단은 첫화면 홈페이지 말고는 웬만하면 다 인증을 하는 걸로 합니다.
		$return = (current_url() != base_url());

		// 맨 앞에서 정의한 라우트들을 가지고 검사를 합니다.
		foreach ($this->dontAuthenticate as $route) {

			// 지금 접속한 주소가 위에서 "인증 안 하기로" 한 라우트일 땐 인증 안 하기로 합니다.
			if (strpos(current_url(), $route) !== FALSE) $return = false;
		}

		// 검사 결과를 돌려줍니다.
		return $return;
	}
}