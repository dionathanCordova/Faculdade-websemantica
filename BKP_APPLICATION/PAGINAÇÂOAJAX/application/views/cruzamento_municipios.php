<!--==========================
    Why Us Section
============================-->
<section id="why-us" class="wow fadeIn">
    <div class="container">
    <header class="section-header">
        <h3>Cruzamento de dados entre municipios</h3>
        <p>Localizar registros de acumulo de cargos entre seu municipios em relação a todos vinculados a ANFRI.</p>

        <h4 class="text-center text-light" id='municipioSelecionado'></h4>
    </header>

    <div class="form-group col-sm-12">
        <div class="form">
            <form class="form" action="" class="contactForm">
                <div class="form-row">
                    <div class="form-group col-lg-10">
                        <select class='form-control' type="text" name="municipio" id='municipio' required>
                            <option value="">Selecione seu municipio</option>
                            <option value="balneario_camboriu">Balneario Camboriu</option>
                            <option value="camboriu">Camboriu</option>
                            <option value="navegantes">Navegantes</option>
                            <option value="itajai">Itajaí</option>
                            <option value="porto_belo">Porto Belo</option>
                            <option value="picarras">Balneário Piçarras</option>
                        </select>                   
                    </div>
                    <div class="text-center col-lg-2"><button type="button" class="btn btn-primary" id='btnCruzamento'>Pesquisar</button></div>
                </div>
            </form>
        </div>
    </div>
   

    <!-- <div class="row row-eq-height justify-content-center">

        <div class="col-lg-4 mb-4">
            <div class="card wow bounceInUp">
                <i class="fa fa-diamond"></i>
                <div class="card-body">
                <h5 class="card-title">Corporis dolorem</h5>
                <p class="card-text">Deleniti optio et nisi dolorem debitis. Aliquam nobis est temporibus sunt ab inventore officiis aut voluptatibus.</p>
                <a href="#" class="readmore">Read more </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
        <div class="card wow bounceInUp">
            <i class="fa fa-language"></i>
            <div class="card-body">
            <h5 class="card-title">Voluptates dolores</h5>
            <p class="card-text">Voluptates nihil et quis omnis et eaque omnis sint aut. Ducimus dolorum aspernatur.</p>
            <a href="#" class="readmore">Read more </a>
            </div>
        </div>
        </div>

        <div class="col-lg-4 mb-4">
        <div class="card wow bounceInUp">
            <i class="fa fa-object-group"></i>
            <div class="card-body">
            <h5 class="card-title">Eum ut aspernatur</h5>
            <p class="card-text">Autem quod nesciunt eos ea aut amet laboriosam ab. Eos quis porro in non nemo ex. </p>
            <a href="#" class="readmore">Read more </a>
            </div>
        </div>
        </div>

    </div> -->

    <h3 class="text-center text-light" id='titulo_encontrados'></h3>
   
    <div class='pull-center text-center' id="imgLoading" hidden>
        <img  src="<?php echo base_url('assets/img/loading.gif')?>" alt="carrregando dados" width='40'>
    </div>
   
    <div class="row counters" id='registros_encontrados'>
        
    </div>
        
</section>


<!--==========================
    About Us Section
============================-->


<section id="about">
    <div class="container-fluid">
        <div class="row about-container">
            <div class='container'>
                
                <table class="table table-striped tableCruzamento" hidden>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th id='cpf_meu_municipio'></th>
                            <th id='cpf_municipio_alvo'></th>
                            <th id='meu_municipio'></th>
                            <th id='municipio_alvo'></th>
                            <th>Visualizar</th>
                        </tr>
                    </thead>
                    
                    <tbody id='ResultCruzamento'>
                            
                    </tbody>
                </table>

                <div class='pull-center text-center' id="imgLoading" hidden>
                    <img  src="<?php echo base_url('assets/img/loading.gif')?>" alt="carrregando dados" width='40'>
                </div>

                <div class='table-responsive'>
                    <div class="table-responsive" id="country_table">
                        
                    
                    </div> 
                    <div align="right" id="pagination_link"></div>
                </div> 
            </div>
        </div> 
    </div>
