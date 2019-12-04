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
                $cpf = ($row->CPF != 'Null') ? $row->CPF : 'Não informado';
                $ch = ($row->horas_mes != 'Null') ? $row->horas_mes : 'Não informado';
                $output .= '
                    <tr>
                    <td>'.$row->nome.'</td>
                    <td>'.$row->matricula.'</td>
                    <td>'.$cpf.'</td>
                    <td>'.$ch.'</td>
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

    public function getDadosIfCountZero($municipio) {
        $query = $this->db->select('cidade, tabela')->get($municipio);
        return $query->result_array();
    }

    public function GetCountCruzamentoBanlearioCamboriu($municipio, $municipio_alvo) {
        $this->db->select("
        count($municipio_alvo.nome) as soma_acumulo,
        $municipio_alvo.cidade as cidade_cruzada_nome,
        $municipio.cidade as minha_cidade_nome,
        $municipio_alvo.tabela as cidade_cruzada_tabela,
        $municipio.tabela as minha_cidade_tabela");
        $this->db->from($municipio_alvo);
        $this->db->join($municipio, "$municipio.nome = $municipio_alvo.nome");
        $this->db->where("$municipio.CPF = $municipio_alvo.CPF");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function count_all_pagination($meumunicipio, $municipio_alvo) {
        $this->db->select("
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
        $municipio_alvo.tabela as tabela_MA");
        $this->db->from($municipio_alvo);
        $this->db->join($meumunicipio, "$meumunicipio.nome = $municipio_alvo.nome");
        $this->db->where("$meumunicipio.CPF = $municipio_alvo.CPF");
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function GetDadosCrazados($limit, $start, $meumunicipio, $municipio_alvo) {
        $this->db->select("
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
        $municipio_alvo.tabela as tabela_MA");
        $this->db->from($municipio_alvo);
        $this->db->join($meumunicipio, "$meumunicipio.nome = $municipio_alvo.nome");
        $this->db->where("$meumunicipio.CPF = $municipio_alvo.CPF");
        $this->db->limit($start, $limit);
        $query = $this->db->get();

        // $query = $this->db->query("SELECT 
        // $meumunicipio.nome as nome_cid,
        // $meumunicipio.matricula as matricula_cid,
        // $meumunicipio.CPF as CPF_cid,
        // $meumunicipio.horas_mes as horario_cid,
        // $meumunicipio.cargo as cargo_cid,
        // $meumunicipio.cidade as cidade_cid,
        // $meumunicipio.tabela as tabela_cid,
        // $municipio_alvo.nome as nome_MA,
        // $municipio_alvo.matricula as matricula_MA,
        // $municipio_alvo.CPF as CPF_MA,
        // $municipio_alvo.horas_mes as horario_MA,
        // $municipio_alvo.cargo as cargo_MA,
        // $municipio_alvo.cidade as cidade_MA,
        // $municipio_alvo.tabela as tabela_MA
        // from $municipio_alvo 
        // inner join $meumunicipio 
        // on $meumunicipio.nome = $municipio_alvo.nome
        // AND $meumunicipio.CPF = $municipio_alvo.CPF
        // limit $limit, $start
        // ");

        foreach($query->result() as $row) {
            $meumunicipioNome = $row->cidade_cid;
            $municipio_alvoNome = $row->cidade_MA;
        }

        $output = '
        <table class="table table-bordered table-striped table-responsive table-condensed" style="font-size: 2vmin">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th id="meu_municipio">Cargo '.$meumunicipioNome . '</th>
                <th id="municipio_alvo">Cargo '.$municipio_alvoNome . '</th>
                <th id="municipio_alvo">CH '.$meumunicipioNome . '</th>
                <th id="municipio_alvo">CH '.$municipio_alvoNome . '</th>
                <th id="municipio_alvo">CH TOTAL </th>
                <th>Visualizar</th>
            </tr>
        ';

        $link = base_url('/Busca/getDadosCruzamentoView/');
            
        if($query) {
            foreach($query->result() as $row) {
                $cargahoraria = floatval($row->horario_cid) + floatval($row->horario_MA);
                $textColor = ($cargahoraria > 200) ? 'text-danger' : 'text-dark';

                $horario_cid = ($row->horario_cid != 'Null') ? $row->horario_cid : 'Não informado';
                $horario_MA = ($row->horario_MA != 'Null') ? $row->horario_MA : 'Não informado';
                $CPF_cid = ($row->CPF_cid != 'Null') ? $row->CPF_cid : 'Não informado';

                $output .= '
                    <tr id="dadosServidorCruzado">
                        <td class='.$textColor. '>'.$row->nome_cid.'</td>
                        <td>'.$CPF_cid.'</td>
                        <td>'.$row->cargo_cid.'</td>
                        <td>'.$row->cargo_MA.'</td>
                        <td>'.$horario_cid.'</td>
                        <td>'.$horario_MA.'</td>
                        <td class='.$textColor. '>'. $cargahoraria.'</td>
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