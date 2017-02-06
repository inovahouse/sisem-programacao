<?php
class SISEM_Shortcodes {

  public function __construct() {
    add_shortcode( 'sisem-programacao', array($this, 'shortcode_programacao' ) );
    add_shortcode( 'sisem-destaque', array($this, 'shortcode_destaque' ) );
  }

  // Renderiza o Layout especificado
  public function layout_render( $group, $layout ) {
    $plugin_path = constant( 'WP_PLUGIN_DIR' );
    $template_path = "$plugin_path/sisem-programacao/template/$group/$layout.php";
    if (file_exists($template_path)) {
      ob_start();
      require_once $template_path;
      $html = ob_get_contents();
      ob_get_clean();
      return $html;
    }
    return '';
  }

  // Renderiza o Shortcode [sisem-programacao]
  public function shortcode_programacao( $params ) {
    $js_path = plugins_url().'/sisem-programacao/js';
    $css_path = plugins_url().'/sisem-programacao/css';
    wp_register_script( 'bootstrap-calendar', $js_path.'/bootstrap-datepicker.min.js', array( 'jquery' ), '', true );
    wp_register_script( 'datatables', $js_path.'/jquery.dataTables.min.js', array(), '', true );
    wp_register_style( 'datatables-css', $css_path.'/jquery.dataTables.min.css');
    wp_register_style( 'sisemsp-programacao', $css_path.'/sisemsp-programacao.css');
    wp_enqueue_script( 'bootstrap-calendar' );
    wp_enqueue_script( 'datatables' );
    wp_enqueue_style( 'datatables-css' );
    wp_enqueue_style( 'sisemsp-programacao' );
    return $this->layout_render('pagina', 'conteudo');

  }

  // Renderiza o Shortcode [sisem-destaque]
  public function shortcode_destaque( $params ) {
    return $this->layout_render('bloco', 'conteudo');
  }
}

?>
