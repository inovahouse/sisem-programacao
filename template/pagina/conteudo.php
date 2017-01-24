<!-- Cnntaine Principal da Programação -->
<div id="sisem-programacao" class="container-fluid">

  <!-- Container da Sibebar Lateral -->
  <div id="sisem-programacao-lateral" class="col-md-3 pull-left">
    <div class="container-fluid">
      <h3 class="titulo">Navegue</h3>
      <div class="row">
        <div class="col-md-12">
          <p class="descricao">Consulte a agenda de Programação Cultural</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 form-group">
          <label for="museu">Por Museu:</label>
          <select name="museu">
            <option>Teste</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 form-group">
          <label for="linguagem">Por Linguagem:</label>
          <select name="linguagem">
            <option>Teste</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 form-group">
          <label>Por Data:</label>
          <div class="data-selecionada"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 form-group">
          <button class="btn btn-lg btn-danger">BUSCAR</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Container do Conteúdo -->
  <div id="sisem-programacao-conteudo" class="col-md-9 pull-right">
    <ul class="listagem-de-eventos">
      <!--
      <li class="evento">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">
              <img class="img-rounded" />
            </div>
            <div class="col-md-9">
              <h3 class="titulo">TESTE</h3>
              <p class="descricao">DESCRIÇÃO</p>
              <p class="saiba-mais"><a href="#">Saiba Mais</a></p>
            </div>

          </div>
        </div>
      </li>-->
    </ul>
  </div>

</div>

<!-- Javascript da Página -->
<script type="text/javascript">
  jQuery(window).ready(function($) {
    // Efetua a Requisição dos Eventos em geral à partir da Data Atual
    $('#sisem-programacao-lateral div.data-selecionada').datepicker({
        language: "pt-BR",
        orientation: "auto left"
    });
    $.getJSON(
      'http://spcultura.prefeitura.sp.gov.br/api/event/findByLocation',
      {
        '@from': '<?= date("Y-m-d") ?>',
        '@to': '<?= date("Y-m-d") ?>',
        '@select': 'id,name,location,shortDescription',
        '@files': '(avatar.avatarMedium):url',
        '@order': 'name ASC'
      },
      function (response){
        console.log(response);
        $.each(response, function(k,v) {
          var eventoID = 'evento-'+v.id;
          var html = '<div class="row"><div class="col-md-3"><img id="imagem" class="img-rounded" /></div><div class="col-md-9"><h3 id="titulo" /><p id="descricao" /><p id="saiba-mais" /></div></div>';

          // Cria a Entrada do Evento
          jQuery('<li />', {
            id: eventoID
          }).appendTo('#sisem-programacao-conteudo ul.listagem-de-eventos');
          jQuery('#'+eventoID).html(html);

          // Insere a Imagem do Evento
          if (typeof v['@files:avatar.avatarMedium'] != 'undefined') {
            jQuery('#'+eventoID+' #imagem').attr('src', v['@files:avatar.avatarMedium'].url);
          }
          // Insere o Título do Evento
          jQuery('#'+eventoID+' #titulo').text(v.name);

          // Insere a Descrição do Evento
          if (typeof v['shortDescription'] != 'undefined') {
            jQuery('#'+eventoID+' #descricao').text(v.shortDescription);
          }

          // Insere a Descrição do Evento
          jQuery('#'+eventoID+' #saiba-mais').html('<a href="<?= home_url('/programacao/evento')?>/'+v.id+'-'+encodeURI(v.name)+'">Saiba Mais</a>');

        });
      }
    );
  });
</script>
