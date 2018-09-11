<?php /*

body 태그가 열리기 직전까지를 처리합니다.

1. 부트스트랩은 4를 쓰기로 합니다.
   https://getbootstrap.com/docs/4.0/getting-started/introduction/
2. 기본적으로 $site 배열이 넘어온다고 가정하지만 값이 없을 때를 처리해야 합니다.
3. <?= 'foo' ?> 는 <?php echo 'foo'; ?>와 동일합니다.

*/ ?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<title><?= $site['name']['full'] ?> :: <?= $site['name']['short'] ?></title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" integrity="sha384-kW+oWsYx3YpxvjtZjFXqazFpA7UP/MbiY4jvs+RWZo2+N94PFZ36T6TFkc9O3qoB" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<?php

// 원래는 public/assets/ 디렉토리 밑에 필요한 css/js를 저장하고 써야 하는데...
// 로컬 환경에서는 public 경로 rewrite를 하기가 번거로워서 우선 inline 스타일링으로 집어넣습니다.

?>
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:300,900&subset=korean" rel="stylesheet">
	<style type="text/css">
		body {
			font-family: 'Noto Sans KR', sans-serif;
		}
		.btn svg[class*=fa-], .btn i[class*=fa-] {
			margin-right: 0.25em;
		}
	</style>
<?php

// 이런 문법이 됩니다.
if (!empty($site['extra']['head'])) foreach ($site['extra']['head'] as $tag) echo $tag;

?>
</head>
<body<?= (isset($site['bodyClass']) && !empty($site['bodyClass'])) ? ' class="'.implode(' ', $site['bodyClass']).'"' : '' ?>>
<?php $this->load->view('templates/bootstrap/1_navbar.php', $this->data) ?>
<?php /*

여기서부터는 다른 어떤 파일이 내용을 만든다고 가정합니다.

*/ ?>