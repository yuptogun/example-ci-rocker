<div class="container pt-3">
	<div class="row">
		<div class="col">
			<h1><?= $site['name']['full'] ?></h1>
			<p>여기는 Slim API가 돌고 있지 않으면 작동하지 않습니다. <a href="<?= $this->config->item('api') ?>status" target="_blank" class="btn btn-primary btn-sm">API 상태 확인</a></p>
		</div>
	</div>
</div>