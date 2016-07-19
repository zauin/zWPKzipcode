<?php
/*
* Plugin Name: Korean ZipCode with WP-Members
* Plugin URI: http://tera.co.kr/main/?p=216
* Description: WP-Members 플러그인과 함께 작동하며 Daum 우편번호 검색 API를 이용하여 우편번호를 검색할 수 있습니다.
* Author: zauin
* Author URI: Http://tera.co.kr
* Version:0.5
*/ 

add_filter( 'wpmem_register_form', 'my_register_form_filter', 2);

function my_register_form_filter( $string ) {
	$org_str = '우편번호';
	$rep_str = '우편번호 <input type=button id=zipcode_search value=찾기 class=zip_btn onclick=openDaumPostcode();>';
	$string = str_replace( $org_str, $rep_str, $string );
	return $string;

}  

add_action('init','zauin_address_start');  

function zauin_address_start(){  
	wp_enqueue_script( 'postcode', 'http://dmaps.daum.net/map_js_init/postcode.v2.js', array(), null, true ); 
	add_action('wp_enqueue_scripts', 'zauin_wp_enqueue_scripts'); 
	function zauin_wp_enqueue_scripts() { ?>
		<script type="text/javascript">
			function openDaumPostcode() {
				new daum.Postcode({
					oncomplete: function(data) {
						document.getElementById('zip').value = data.zonecode;
						document.getElementById('addr1').value = data.address;
						document.getElementById('addr2').focus();
					}
				}).open();
			}
		</script>
 	<?php
	}
} 
