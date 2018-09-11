# CI CMS 예제 애플리케이션

이 레포는 '일정 예약 관리 툴'의 형식으로, JWT 사용자 인증 및 CodeIgniter MVC 패턴의 예시를 보여줍니다.

2018 Written by Eojin K. Commissioned by @[rocker76](https://github.com/rocker76)

## 구현되어 있는 것
- hooks과 JWT을 이용한 사용자 인증 (로그인/로그아웃)
- 기본적인 CodeIgniter MVC 패턴 예제
- 기본적인 테이블 CRUD

## 빠른 시작
1. 의존성을 설치하세요. `composer require`
2. MySQL의 `example` 스키마를 만들고 여기 접근 권한이 있는 `example` 계정의 비밀번호를 `example`로 지정하세요.
3. `dump.sql`을 실행해서 마이그레이션 + 시딩을 합니다.
4. `start-ci.bat`을 실행하면 PHP 내장 서버가 `localhost:7200`에 시작되고 브라우저가 열립니다.
5. 로그인을 해보세요. 계정은 ` admin@admin.com `, 비밀번호는 `password`
6. 뭔가 일정을 예약하거나 이메일, 비밀번호를 변경해 보세요!

## `libraries/JWTAuth.php`는 어떻게 작동하는가?

1. 사용자가 최초 로그인을 하면, 사용자 고유 정보를 포함한 JWT을 생성해 그 값을 응답의 `Authorization` 헤더와 `token_example_ci` 쿠키에 넣어서 돌려줍니다.
2. 사용자 인증을 해야 하는 라우트가 호출되면, 그 라우트를 담당하는 컨트롤러가 construct됩니다. 그 직후 `config/hooks.php`의 설정에 따라 `authenticate()` 메소드가 실행됩니다.
3. 이 메소드는, 해당 라우트가 호출될 당시 사용자가 보낸 HTTP 요청의  헤더에 값이 있었는지, 혹은 사용자의 `token_example_ci` 쿠키에 값이 있는지 확인합니다.
4. 그 값이 없거나 유효한 JWT로 디코딩되지 않으면 인증하지 않습니다. 디코딩에 성공했을 경우 그 안에는 사용자 고유 정보가 있으므로, 그것을 이용해서 지금 로그인한 사용자를 특정하고 원하는 작업을 합니다.

## 사용권

[MIT License](https://www.olis.or.kr/license/Detailselect.do?lId=1006)