<div class="container h-100">
	<div class="row d-flex h-100 align-items-center">
		<div class="col text-center">
			<h1><?= $site['name']['full'] ?></h1>
<?php if (!$user) { ?>
			<p>
				<a href="<?= site_url('login') ?>" class="btn btn-primary">로그인으로 시작하기</a>
			</p>
<?php } else { ?>
			<p><?= $user->username ?>님 안녕하세요?</p>
			<p>
				<a href="<?= site_url('bookings') ?>" class="btn btn-primary">예약 관리</a> <a href="<?= site_url('me') ?>" class="btn btn-secondary">내 계정</a>
			</p>
<?php } ?>
		</div>
	</div>
</div>