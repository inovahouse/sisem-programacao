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
    // Filters
    add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rule_programacao') );
    add_filter( 'query_vars', array( $this, 'rewrite_vars_programacao' ) );
    add_filter( 'init', array( $this, 'rewrite_flush' ) );

    // Actions
    add_action( 'init', array( $this, 'shortcodes' ) );
    add_action(' init', array( $this, 'initialize' ), 0);
  }

  // Inicializa o Plugin
  public function initialize() {
    // void
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

  public function rewrite_flush() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }

  public function rewrite_rule_programacao( $rules ) {
    global $wp_rewrite;
    $newRule = array('programacao/(.+)' => 'index.php?pagename=programacao&evento='.$wp_rewrite->preg_index(1));
    $newRules = $newRule + $rules;
    return $newRules;
  }

  public function rewrite_vars_programacao( $vars ) {
    $vars[] = 'evento';
    return $vars;
  }

}

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly.

new SISEM_Programacao();
