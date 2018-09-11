<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	// MVC란 무엇인가?
	// 화면 하나 띄우는 과정을 Model, View, Controller 3개로 나누어 쓰는 구조를 MVC라고 부릅니다.

	// 지금 보고 있는 것은 controller입니다.
	// 컨트롤러는 모델과 뷰를 컨트롤합니다.
	// 즉, 요청이 들어오면 (필요할 때) 모델에 자료를 요구하고, 그 자료를 뷰에 포함하여 응답하게 합니다.
	// 따라서 모든 메소드는 뷰 처리하는 것 한 번, (필요하면) DB 붙어서 모델 가져오는 것 한 번이면 충분합니다.
	// 하지만 좀더 예쁘게 체계적으로 만들 필요가 있으니 생성자, 헬퍼, 라이브러리, 유사 템플릿 구조 등을 이용해 보기로 합니다.
	// (사실 "프레임워크"가 하는 일이란 바로 이런 유틸리티를 제공하는 것)

	// 모든 메소드에 대해서 미리 실행해야 할 것이 있다면 생성자에서 처리합니다.
	public function __construct()
	{
		parent::__construct();

		// 일단 모델을 가져옵니다.
		// 두번째 변수는 약칭입니다. 이걸 안 넣으면 $this->첫번째변수->메소드() 형식으로 써야 합니다.
		$this->load->model('users_model', 'users');
		$this->load->model('bookings_model', 'bookings');

		// 혹시 지금 로그인시킬 수 있는 사용자가 있는지 봐서 가져옵니다.
		$currentUser = $this->users->currentUser();

		// 응답용 자료 배열을 만들어둡니다.
		// 이 함수는 helpers/app_helper.php 에 정의돼  있으며 이걸 쓸 수 있는 이유는 config/autoload.php 에서 오토로딩하고 있기 때문입니다.
		$this->data = returnAppData($currentUser);

		// 자료 배열을 적당히 맞춤 설정합니다.
		$this->data['site']['menu'] = null;
		$this->data['site']['bodyClass'] = ['welcome'];
		$this->data['site']['extra']['head'] = [
			'<style>html, body.welcome {height: 100%; background-color: #fafafa;}</style>'
		];
	}

	// 라우트에서 별도로 정의하지 않으면 index 메소드를 실행합니다.
	public function index()
	{
		// 생성자에서 만든 응답용 기본 배열을 가져옵니다.
		$data = $this->data;

		// 이제 뷰가 그 데이터를 포함해 응답을 돌려줄 수 있습니다.
		// 이 경우에는 views/welcome/index.php 파일을 찾으세요.

		// returnView 메소드는 helpers/view_helper.php 에 있습니다.
		returnView('welcome/index', $data);
	}

	// 라우트에서 404_override를 이 메소드로 정의했습니다.
	public function notFound()
	{
		$data = $this->data;
		$data['site']['extra']['head'] = [
			'<style>html, body.welcome {height: 100%;}</style>'
		];
		$data['site']['bodyClass'] = ['h-100', 'bg-dark', 'text-white'];
		returnView('welcome/notfound', $data);
	}
}
