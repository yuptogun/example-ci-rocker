<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// CI의 개발/PRD 환경 구분은 매우 어려우니 방안을 찾거나 적당히 고쳐 주세요.
$config['base_url'] = 'http://localhost:7200/';

// Slim으로 구현한 API는 이 주소에서 돌아간다고 가정합니다. 마찬가지로 적당히 고쳐 주세요.
$config['api'] = 'http://localhost:7100/v1/';

// index.php 를 URL에 노출하지 않기 위해 빈값으로 둡니다. 필요한 서버의 rewrite 설정을 해주세요.
$config['index_page'] = '';

// 기본값들
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';

// 인증을 구현해야 해서 훅 사용해야 합니다.
$config['enable_hooks'] = TRUE;

// 컴포저로 설치된 패키지를 자동로딩할지 여부입니다.
// 이 소스에서는 실제로는 index.php 에서 로딩하고 있어서 이 설정이 무의미합니다.
$config['composer_autoload'] = FALSE;

// 기본값들
$config['subclass_prefix'] = 'MY_';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

// 주소에 쿼리스트링을 써야 한다면 이걸 TRUE로 바꾸세요.
// 그 다음 config/routes.php 를 뜯어고치셔야 할 겁니다.
$config['enable_query_strings'] = FALSE;

$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;

// 로깅 관련 설정...
$config['log_threshold'] = 2;
$config['log_path'] = '';
$config['log_file_extension'] = 'log';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';

// 다시 기본값들...
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;

// 이 키는 외부 유출되면 안됩니다.
$config['encryption_key'] = 'QpS76DxzA7cMJiFNsv4Cq1XEZqU5Cftr';

// 세션/쿠키 기본값들...
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = 'sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

// 보안관련 기본 설정들
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE; // 더 이상 안 쓰이는 설정입니다.
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

// 기본값들...
$config['compress_output'] = FALSE;
$config['time_reference'] = 'Asia/Seoul'; // PHP 설정이 우선합니다.
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';