<div id="sisem-destaque" class="container-fluid">
  <h3 class="titulo">Consulte a Programação</h3>
  <div class="row">
    <div class="col-md-12">
      <ul id="sisem-destaque-conteudo"></ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 confira">
      <a id="botao-confira" href="<?= home_url('/programacao') ?>" class="btn btn-lg">Confira a Programação Completa</a>
    </div>
  </div>
</div>

<!-- Javascript da Página -->
<script type="text/javascript">
  jQuery(window).ready(function($) {
    var eventos = [];
    var museus = [6, 7, 11, 15, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 66, 85, 1444];
    var data_selecionada = "<?= date('Y-m-d'); ?>";
    var museusListagem = [];

    // Renderiza o Evento na Lista de Eventos
    function renderEvento(evento) {
      // Data do Evento
      var d = new Date(evento.starts_on + 'T' + evento.starts_at + '.000Z');
      var eventoData = d.toISOString().slice(8,10) + '/' + d.toISOString().slice(5,7);
      var eventoMuseu = '';
      // Museu do Evento
      $.each(museusListagem, function(k,v) {
        if(evento.space.id == v.id) {
          eventoMuseu = v.name;
        }
      });
      var html = '<a href="'+evento.singleUrl+'" target="_blank"><div class="container-fluid"><div class="row"><div id="evento-data" class="col-md-2">'+eventoData+'</div><div class="evento-conteudo col-md-10"><p id="evento-nome">'+evento.name+'</p><p id="evento-museu">'+eventoMuseu+'</p></div></div></div></a>';
      $('#sisem-destaque-conteudo').append($('<li />', {
        id: 'evento-'+evento.id,
        class: 'evento',
      }).html(html));
    }

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
          museusListagem.push(v);
        });
      }
    );
    // Efetua a Requisição dos Eventos em geral à partir da Data Atual
    $.ajaxSetup({cache:false});
    $.support.cors = true;
    $.getJSON(
      'http://estadodacultura.sp.gov.br/api/event/findOccurrences',
      {
        '@from': data_selecionada,
        '@select': 'id,name,singleUrl',
        '@order': 'name ASC',
        '@limit': 4,
      },
      function (response) {
        $.each(response, function(k,v) {
          // Insere o Evento dentro do Array com todos os Eventos
          if ($.inArray(v.space.id,museus) >= 0) {
            renderEvento(v);
          }
        });
      }
    );

  });
</script>
