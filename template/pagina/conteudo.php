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
          <select id="lista-de-linguagens" name="linguagem">
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
    var linguagens = [{"value":"Artes Circenses","label":"Artes Circenses"},{"value":"Artes Integradas","label":"Artes Integradas"},{"value":"Artes Visuais","label":"Artes Visuais"},{"value":"Audiovisual","label":"Audiovisual"},{"value":"Cinema","label":"Cinema"},{"value":"Cultura Digital","label":"Cultura Digital"},{"value":"Cultura Ind\u00edgena","label":"Cultura Ind\u00edgena"},{"value":"Cultura Tradicional","label":"Cultura Tradicional"},{"value":"Curso ou Oficina","label":"Curso ou Oficina"},{"value":"Dan\u00e7a","label":"Dan\u00e7a"},{"value":"Exposi\u00e7\u00e3o","label":"Exposi\u00e7\u00e3o"},{"value":"Hip Hop","label":"Hip Hop"},{"value":"Livro e Literatura","label":"Livro e Literatura"},{"value":"M\u00fasica Popular","label":"M\u00fasica Popular"},{"value":"M\u00fasica Erudita","label":"M\u00fasica Erudita"},{"value":"Palestra\\, Debate ou Encontro","label":"Palestra, Debate ou Encontro"},{"value":"R\u00e1dio","label":"R\u00e1dio"},{"value":"Teatro","label":"Teatro"},{"value":"Outros","label":"Outros"}];
    var data_selecionada = "<?= date('Y-m-d'); ?>";
    var museu_selecionado = 'Todos';
    var linguagem_selecionada = 'Todos';
    var listagem;
    var museusListagem = [];

    var args = {
      data: eventos,
      searching: false,
      lengthChange: false,
      columns : [
        { title: 'ID', visible: false },
        {
          title: 'Eventos da Programação',
          data: null,
          render: function (data, type, row) {
            content = '<div id="evento-'+data[0]+'" class="container-fluid evento">';
            content += '<div class="row">';
            content += '<div class="col-md-6 evento-foto" style="background-image: url('+data[1]+')"><p id="evento-data">'+data[6]+' horas</p><p id="evento-nome">'+data[2]+'</p></div>';
            content += '<div class="col-md-6 evento-conteudo">'
            content += '<div class="row"><div class="col-md-12"><h3 id="evento-museu">'+data[5]+'</h3></div></div>';
            content += '<div class="row"><div class="col-md-12"><p id="evento-descricao">'+data[3]+'</p></div></div>'
            content += '<div class="row"><div class="col-md-12"><a id="evento-url" href='+data[4]+'>Saiba Mais</a></div></div>'
            content += '</div></div></div>';
            return content;
          }
        }
      ],
      'language': {
        "sEmptyTable": "Nenhum evento encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ eventos",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 eventos",
        "sInfoFiltered": "(Filtrados de _MAX_ eventos)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "Resultados por Página _MENU_",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum evento encontrado",
        "sSearch": "Pesquisar",
        "oPaginate": {
           "sNext": "Próximo",
           "sPrevious": "Anterior",
           "sFirst": "Primeiro",
           "sLast": "Último"
        },
        "oAria": {
           "sSortAscending": ": Ordenar colunas de forma ascendente",
           "sSortDescending": ": Ordenar colunas de forma descendente"
        }
      }
    }

    // Filtra os Eventos na Lista de Eventos
    function filtraEventos(museuID, linguagemID, data) {

      eventos = [];

      // Verifica se a opção Todos os Museus está selecionada
      if (museuID == 'Todos') {
        buscaMuseu = museus;
      }
      else {
        buscaMuseu = [parseInt(museuID)];
      }

      // Verifica se a opção Todos as Linguagens está selecionada
      if (linguagemID == 'Todos') {
        buscaLinguagem = '';
      }
      else {
        buscaLinguagem = linguagemID;
      }


      // Efetua a Requisição dos Eventos em geral à partir da Data Atual
      $.getJSON(
        'http://estadodacultura.sp.gov.br/api/event/findOccurrences',
        {
          '@from': data,
          '@to': data,
          '@select': 'id,name,shortDescription,singleUrl,terms',
          '@files': '(header,avatar):url',
          '@order': 'name ASC',
        },
        function (response){
          $.each(response, function(k,v) {
            if ($.inArray(v.space.id, buscaMuseu) >= 0) {
              if (buscaLinguagem != '') {
                if ($.inArray(buscaLinguagem, v.terms.linguagem) >= 0) {
                  // Insere o Evento dentro do Array com todos os Eventos
                  eventos.push(renderEvento(v));
                }
              }
              else {
                // Insere o Evento dentro do Array com todos os Eventos
                eventos.push(renderEvento(v));
              }
            }
          });
          // Inicializa a dataTable com a programação
          listagem.clear().rows.add(eventos).draw()
        }
      );
    }

    // Renderiza o Evento na Lista de Eventos
    function renderEvento(evento) {
      var eventoDados = [evento.id];
      // Insere a Imagem do Evento
      if (typeof evento['@files:header'] != 'undefined') {
        eventoDados.push(evento['@files:header'].url);
      }
      else if (typeof evento['@files:avatar'] != 'undefined') {
        eventoDados.push(evento['@files:avatar'].url);
      }
      else {
        eventoDados.push('http://estadodacultura.sp.gov.br/assets/img/fundo.png');
      }
      // Insere o Título do Evento
      eventoDados.push(evento.name);
      // Insere a Descrição do Evento
      if (typeof evento['shortDescription'] != 'undefined') {
        eventoDados.push(evento.shortDescription);
      }
      else {
        eventoDados.push('Nenhuma descrição disponível.');
      }
      // Insere a Descrição do Evento
      eventoDados.push(evento.singleUrl);
      // Insere o Museu do Evento
      $.each(museusListagem, function(k,v) {
        if(evento.space.id == v.id) {
          eventoDados.push(v.name);
        }
      })
      // Insere a data do Evento
      var d = new Date(evento.rule.startsOn + ' ' + evento.rule.startsAt);
      eventoDados.push(d.toLocaleFormat('%d/%m - %H:%M'));
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

    // Renderiza as Linguagens na Lista de Linguagens
    function renderLinguagens(linguagem) {
      var item = jQuery('<option />', {
        value: linguagem.label,
        text: linguagem.label
      });
      jQuery('#lista-de-linguagens').append(item);
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
          renderMuseus(v);
        });
      }
    );

    // Efetua a criação das opções de linguagem
    $.each(linguagens, function(k, v) {
      renderLinguagens(v);
    });

    // Efetua a Requisição dos Eventos em geral à partir da Data Atual
    $.getJSON(
      'http://estadodacultura.sp.gov.br/api/event/findOccurrences',
      {
        '@from': data_selecionada,
        '@select': 'id,name,shortDescription,singleUrl,terms',
        '@files': '(header,avatar):url',
        '@order': 'name ASC',
      },
      function (response){
        $.each(response, function(k,v) {
          // Insere o Evento dentro do Array com todos os Eventos
          if ($.inArray(v.space.id,museus) >= 0) {
            eventos.push(renderEvento(v));
          }
        });
        // Inicializa a dataTable com a programação
        listagem = $("#eventos").DataTable(args);
      }
    );

    // Ativa o plugin de Datepicker para o filtro de Data
    var datepicker = $('#sisem-programacao-lateral div.data-selecionada').datepicker({
        language: "pt-BR",
        orientation: "auto left",
        todayHighlight: true
    }).on('changeDate', function(e) {
      var d = new Date(e.date);
      data_selecionada = d.toISOString().slice(0,10);
      filtraEventos(museu_selecionado, linguagem_selecionada, data_selecionada);
    });


    // Adiciona o Filtro por Museu Selecionado
    $(document).on('change', '#lista-de-museus', function(e) {
      e.preventDefault();
      museu_selecionado = $(this).val();
      filtraEventos(museu_selecionado, linguagem_selecionada, data_selecionada);
    });

    // Adiciona o Filtro por Linguagem Selecionada
    $(document).on('change', '#lista-de-linguagens', function(e) {
      e.preventDefault();
      linguagem_selecionada = $(this).val();
      filtraEventos(museu_selecionado, linguagem_selecionada, data_selecionada);
    });

  });
</script>
