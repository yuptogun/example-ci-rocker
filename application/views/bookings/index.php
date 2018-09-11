<div class="container pt-3">
	<div class="row">
		<div class="col">
			<h2>예약 관리</h2>
			<p class="lead">내가 신청한 예약들을 관리합니다.</p>
<?php if ($bookings) : ?>
			<div id="bookingsCalendar"></div>
			<script type="text/javascript">
			$(function(){
				var myBookings = '<?= json_encode($bookings) ?>'
				$('#bookingsCalendar').fullCalendar({
					locale: 'ko',
					timezone: 'Asia/Seoul',
					timeFormat: 'H:mm',
					titleFormat: 'YYYY년 MMMM',
					themeSystem: 'bootstrap4',
					customButtons: {
						addBooking: {
							text: '추가',
							click: function () {
								window.location.href = '<?= site_url('bookings/create') ?>'
							}
						}
					},
					header: {
						left:   'prev,next today',
						center: 'title',
						right:  'addBooking'
					},
					events: JSON.parse(myBookings),
					eventClick: function (calEvent, jsEvent, view) {
						window.location.href = '<?= site_url('bookings/update') ?>/' + calEvent.id
					}
				})
			})
			</script>
<?php else : ?>
			<p class="text-center">음? 예약을 안 하셨군요.</p>
			<p class="text-center"><a href="<?= site_url('bookings/create') ?>" class="btn btn-primary btn-lg"><i class="fas fa-plus"></i> 예약하기</a></p>
<?php endif; ?>
		</div>
	</div>
</div>