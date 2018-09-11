<div class="container h-100">
	<div class="row d-flex h-100 align-items-center">
		<div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
			<?= form_open('login') ?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<h1>로그인하세요.</h1>
					</div>
				</div>
				<div class="form-group row">
					<?= form_label('이메일', 'email', [
						'class' => 'col-sm-3 col-form-label'
					]) ?>
					<div class="col-sm-9">
						<?= form_input('email', set_value('email'), [
							'class' => 'form-control',
							'placeholder' => 'foo@bar.com',
						]) ?>
					</div>
				</div>
				<div class="form-group row">
					<?= form_label('비밀번호', 'password', [
						'class' => 'col-sm-3 col-form-label'
					]) ?>
					<div class="col-sm-9">
						<?= form_password('password', set_value('password'), [
							'class' => 'form-control',
							'placeholder' => '최소 4자',
						]) ?>
					</div>
				</div>
				<!-- <div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<?= form_checkbox('remember', set_checkbox('remember', 'on'), [
							'class' => 'form-check-input',
						]) ?>
						<?= form_label('자동 로그인', 'remember', [
							'class' => 'form-check-label'
						]) ?>
					</div>
				</div> -->
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<?= validation_errors(); ?>
						<?= form_submit('submit', '로그인', [
							'class' => 'btn btn-primary'
						]) ?>
					</div>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>