
<div class="row about-extra">
    <div class="col-lg-6 wow fadeInUp order-1 order-lg-2">
        <img src="<?php echo base_url('assets/img/about-extra-2.svg')?>" class="img-fluid" alt="">
    </div>

    <div class="col-lg-6 wow fadeInUp pt-4 pt-lg-0 order-2 order-lg-1">
        <h4>SBAC - Sistema de Busca de Acúmulo de Cargos</h4>
        <p>
            Todos os municipios onde existe servidor com dados semelhantes ao informados.
        </p>
        <p>
            Simples consulta buscando análisar possível acúmulo de cargo público. 
        </p>
    </div>
</div><br>

<header class="section-header" id='Pesquisa_servidor'>
    <h3>Pesquisar servidor</h3>
    <p>Faça uma busca por servidores, o resultado trará os municípios relacionados</p>
</header>

<div class="row about-container">
    <div class="form-group col-sm-12">
        <div class="form">
            <form class="form" action="" class="contactForm">
                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <input type="text" class="form-control" id="Nome" placeholder="Nome">
                    </div>
                    <div class="form-group col-lg-6">
                        <input type="text" class="form-control" id="CPF" placeholder="CPF">
                    </div>
                </div>
                <div class="text-center"><button type="button" class="btn btn-primary" id='btnPesquisa'>Pesquisar</button></div>
            </form>
        </div>
    </div>


    <div class='container'>
        
        <div class='pull-center text-center' id="imgLoading" hidden>
            <img  src="<?php echo base_url('assets/img/loading.gif')?>" alt="carrregando dados" width='40'>
        </div>

        <table class="table table-striped tableResult" hidden>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matriculo</th>
                    <th>CPF</th>
                    <th>Carga Horária</th>
                    <th>Município</th>
                    <th>Cargo</th>
                </tr>
            </thead>
            
            <tbody id='Result'>
                <tr>
                    
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
		$('#btnPesquisa').click(function() {
            $('#imgLoading').attr('hidden', false);

			let nome = $('#Nome').val();
			let cpf = $('#CPF').val();
			$.ajax({
                url: 'Busca/busca',
                method:"POST",
                data: {servidor: nome, cpf: cpf},
                dataType:"json",
                success:function(data) {
                    $('#imgLoading').attr('hidden', true);
                    $('.tableResult').attr('hidden', false);
                    console.log(data);

					let htmlBC = '';
					let htmlCAMBORIU = '';
                    let htmlITAJAI = '';
                    let htmlNAVEGANTES = '';
                    let htmlPORTO_BELO = '';
                    let htmlPICARRAS = '';

					if(data.BC != null || data.BC != 0) {
						htmlBC = data.BC;
					}

					if(data.CAMBORIU != null || data.CAMBORIU != 0) {
						htmlCAMBORIU = data.CAMBORIU;
					}

					if(data.ITAJAI != null || data.ITAJAI != 0) {
						htmlITAJAI = data.ITAJAI;
					}

					if(data.NAVEGANTES != null || data.NAVEGANTES != 0) {
						NAVEGANTES = data.NAVEGANTES;
					}

					if(data.PORTO_BELO != null || data.PORTO_BELO != 0) {
						PORTO_BELO = data.PORTO_BELO;
					}

					if(data.PICARRAS != null || data.PICARRAS != 0) {
						PICARRAS = data.PICARRAS;
					}
         
					$('#Result').html(htmlBC + htmlCAMBORIU + htmlITAJAI + htmlNAVEGANTES + htmlPORTO_BELO, htmlPICARRAS);
                }
            });
		})
	</script>