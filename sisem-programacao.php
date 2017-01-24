<?php
/*
Plugin Name: SISEM-SP - Programação
Description: Plugin para integração da API Mapas Culturais com a Programação dos Eventos
Version: 1.0.0
Author: Hans Bonini / Inova House
Author URI: http://github.com/hansbonini
*/

class SISEM_Programacao {

  // Monta o Plugin
  public function __construct() {
    add_action( 'init', array( $this, 'shortcodes' ) );
    add_action(' init', array( $this, 'initialize' ), 0);
  }

  // Inicializa o Plugin
  public function initialize() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }

  // Ativa os Shorcodes para embutir nas páginas do sistema
  public function shortcodes() {
    $plugin_path = constant( 'WP_PLUGIN_DIR' );
    $include_path = "$plugin_path/sisem-programacao/includes/shortcodes.php";
    if (file_exists($include_path)) {
      require_once $include_path;
      new SISEM_Shortcodes();
    }
    else {
      exit;
    }
  }

}

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly.

new SISEM_Programacao();
