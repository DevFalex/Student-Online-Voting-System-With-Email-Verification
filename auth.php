<?php
if( defined('RESTRICTED')){
	if(!isset($_SESSION['data']) && empty($_SESSION['data'])){
		header('location: ../admin/'); 
	}
} else {
	if ( defined('SEND_TO_HOME') && isset( $_SESSION['data'] ) ) {
      header( 'Location: ../admin/admin.php' ); 
    }    
}
?>