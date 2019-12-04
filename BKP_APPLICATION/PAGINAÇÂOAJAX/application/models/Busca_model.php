<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Busca_model extends CI_Model{

    public function __contruct(){
        parent::__construct();
    }

    public function GetDados($table, $nome = null, $CPF = null) {
        if($nome != null && $CPF != null) {
            $query = $this->db->like('nome', $nome)->like('CPF', $CPF)->order_by('nome')->get("$table");
        }else if($nome != null && $CPF == null) {
            $query = $this->db->like('nome', $nome)->order_by('nome')->get("$table");
        }else if($nome == null && $CPF != null) {
            $query = $this->db->like('CPF', $CPF)->order_by('nome')->get("$table");
        }else{
            $query = false;
        }

        $output = '';
            
        if($query) {
            foreach($query->result() as $row) {
                
                $output .= '
                    <tr>
                    <td>'.$row->nome.'</td>
                    <td>'.$row->matricula.'</td>
                    <td>'.$row->CPF.'</td>
                    <td>'.$row->horas_mes.'</td>
                    <td>'.$row->cidade.'</td>
                    <td>'.$row->cargo.'</td>
                    </tr>
                ';
            }
            if(count($query->result()) > 0 ) {
                return $output;
            }
        }
        else{
            return 0;
        }
    }

    public function GetCountCruzamentoBanlearioCamboriu($municipio, $municipio_alvo) {
        $query = $this->db->query("SELECT 
        count($municipio_alvo.nome) as soma_acumulo,
        $municipio_alvo.cidade as cidade_cruzada_nome,
        $municipio.cidade as minha_cidade_nome,
        $municipio_alvo.tabela as cidade_cruzada_tabela,
        $municipio.tabela as minha_cidade_tabela
        from $municipio_alvo 
        inner join $municipio 
        on $municipio.nome = $municipio_alvo.nome
        AND $municipio.CPF = $municipio_alvo.CPF
        ");
        return $query->result();
    }

    public function count_all_pagination($meumunicipio, $municipio_alvo) {
        $query = $this->db->query("SELECT 
        $meumunicipio.nome as nome_cid,
        $meumunicipio.matricula as matricula_cid,
        $meumunicipio.CPF as CPF_cid,
        $meumunicipio.horas_mes as horario_cid,
        $meumunicipio.cargo as cargo_cid,
        $meumunicipio.cidade as cidade_cid,
        $meumunicipio.tabela as tabela_cid,
        $municipio_alvo.nome as nome_MA,
        $municipio_alvo.matricula as matricula_MA,
        $municipio_alvo.CPF as CPF_MA,
        $municipio_alvo.horas_mes as horario_MA,
        $municipio_alvo.cargo as cargo_MA,
        $municipio_alvo.cidade as cidade_MA,
        $municipio_alvo.tabela as tabela_MA
        from $municipio_alvo 
        inner join $meumunicipio 
        on $meumunicipio.nome = $municipio_alvo.nome
        AND $meumunicipio.CPF = $municipio_alvo.CPF
        ");

        return $query->num_rows();
    }

    public function GetDadosCrazados($limit, $start, $meumunicipio, $municipio_alvo) {
        $query = $this->db->query("SELECT 
        $meumunicipio.nome as nome_cid,
        $meumunicipio.matricula as matricula_cid,
        $meumunicipio.CPF as CPF_cid,
        $meumunicipio.horas_mes as horario_cid,
        $meumunicipio.cargo as cargo_cid,
        $meumunicipio.cidade as cidade_cid,
        $meumunicipio.tabela as tabela_cid,
        $municipio_alvo.nome as nome_MA,
        $municipio_alvo.matricula as matricula_MA,
        $municipio_alvo.CPF as CPF_MA,
        $municipio_alvo.horas_mes as horario_MA,
        $municipio_alvo.cargo as cargo_MA,
        $municipio_alvo.cidade as cidade_MA,
        $municipio_alvo.tabela as tabela_MA
        from $municipio_alvo 
        inner join $meumunicipio 
        on $meumunicipio.nome = $municipio_alvo.nome
        AND $meumunicipio.CPF = $municipio_alvo.CPF
        limit $limit, $start
        ");

        $output = '
        <table class="table table-bordered table-striped">
            <tr>
                <th>Nome</th>
                <th id="cpf_meu_municipio">CPF '.$meumunicipio . '</th>
                <th id="cpf_municipio_alvo">CPF '.$municipio_alvo . '</th>
                <th id="meu_municipio">Cargo '.$meumunicipio . '</th>
                <th id="municipio_alvo">Cargo '.$municipio_alvo . '</th>
                <th id="municipio_alvo">CH '.$meumunicipio . '</th>
                <th id="municipio_alvo">CH '.$municipio_alvo . '</th>
                <th>Visualizar</th>
            </tr>
        ';

        $link = base_url('/Busca/getDadosCruzamentoView/');
            
        if($query) {
            foreach($query->result() as $row) {
                
                $output .= '
                    <tr>
                    <td>'.$row->nome_cid.'</td>
                    <td>'.$row->CPF_cid.'</td>
                    <td>'.$row->CPF_MA.'</td>
                    <td>'.$row->cargo_cid.'</td>
                    <td>'.$row->cargo_MA.'</td>
                    <td class="text-center">
                    <a target="_blank" href='.  $link . $row->tabela_cid.'/'. $row->tabela_MA . '/' . $row->matricula_cid . '/' . $row->matricula_MA  .'>
                        <button type="button" class="btn btn-primary btn-xs btnViewCruzamento" name="btn_id" value="'.$row->cidade_cid.'|'. $row->matricula_MA . '|' . $row->CPF_cid  .'|' .$row->CPF_MA .  '"><i class="fa fa-eye"></i></button>
                    </a>
                </td>
                    </tr>
                ';
            }

            $output .= '</table>';
            if(count($query->result()) > 0 ) {
                return $output;
            }
        }
        else{
            return 0;
        }
    }

    public function GetDadosCrazadosView($meumunicipio, $municipio_alvo, $meu_municipio_matrivula, $municipio_alvo_matrivula) {
        $query = $this->db->query(
            "SELECT 
            $meumunicipio.nome as nome_cid,
            $meumunicipio.matricula as matricula_cid,
            $meumunicipio.CPF as CPF_cid,
            $meumunicipio.horas_mes as horario_cid,
            $meumunicipio.cargo as cargo_cid,
            $meumunicipio.cidade as cidade_cid,
            $municipio_alvo.nome as nome_MA,
            $municipio_alvo.matricula as matricula_MA,
            $municipio_alvo.CPF as CPF_MA,
            $municipio_alvo.horas_mes as horario_MA,
            $municipio_alvo.cargo as cargo_MA,
            $municipio_alvo.cidade as cidade_MA
            from $municipio_alvo 
            inner join $meumunicipio 
            on $meumunicipio.nome = $municipio_alvo.nome
            AND  $meumunicipio.matricula = $meu_municipio_matrivula
            AND $meumunicipio.CPF = $municipio_alvo.CPF
            -- AND $municipio_alvo.matricula  = $municipio_alvo_matrivula
            "
        );

        return $query->result_array();
    }
}