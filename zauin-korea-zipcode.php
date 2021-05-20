<?php
/*
* Plugin Name: Zauin Korean Zipcode
* Plugin URI: http://tera.co.kr/
* Description: WP-Members 플러그인에 우편번호 필드 옆에 "찾기"버튼을 생성합니다.
* Author: zauin (zauin@tera.co.kr)
* Author URI: Http://tera.co.kr
* Version:0.6
* Date Created : 2016/01/25
* Date Modified : 2021/05/21
*/

add_filter( 'wpmem_register_form', 'my_register_form_filter', 2);

function my_register_form_filter( $string ) {
	$org_str = '우편번호';
	$rep_str = '우편번호 <input type="button" id="zipcode_search" value="찾기" class="zip_btn" onclick="openDaumPostcode();">';
	$string = str_replace( $org_str, $rep_str, $string );
	return $string;

}

add_action('init','zauin_address_start');

function zauin_address_start(){
	wp_enqueue_script( 'postcode', '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', array(), null, true );
	add_action('wp_enqueue_scripts', 'zauin_wp_enqueue_scripts');
	function zauin_wp_enqueue_scripts() { ?>
		<script type="text/javascript">
			function openDaumPostcode() {
				new daum.Postcode({
					oncomplete: function(data) {
						// document.getElementById('billing_postcode').value = data.zonecode;
						// document.getElementById('billing_address_1').value = data.address;
						// document.getElementById('billing_address_2').focus();

						// console.log('position: ' + position)
						// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

						// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
						// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
						// 도로명 주소 변수
						let fullRoadAddr = data.roadAddress
						let extraRoadAddr = '' // 도로명 조합형 주소 변수

						var displayName = '' // 지도에 보여줄 이름

						// 법정동명이 있을 경우 추가한다. (법정리는 제외)
						// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
						if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
							extraRoadAddr += data.bname
							// displayName = data.bname
						}
						// 건물명이 있고, 공동주택일 경우 추가한다.
						if (data.buildingName !== '' && data.apartment === 'Y') {
							extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName)
							displayName = data.buildingName
						}
						// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
						if (extraRoadAddr !== '') {
							extraRoadAddr = ' (' + extraRoadAddr + ')'
						}
						// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 "추가"한다.
						if (fullRoadAddr !== '') {
							fullRoadAddr += extraRoadAddr
						}
						// console.log(displayName)
						if (displayName === '') {
							// console.log('========== displayName ===');
							// console.log(data.address.replace(data.sido + " ", "").replace(data.sigungu + " ", ""))
							displayName = data.address.replace(data.sido, "").replace(data.sigungu, "")
						}
						document.getElementById('billing_postcode').value = data.zonecode;
						document.getElementById('billing_address_1').value = fullRoadAddr
						document.getElementById('billing_address_2').focus();

					}
				}).open();
			}
		</script>
	<?php
	}
}
