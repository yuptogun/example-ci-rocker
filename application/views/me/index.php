<div class="container pt-3">
	<div class="row">
		<div class="col">
			<h1><?= $user->username ?>님의 공간 <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger btn-sm">로그아웃</a></h1>
			<hr />
<?php if (count($myBookings) > 0) : ?>
			<p>앞으로 <?= count($myBookings) ?>가지 예약 일정이 <?= $user->username ?>님을 기다리고 있습니다. <a href="<?= site_url('bookings') ?>" class="btn btn-sm btn-primary">예약 보기</a></p>
<?php else : ?>
			<p>다가오는 일정이 없습니다. <a href="<?= site_url('bookings/create') ?>" class="btn btn-sm btn-primary">예약하기</a></p>
<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6"><?php /*

		딱히 CI가 제공하는 폼 헬퍼를 쓰지 않아도 됩니다.
		여기서는 ajax로 처리를 해봅니다.

		*/ ?>
			<legend>이메일 변경</legend>
			<form class="form-inline form-ajax" method="POST" id="updateEmail" data-api="<?= site_url('me/update/email') ?>">
				<div class="errors d-block w-100"></div>
				<label class="sr-only" for="email">새 이메일</label>
				<input type="email" name="email" id="email" class="form-control mr-2 mb-2" value="<?= set_value('email', $user->email); ?>" style="" placeholder="새 이메일을 입력하시고" / required>
				<button type="submit" class="btn btn-default mb-2">변경</button>
			</form>
		</div>
		<div class="col-sm-6">
			<legend>비밀번호 변경</legend>
			<form class="form-inline form-ajax" method="POST" id="updatePassword" data-api="<?= site_url('me/update/password') ?>">
				<div class="errors d-block w-100"></div>
				<label class="sr-only" for="password1">새 비밀번호</label>
				<input type="password" name="password1" id="password1" class="form-control mr-2 mb-2" value="<?= set_value('password1'); ?>" style="" placeholder="최소 4자" required />
				<label class="sr-only" for="password2">새 비밀번호 재입력</label>
				<input type="password" name="password2" id="password2" class="form-control mr-2 mb-2" value="<?= set_value('password2'); ?>" style="" placeholder="앞의 것과 똑같이" required />
				<button type="submit" class="btn btn-default mb-2">변경</button>
			</form>
		</div>
	</div>
</div><?php

// ajax 폼은 대충 이런 식으로 할 수 있습니다.
// POST를 받는 API는 controllers/Auth.php 에 정의돼 있습니다.

?>
<script type="text/javascript">
(function($){
	$('.form-ajax').each(function () {
		var form = $(this)
		$(this).submit(function (e) {
			e.preventDefault()
			var api = $(this).data('api')
			var xhr = new XMLHttpRequest()
			xhr.open('POST', api, true)
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8')
			xhr.onload = function () {
				if (xhr.status >= 200 && xhr.status < 400) {
					var res = JSON.parse(xhr.response)
					if (res.result == true) {
						alert(res.messages[0])
						if (res.redirect.length) {
							window.location.href = res.redirect
						}
					} else {
						if (typeof res.messages !== 'undefined') {
							var message = ''
							$.each(res.messages, function (i, data) {
								message += '<p><small>'+data+'</small></p>'
								form.find('.errors').html(message)
							})
						}
					}
				} else {
					console.log('서버 오류')
				}
			}
			xhr.onerror = function () {
				console.log('서버/클라이언트 오류')
			}
			xhr.send($(this).serialize())
		})
	})
})(jQuery)
</script>