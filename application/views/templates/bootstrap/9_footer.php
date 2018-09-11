<?php /*

body 태그가 닫히기 직전 나와야 하는 것들을 처리합니다.
기본적으로 $site 배열이 넘어온다고 가정하지만 값이 없을 때를 처리해야 합니다.

*/ ?>
<?php if (!empty($site['extra']['body'])) :
	foreach ($site['extra']['body'] as $tag) : echo $tag; endforeach;
endif; ?>
</body>
</html>