<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Busca extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('Busca_model', 'Busca');
	}

	public function index()
	{
		$dados['viewName'] = 'pesquisa_servidor';
        $this->load->view('index', $dados);
	}

	public function busca() {
		$nome = !empty($this->input->post('servidor')) ? $this->input->post('servidor') : null;
		$cpf =  !empty($this->input->post('cpf')) ? $this->input->post('cpf') : null;

		$InBc = $this->Busca->GetDados('balneario_camboriu', $nome, $cpf);
		$inCamboriu = $this->Busca->GetDados('camboriu', $nome, $cpf);
		$inNavegantes = $this->Busca->GetDados('navegantes', $nome, $cpf);
		$inItajai = $this->Busca->GetDados('itajai', $nome, $cpf);
		$inPortoBelo = $this->Busca->GetDados('porto_belo', $nome, $cpf);
		$inBombinhas = $this->Busca->GetDados('bombinhas', $nome, $cpf);
		$inItapema = $this->Busca->GetDados('itapema', $nome, $cpf);
		$inLuizAlves = $this->Busca->GetDados('luiz_alves', $nome, $cpf);
		$inIlhota = $this->Busca->GetDados('ilhota', $nome, $cpf);
		$inPenha = $this->Busca->GetDados('penha', $nome, $cpf);
		$inPicarras = $this->Busca->GetDados('picarras', $nome, $cpf);

		echo json_encode([
			'BC' => $InBc,
			'CAMBORIU' => $inCamboriu,
			'NAVEGANTES' => $inNavegantes, 
			'ITAJAI' => $inItajai,
			'PORTO_BELO' => $inPortoBelo,
			'PICARRAS' => $inPicarras,
			'BOMBINHAS' => $inBombinhas,
			'ITAPEMA' => $inItapema,
			'LUIZ_ALVES' => $inLuizAlves,
			'PENHA`' => $inPenha,
			'ILHOTA' => $inIlhota,
		]);
	}

	public function pesquisa_servidor() {

		$dados['viewName'] = 'pesquisa_servidor';
        $this->load->view('index', $dados);
	}

	public function cruzamento_municipios() {
	
		$dados['viewName'] = 'cruzamento_municipios';
        $this->load->view('index', $dados);
	}

	public function getCountCruzamento() {
		$municipio = !empty($this->input->post('municipio')) ? $this->input->post('municipio') : null;
		$municipio_alvo  = !empty($this->input->post('listaMunicipios')) ? $this->input->post('listaMunicipios') : null;
		
		foreach($municipio_alvo as $info) {
			$retorno = $this->Busca->GetCountCruzamentoBanlearioCamboriu($municipio, $info);

			if($retorno[0]['cidade_cruzada_nome'] != null) {
				$dados[$info] = $retorno;
			}else{
				$dados[$info][0] = [
					'soma_acumulo' => 0,
					'cidade_cruzada_nome' => $this->Busca->getDadosIfCountZero($info)[0]['cidade'],
					'cidade_cruzada_tabela' => $this->Busca->getDadosIfCountZero($info)[0]['tabela'],
					'minha_cidade_nome' => $this->Busca->getDadosIfCountZero($municipio)[0]['cidade'],
					'minha_cidade_tabela' => $this->Busca->getDadosIfCountZero($municipio)[0]['tabela']
				]; 
			}
		}
		// $dados[$info]  = $this->Busca->GetCountCruzamentoBanlearioCamboriu($municipio, $info);

		echo json_encode(['municipio' => $municipio, 'listaMunicipios' => $municipio_alvo, 'dados' => $dados]);
	}

	public function pagination() {
		
		$municipio = !empty($this->input->post('nomesMunicipios')) ? $this->input->post('nomesMunicipios'): null;

		if($municipio != null) {
			$municipio = explode('/', $municipio);
			$meu_municipio = $municipio[0];
			$municipio_alvo = $municipio[1];

			$total_registros = $this->Busca->count_all_pagination($meu_municipio, $municipio_alvo);
		}

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = "#";  
        $config["total_rows"] = $total_registros;
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;
        $config["use_page_numbers"] = TRUE;
        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';
        $config["first_tag_open"] = "<li style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd;'>";
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = "<li style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd;'>";
        $config["last_tag_close"] = '</li>';
        $config['next_link'] = '&gt;';
        $config["next_tag_open"] = "<li style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd;'>";
        $config["next_tag_close"] = '</li>';
        $config["prev_link"] = "&lt;";
        $config["prev_tag_open"] = "<li style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd;'>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='active'><a style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd; color: #fff; background:#337ab7 ' href='#'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] ="<li style='position: relative; padding: 6px 12px; line-height: 1.42857143; border: 1px solid #ddd;'>";
        $config["num_tag_close"] = "</li>";
        $config["num_links"] = 1;
        $this->pagination->initialize($config);
        $page = $this->uri->segment(3);
		$start = ($page - 1) * $config["per_page"];
		
        $output = array(
            'response' =>  array('minhacidade' => $meu_municipio, 'municipioalvo' =>  $municipio_alvo ),
            'pagination_link'  => $this->pagination->create_links(),
            'country_table'   => $this->Busca->GetDadosCrazados($start, $config["per_page"], $meu_municipio, $municipio_alvo)
        );

        echo json_encode($output); 
    } 

	public function getDadosCruzamento() {
		$municipio = !empty($this->input->post('nomesMunicipios')) ? $this->input->post('nomesMunicipios'): null;

		if($municipio != null) {
			$municipio = explode('/', $municipio);
			$meu_municipio = $municipio[0];
			$municipio_alvo = $municipio[1];

			$crazamento = $this->Busca->GetDadosCrazados($config["per_page"], $start, $meu_municipio, $municipio_alvo);
		}
		
		echo json_encode(['dadosCruzados' => $crazamento]);
	}

	public function getDadosCruzamentoView() {
		$this->load->library('tcpdf/Tcpdf');    

		$meu_municipio = $this->uri->segment(3);
		$municipio_alvo = $this->uri->segment(4);
		$meu_municipio_matrivula = $this->uri->segment(5);
		$municipio_alvo_matrivula = $this->uri->segment(6);

		$dados['relatorio'] = $this->Busca->GetDadosCrazadosView("$meu_municipio", "$municipio_alvo", $meu_municipio_matrivula, $municipio_alvo_matrivula);
        $val = 0;
        $cont = 0;
        foreach( $dados['relatorio'] as $info) {
            $val = $val + $info['nome_cid'];
            $cont ++;
        }

        $dados['contador_registro'] =  $cont;

		$this->load->view('PdfCruzamento', $dados);
		// echo '<pre>';
		// var_dump($dados['relatorio'] );
	}

	public function pdfconsulta() {
		$this->load->library('tcpdf/Tcpdf');  

		$dados['nome'] = !empty($this->input->post('nomeConsulta')) ? $this->input->post('nomeConsulta') : null;
		$dados['cpf'] =  !empty($this->input->post('cpfConsulta')) ? $this->input->post('cpfConsulta') : null;

		$dados['InBc'] = $this->Busca->GetDados('balneario_camboriu', $dados['nome'], $dados['cpf']);
		$dados['inCamboriu'] = $this->Busca->GetDados('camboriu', $dados['nome'], $dados['cpf']);
		$dados['inNavegantes'] = $this->Busca->GetDados('navegantes', $dados['nome'], $dados['cpf']);
		$dados['inItajai'] = $this->Busca->GetDados('itajai', $dados['nome'], $dados['cpf']);
		$dados['inPortoBelo'] = $this->Busca->GetDados('porto_belo', $dados['nome'], $dados['cpf']);
		$dados['inPicarras'] = $this->Busca->GetDados('picarras', $dados['nome'], $dados['cpf']);

		$this->load->view('Pdfconsulta', $dados);
	}
	
}


// $html .= '<br> <br>

//     <table cellspacing="0" cellpadding="1" style="font-family:arial; font-size:13px; border: 1px solid #000000;" border="1">

//         <tr class="thead-dark">
//             <td style="text-align:center; width:20px"><strong>' .  $cont . '</strong> </td>
//             <td class="col-xs-6 text-center" colspan="7" style="text-align:center;"><h4> ' . $servidor.' CPF : ' .$CPF_MA . ' </h4></td>
//         </tr>

//     </table>

        

//     <table cellspacing="0" cellpadding="1" style="font-family:arial; font-size:13px; border: 1px solid #000000;" border="1">

//         <tr class="thead-dark">

          
//             <td style="text-align:center; width:160px"><strong>Matricula  '. $cidade_cid . '</strong> </td>
//             <td style="text-align:center; width:160px"><strong>Matricula '. $cidade_MA . '</strong> </td>
//             <td style="text-align:center; width:160px"><strong>Cargo '. $cidade_cid . '</strong> </td>
//             <td style="text-align:center; width:160px"><strong>Cargo '. $cidade_MA . '</strong> </td>

//         </tr>

//     </table>

//     ';