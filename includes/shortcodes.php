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
    wp_register_script( 'bootstrap-calendar', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'bootstrap-calendar' );
    return $this->layout_render('pagina', 'conteudo');
  }

  // Renderiza o Shortcode [sisem-destaque]
  public function shortcode_destaque( $params ) {
    return $this->layout_render('bloco', 'conteudo');
  }
}

?>
