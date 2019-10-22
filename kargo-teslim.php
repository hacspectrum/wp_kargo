<?php
/*
Plugin Name: Kargo Teslim Onayı
Plugin URI: http://temsilci.com
Description: Kargoya teslim edilecek kargoların barkodu okutularak sistemden düşürülmesi
Version: 1.0
Author: Anıl ERGÜL
Author URI: https://github.com/hacspectrum/
License: GNU
*/


add_action('admin_menu', 'func_kargo_teslim_onayi');
function func_kargo_teslim_onayi(){
 add_menu_page('Kargo Teslim Onayı','Barkodlu Kargo Teslim', 'manage_options', 'kargo-teslim', 'func_kargo_teslim_html');
}

function page_tabs( $current = 'first' ) {
    $tabs = array(
        'teslim'   => __( 'Teslim Edilecek', 'plugin-textdomain' ), 
        'iade'  => __( 'İade Edilecek', 'plugin-textdomain' )
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page=kargo-teslim&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}

function func_kargo_teslim_html(){
	global $wpdb;
	if(isset($_POST["txtBarkod"])){
		$barkod=explode("\n",$_POST['txtBarkod']);
		
			if(count($barkod)>1){
				foreach($barkod as $v){
					$wpCode=$wpdb->get_results("SELECT * FROM wpun_posts WHERE post_status='wc-awaiting-shipment' AND ID=".esc_attr($v));
					if(is_object($wpCode[0])){
						$order = new WC_Order($wpCode[0]->ID);
						$order->update_status('shipped');
					}
				}
				echo '<div class="updated settings-error notice is-dismissible"> 
	<p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">İşlem başarıyla gerçekleşmiştir.</em> .</span>
	</strong></p><button type="button" class="notice-dismiss"></button></div>';
			}else{
				$wpCode=$wpdb->get_results("SELECT * FROM wpun_posts WHERE ID=".$_POST['txtBarkod']);
				if(is_object($wpCode[0])){
					$order = new WC_Order($wpCode[0]->ID);
					$order->update_status('shipped');
					echo '<div class="updated settings-error notice is-dismissible"> 
	<p><strong><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">İşlem başarıyla gerçekleşmiştir.</em> .</span>
	</strong></p><button type="button" class="notice-dismiss"></button></div>';
				}
			}
		}
	}
	
	echo '
	<!-- tab menu baslangic -->
	';
	$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'teslim';
	page_tabs( $tab );
	
	
	echo '
	<form method="post">';
	wp_nonce_field('func_kargo_teslim_update','func_kargo_teslim_update');
	if ( $tab == 'teslim' ) {
		echo '<input type="hidden" name="tip" value="'.esc_attr($tab).'">';
	}else{
		echo '<input type="hidden" name="tip" value="'.esc_attr($tab).'">';
	}
	echo '
		<div class="row clearfix">
				<div class="col-sm-6">
					<div class="form-group"> <textarea name="txtBarkod" rows="25" cols="30" class="form-control"></textarea>
				</div>
			</div>
		</div>';
	submit_button();
		echo'
	</form>';
	
	
	
	
}

function func_kargo_teslim_update(){
	
}