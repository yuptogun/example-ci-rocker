<?php

// 템플릿 본문 파일명, 전체 뷰에 사용할 자료 배열, 템플릿명을 넣으면 뷰를 만들어 줍니다.
function returnView($bodyName, $data, $templateName = 'bootstrap')
{
	// CI의 뷰 로딩 클래스를 써야 해서 인스턴스 사본을 가져옵니다.
	$CI =& get_instance();

	// CI는 기본적으로 템플릿 엔진이 없습니다.
	// 그래서 모든 뷰에 공통으로 쓰이는 css, js를 로딩하고 싶을 때 좀 곤란합니다.
	// 대부분의 경우 <body> 태그 앞을 header, 뒤를 footer 파일로 별도의 뷰 파일로 빼놓은 다음 순서대로 로딩하는 방식으로 처리합니다.
	// 여기서는 이걸 아예 메소드로 만들어서 처리해 볼까 합니다.
	$CI->load->view('templates/'.$templateName.'/0_header.php', $data);
	$CI->load->view($bodyName, $data);
	$CI->load->view('templates/'.$templateName.'/9_footer.php', $data);
}