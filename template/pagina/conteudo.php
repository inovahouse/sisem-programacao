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
          <select id="lista-de-museus" name="museu">
            <option>Todos</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 form-group">
          <label for="linguagem">Por Linguagem:</label>
          <select name="linguagem">
            <option>Todos</option>
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
    <table id="eventos"></table>
  </div>

</div>

<!-- Javascript da Página -->
<script type="text/javascript">
  jQuery(window).ready(function($) {

    var eventos = [];
    var museus = [6, 7, 11, 15, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 66, 85, 1444];
    var listagem;
    var args = {
      data: eventos,
      columns : [
        { title: 'ID', visible: false },
        {
          title: 'Eventos da Programação',
          data: null,
          render: function (data, type, row) {
            content = '<div id="evento-'+data[0]+'" "class="container-fluid">';
            content += '<div class="row">';
            content += '<div class="col-md-3"><div class="row"><img src="'+data[1]+'" class="img-rounded"></div></div>';
            content += '<div class="col-md-9">'
            content += '<div class="row"><div class="col-md-12"><h3 id="evento-nome">'+data[2]+'</h3></div></div>';
            content += '<div class="row"><div class="col-md-12"><p id="evento-descricao">'+data[3]+'</p></div></div>'
            content += '<div class="row"><div class="col-md-12"><a id="evento-url" href='+data[4]+'>Saiba Mais</a></div></div>'
            content += '</div></div></div>';
            return content;
          }
        }
      ]
    }

    // Renderiza o Evento na Lista de Eventos
    function renderEvento(evento) {
      var eventoDados = [evento.id];
      // Insere a Imagem do Evento
      if (typeof evento['@files:avatar'] != 'undefined') {
        eventoDados.push(evento['@files:avatar'].url);
      }
      else {
        eventoDados.push('');
      }
      // Insere o Título do Evento
      eventoDados.push(evento.name);
      // Insere a Descrição do Evento
      if (typeof evento['shortDescription'] != 'undefined') {
        eventoDados.push(evento.shortDescription);
      }
      else {
        eventoDados.push('');
      }
      // Insere a Descrição do Evento
      eventoDados.push(evento.singleUrl);
      // Retorna os Dados
      return eventoDados;
    }


    // Renderiza os Museus na Lista de Museus
    function renderMuseus(museu) {
        var item = jQuery('<option />', {
          value: museu.id,
          text: museu.name
        });
        jQuery('#lista-de-museus').append(item);
    }

    // Ativa o plugin de Datepicker para o filtro de Data
    $('#sisem-programacao-lateral div.data-selecionada').datepicker({
        language: "pt-BR",
        orientation: "auto left"
    });

    // Efetua a Requisição dos Museus pré-definidos
    $.getJSON(
      'http://estadodacultura.sp.gov.br/api/space/find/',
      {
        'id': 'IN ('+museus.join()+')',
        '@select': 'id,name,location',
        '@order': 'name ASC',
      },
      function(response) {
        $.each(response, function (k,v) {
          renderMuseus(v);
        });
      }
    );

    // Efetua a Requisição dos Eventos em geral à partir da Data Atual
    $.getJSON(
      'http://estadodacultura.sp.gov.br/api/event/findByLocation',
      {
        '@from': '<?= date("Y-m-d") ?>',
        '@to': '<?= date("Y-m-d") ?>',
        '@select': 'id,name,location,shortDescription,singleUrl',
        '@files': '(avatar):url',
        '@order': 'name ASC',
        'space': 'IN ('+museus.join()+')'
      },
      function (response){
        $.each(response, function(k,v) {
          // Insere o Evento dentro do Array com todos os Eventos
          eventos.push(renderEvento(v));
        });
        // Inicializa a dataTable com a programação
        listagem = $("#eventos").DataTable(args);
      }
    );

    // Adiciona o Filtro por Museu Selecionado
    $(document).on('change', '#lista-de-museus', function(e) {
      e.preventDefault();
      eventos = [];
      valor = $(this).val();
      if (valor == 'Todos') {
        busca = 'IN ('+museus.join()+')';
      }
      else {
        busca = 'EQ ('+valor+')';
      }
      // Efetua a Requisição dos Eventos em geral à partir da Data Atual
      $.getJSON(
        'http://estadodacultura.sp.gov.br/api/event/findByLocation',
        {
          '@from': '<?= date("Y-m-d") ?>',
          '@to': '<?= date("Y-m-d") ?>',
          '@select': 'id,name,location,shortDescription,singleUrl',
          '@files': '(avatar):url',
          '@order': 'name ASC',
          'space': busca
        },
        function (response){
          $.each(response, function(k,v) {
            // Insere o Evento dentro do Array com todos os Eventos
            eventos.push(renderEvento(v));
          });
          // Inicializa a dataTable com a programação
          listagem.clear().rows.add(eventos).draw()
        }
      );
    });

  });
</script>