</section><!-- #about -->

<script>
  $(document).ready(function() { 
    var nomesMunicipios = '';

    function load_country_data(page, nomesMunicipios) {
       
       url = "<?php echo base_url(); ?>Busca/pagination/"+page+'/';
    
       $.ajax({
           url: url,
           method:"POST",
           data: {nomesMunicipios: nomesMunicipios, page:page},
           dataType:"json",
           success:function(data) {
                $('#imgLoading').attr('hidden', true);
                console.log(data.response);
                $('#country_table').html(data.country_table);
                $('#pagination_link').html(data.pagination_link);
           }
       });
    }

    $(document).on('click', '.pagination li a', function(event) {
        event.preventDefault();
        let page = $(this).data('ci-pagination-page');
        load_country_data(page, nomesMunicipios);
    })

    $('#btnCruzamento').click(function() {
        $('#registros_encontrados').html('');
        $('#imgLoading').attr('hidden', false);

        if($('#municipio').val() == '') {
            $('#municipioSelecionado').text('Por favor informe seu municipio');
            $('#municipio').focus();
            return false;
        }else{
            $('#municipioSelecionado').text('');
        }

        let municipio = $('#municipio').val();
        let listaMunicipios = ['balneario_camboriu', 'navegantes', 'camboriu', 'itajai', 'porto_belo', 'picarras'];

        var index = listaMunicipios.indexOf(municipio);
        if (index > -1) {
            listaMunicipios.splice(index, 1);
        }
        listaMunicipios = listaMunicipios;

        let html = '';
        $.ajax({
            url: "<?php echo base_url('Busca/getCountCruzamento')?>",
            method:"POST",
            data: {municipio: municipio, listaMunicipios:listaMunicipios},
            dataType:"json",
            success:function(data) {
                $('#imgLoading').attr('hidden', true);

                let minha_cidade = '';
                Object.size = function(obj) {
                    var size = 0, key;
                    for (key in obj) {
                        if (obj.hasOwnProperty(key)) size++;

                        html += '<div class="col-lg-3 col-6 text-center "><span data-toggle="counter-up">' + data.dados[key][0]['soma_acumulo'] + '</span><button type="button" class="btn btn-link btnGetRegistrosCidades readmore" value="' + data.dados[key][0]['minha_cidade_tabela'] + '/' + data.dados[key][0]['cidade_cruzada_tabela'] + '"><p>' + data.dados[key][0]['cidade_cruzada_nome'] + '</p></button></div>'
                    }
                    return html;
                };

                var html_cruzamento = Object.size(data.dados);
                $('#registros_encontrados').html(html_cruzamento);

                $('#registros_encontrados button').on('click', function() {
                    $('#imgLoading').attr('hidden', false);
              

                    nomesMunicipios = $(this).val(); 

                    load_country_data(1, nomesMunicipios);  
                    // $.ajax({
                    //     url: "<?php echo base_url('Busca/getDadosCruzamento') ?>",
                    //     method: 'POST',
                    //     data: {nomesMunicipios:nomesMunicipios},
                    //     dataType:"json",
                    //     success: function(data) {
                    //         $('#imgLoading').attr('hidden', true);
                    //         $('.tableCruzamento').attr('hidden', false);
                            
                    //         $('#cpf_meu_municipio').text('CPF ' + data.meu_municipio);
                    //         $('#cpf_municipio_alvo').text('CPF ' + data.municipio_alvo);
                    //         $('#meu_municipio').text('Cargo ' + data.meu_municipio);
                    //         $('#municipio_alvo').text('Cargo ' + data.municipio_alvo);
                    //         $('#ResultCruzamento').html(data.dadosCruzados);
                    //     }
                    // })
                })
            }
        });
    })

   

})

   
</script>