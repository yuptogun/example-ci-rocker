<div class="container pt-3">
	<div class="row">
		<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
			<h1>
				<?= $site['name']['full'] ?><?php if (isset($booking)) { ?>
				<a href="#" onClick="confirmToGo('<?= site_url('bookings/delete/'.$booking->id) ?>')" class="btn btn-sm btn-danger">예약 취소</a>
				<?php } ?>
			</h1>
			<?= form_open('bookings/'.(isset($booking) ? 'update/'.$booking->id : 'create'), '', [
				'booked_by' => $user->id,
			]) ?>
				<fieldset class="form-group">
					<div class="row">
						<legend class="col-form-label col-sm-3 pt-0">예약 타입</legend>
						<div class="col-sm-9">
<?php foreach ($form['types'] as $type) : ?>
							<div class="form-check">
								<?= form_radio('type', $type->code, (isset($booking) && $type->code == $booking->type), [
									'id' => 'type_'.$type->code,
									'class' => 'form-check-input',
								]) ?>
								<?= form_label($type->name, 'type_'.$type->code) ?>
							</div>
<?php endforeach; ?>
						</div>
					</div>
				</fieldset>
				<div class="form-group row">
					<?= form_label('예약명', 'title', [
						'class' => 'col-sm-3 col-form-label'
					]) ?>
					<div class="col-sm-9">
						<?= form_input('title', set_value('title', isset($booking) ? ($booking->title ?? '') : ''), [
							'id' => 'title',
							'class' => 'form-control mb-3',
							'placeholder' => '행사명 등을 입력하세요.',
							'autocomplete' => 'off'
						]) ?>
					</div>
				</div>
				<div class="form-group row">
					<?= form_label('시작 일시', 'starts_at', [
						'class' => 'col-sm-3 col-form-label'
					]) ?>
					<div class="col-sm-9">
						<?= form_input('starts_at', set_value('starts_at', isset($booking) ? $booking->starts_at : date('Y-m-d H:i')), [
							'id' => 'starts_at',
							'class' => 'form-control mb-3',
							'placeholder' => '날짜와 시각을 지정하세요.',
							'autocomplete' => 'off'
						]) ?>
						<script type="text/javascript">
							$(function(){
								$('#starts_at').datetimepicker({
									format: 'YYYY-MM-DD HH:mm',
									dayViewHeaderFormat: 'YYYY년 M월',
									minDate: moment().startOf('day'),
									inline: true,
									sideBySide: true
								})
								$('#starts_at').on('change.datetimepicker', function (e) {
									$('#ends_at').datetimepicker('minDate', e.date.add(30, 'm'))
								})
							})
						</script>
					</div>
				</div>
				<div class="form-group row">
					<?= form_label('종료 일시', 'ends_at', [
						'class' => 'col-sm-3 col-form-label'
					]) ?>
					<div class="col-sm-9">
						<?= form_input('ends_at', set_value('ends_at', isset($booking) ? $booking->ends_at : null), [
							'id' => 'ends_at',
							'class' => 'form-control mb-3',
							'placeholder' => '시작 일시에서 최소 30분 뒤로 지정하세요.',
							'autocomplete' => 'off'
						]) ?>
						<script type="text/javascript">
							$(function(){
								$('#ends_at').datetimepicker({
									format: 'YYYY-MM-DD HH:mm',
									dayViewHeaderFormat: 'YYYY년 M월',
									inline: true,
									sideBySide: true
								})
							})
						</script>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<?= validation_errors(); ?>
						<?= form_submit(null, (isset($booking) ? '수정' : '예약하기'), [
							'class' => 'btn btn-primary'
						]) ?>
						<a href="<?= site_url('bookings') ?>" class="btn btn-secondary">취소</a>
					</div>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>