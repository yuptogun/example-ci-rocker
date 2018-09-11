<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Me extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('bookings_model', 'bookings');
		$currentUser = $this->users->currentUser();
		$this->data = returnAppData($currentUser);
		$this->data['site']['name']['full'] = '내 정보 수정';
		$this->data['site']['bodyClass'] = ['me'];
	}

	public function index()
	{
		$data = $this->data;
		$data['myBookings'] = $this->bookings->by($data['user']->id, true);
		returnView('me/index', $data);
	}

	public function update($field)
	{
		$data = $this->data;
		$userId = $data['user']->id;

		if (!$this->input->post() || !$userId) return false;
		$inputs = $this->input->post();
		$result = false;
		$messages = ['뭔가 문제가 발생했습니다.'];
		$redirect = null;

		switch ($field) {

			case 'email':
				if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
					$messages = ['유효하지 않은 이메일입니다. 다시 시도해 주세요.'];

				} else if (!$this->users->isNew('email', $inputs['email'])) {
					$messages = ['이미 사용 중인 이메일입니다.'];

				} else {
					$result = $this->users->update($inputs, $userId);
					if (!$result) {
						$messages = ['죄송합니다. 다시 시도해 주세요.'];

					} else {
						$messages = ['이메일을 변경했습니다. 보안을 위해 다시 로그인해 주세요.'];
						$redirect = site_url('logout');
					}
				}
				break;

			case 'password':
				if (strlen($inputs['password1']) < 4) {
					$messages = ['비밀번호 길이가 너무 짧습니다.'];

				} else if ($inputs['password1'] !== $inputs['password2']) {
					$messages = ['비밀번호 확인을 잘못 입력하셨습니다.'];

				} else if (password_verify($inputs['password2'], $data['user']->password)) {
					$messages = ['현재 비밀번호와 다른 비밀번호를 사용해 주세요.'];

				} else {
					$result = $this->users->update(['password' => $inputs['password2']], $userId);
					if (!$result) {
						$messages = ['죄송합니다. 서버 오류가 났습니다. 다시 시도해 주세요.'];

					} else {
						$messages = ['비밀번호를 변경했습니다. 보안을 위해 다시 로그인해 주세요.'];
						$redirect = site_url('logout');
					}
				}
				break;
			
			default:
				$result = false;
				$messages = ['잘못된 접근입니다.'];
				break;
		}
		echo json_encode(['result' => $result, 'messages' => $messages, 'redirect' => $redirect]);
	}
}
