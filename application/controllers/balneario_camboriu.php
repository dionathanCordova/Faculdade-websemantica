<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class balneario_camboriu extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('Busca_model', 'Busca');
	}

	public function index()
	{
		$dados['viewName'] = 'pesquisa_servidor';
        $this->load->view('index', $dados);
    }

    public function getCountCruzamento() {
		$municipio = !empty($this->input->post('municipio')) ? $this->input->post('municipio') : null;
		$municipio_alvo  = !empty($this->input->post('listaMunicipios')) ? $this->input->post('listaMunicipios') : null;
		
		// foreach($municipio_alvo as $info) {
		// 	$dados[$info]  = $this->Busca->GetCountCruzamentoBanlearioCamboriu($municipio, $info);
		// }

		echo json_encode(['municipio' => $municipio, 'listaMunicipios' => $municipio_alvo, 'dados' => $dados]);
	}
}