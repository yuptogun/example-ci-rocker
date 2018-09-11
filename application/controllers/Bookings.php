<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookings extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('bookings_model', 'bookings');
		
		$currentUser = $this->users->currentUser();
		$this->data = returnAppData($currentUser);
		$this->data['site']['extra']['head'] = [
			'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">',
			'<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>',
			'<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/ko.js"></script>',
			'<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>',
			'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />',
			'<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>',
			'<style>.fc-event {cursor: pointer;} .fc-day-grid-event .fc-content {white-space: normal;}</style>',
			'<script>
			var confirmToGo = function (where, areYouSure) {
				if (typeof areYouSure == "undefined" || !areYouSure.length) {
					areYouSure = "정말로 실행하시겠습니까?"
				}
				var confirmed = confirm(areYouSure)
				if (confirmed) {
					window.location.href = where
				} else {
					return
				}
			}
			</script>'
		];
	}

	// 기본적인 CRUD 의 R에 해당합니다.
	public function index()
	{
		// 기본 자료 가져오고
		$data = $this->data;
		$data['site']['name']['full'] = '예약 관리';

		// bookings 모델에서 더 필요한 자료를 더 가져와서
		$data['bookings'] = $this->bookings->by($data['user']->id, false, true);
		// var_dump($data['bookings']); exit;

		// 뷰에 넘깁니다.
		// 이게 MVC의 기본입니다.
		// 실제 DB를 읽고 쓰는 것은 모두 모델이 합니다.
		returnView('bookings/index', $data);
	}

	public function create()
	{
		$data = $this->data;
		
		if (!isset($this->data['booking'])) $data['site']['name']['full'] = '새 예약';
		$data['form']['types'] = $this->types->availables();

		$rules_starts_at = isset($this->data['booking']) ?
			'required' : 'required|callback_bookable_starts_at';
		$rules_ends_at = isset($this->data['booking']) ?
			'required' : 'required|callback_bookable_ends_at['.$this->input->post('starts_at').']';

		$this->form_validation->set_rules('type', '예약 타입', 'required', [
			'required' => '%s은 필수입니다.',
		]);
		$this->form_validation->set_rules('starts_at', '시작 일시', $rules_starts_at, [
			'required' => '%s는 필수입니다.',
			'bookable_starts_at' => '%s는 현재 시각 이후로 설정하세요.',
		]);
		$this->form_validation->set_rules('ends_at', '종료 일시', $rules_ends_at, [
			'required' => '%s는 필수입니다.',
			'bookable_ends_at' => '%s는 시작 일시와 최소 30분 차이가 나야 합니다.',
		]);

		if ($this->form_validation->run() == FALSE) {
			returnView('bookings/form', $data);

		} else {
			$bookingId = isset($data['booking']) ? $data['booking']->id : null;
			$booked = $this->bookings->book($data['user']->id, $this->input->post(), $bookingId);
			if (!$booked) {
				$this->form_validation->set_message('booking_failed', '예약을 못했습니다. 죄송하지만 재시도 부탁드립니다.');
				returnView('bookings/form', $data);
			} else {
				redirect('/bookings', true);
			}
		}
	}

	public function update($id)
	{
		$booking = $this->bookings->check(['id' => $id]);
		if (count($booking) == 0) redirect('bookings');
		$this->data['booking'] = $booking[0];

		$this->data['site']['name']['full'] = ($booking[0]->title ?? $booking[0]->id.'번 예약 ').' 수정';
		$this->create();
	}

	public function delete($id)
	{
		$deleted = $this->bookings->cancel($id);
		if ($deleted) {
			redirect('bookings');
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function bookable_starts_at($datetime)
	{
		return time() < strtotime($datetime);
	}
	public function bookable_ends_at($end, $start)
	{
		return strtotime($end) >= strtotime('+30 minutes', strtotime($start));
	}
}
