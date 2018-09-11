<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	// 생성자 부분은 controllers/Welcome.php 에서 설명하므로 생략...
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model', 'users');
		$currentUser = $this->users->currentUser();
		$this->data = returnAppData($currentUser);
		$this->data['site']['name']['full'] = 'CI CMS 계정관리';
		$this->data['site']['menu'] = null;
		$this->data['site']['bodyClass'] = ['auth', 'bg-dark'];
		$this->data['site']['extra']['head'] = [
			'<style>html, body.auth {color: white; height: 100%;}</style>'
		];
	}

	// 첫화면
	public function index()
	{
		$data = $this->data;
		returnView('welcome/index', $data);
	}

	// 로그인을 시킵니다.
	public function login()
	{
		// 자료를 가져와 봐서
		$data = $this->data;

		// 이미 로그인된 사용자가 있으면 적당한 곳으로 보내 버립니다.
		if ($data['user']) redirect('/', true);

		// 폼검증 설정을 합니다.
		$this->form_validation->set_rules('email', '이메일', 'required|valid_email', [
			'required' => '%s은 필수입니다.',
			'valid_email' => '%s이 올바르지 않습니다.',
		]);
		$this->form_validation->set_rules('password', '비밀번호', 'required', [
			'required' => '%s는 필수입니다.',
			'isUser' => '이메일 혹은 %s가 잘못되었습니다.',
		]);

		// 폼검증 자체가 안 돌았거나 에러가 있었을 경우
		if ($this->form_validation->run() == FALSE) {

			// 로그인 폼 뷰를 띄워주고
			returnView('auth/login', $data);

		// 폼검증이 통과되었으면
		} else {

			// 귀찮으니까 입력값 배열을 하나 받은 다음
			$post = $this->input->post();

			// 모델에게 사용자 확인을 요청합니다.
			if (!$this->users->isValidUser($post['email'], $post['password'])) {

				// 사용자 확인이 안 되면 뷰에게 결과를 돌려줍니다.
				$this->form_validation->set_message('no_valid_user', '이메일이나 비밀번호가 잘못되었습니다.');
				returnView('auth/login', $data);

			// 모델이 확인해본 결과 이 회원이 존재하면
			} else {

				// 토큰을 쓰라고 만들어 넘겨준 뒤
				$user = $this->users->select(['email' => $post['email']], 1);
				$this->jwtauth->setJWT($user, true);

				// 적당한 곳으로 리디렉션 시켜줍니다.
				redirect('/', true);
			}
		}
	}

	public function logout()
	{
		$this->jwtauth->unauthenticate();
		redirect('/', true);
	}
}
