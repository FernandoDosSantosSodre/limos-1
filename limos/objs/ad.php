﻿<?php
    class ad{
        public $id_ad;
        public $status_ad;
        public $id_res;
        public $status_pag_ad;
        public $data_inicio_ad;
        public $data_fim_ad;

        public function __construct($id_ad, $id_res, $data_inicio_ad, $data_fim_ad, $status_ad, $status_pag_ad){
            $this-> id_ad = $id_ad;
            $this-> id_res = $id_res;
            $this-> data_fim_ad = $data_fim_ad;
            $this-> data_inicio_ad = $data_inicio_ad;
            $this-> status_pag_ad = $status_pag_ad;
            if($status_ad == 1){
                if($this->valida_status()){
                    $this->status_ad = 1;
                }else{
                    $this->status_ad = 2;
                }
            }else{
                $this->status_ad = 2;
            }
        }

        public function valida_status(){
            if($this->valida_inicio_fim()){
                $hoje = explode("-", date("Y-m-d"));
                $fim = explode("-", $this->data_fim_ad);
                $ptsFim = ($fim[0] * 365) + ($fim[1] * 30) + $fim[2];
                $ptsHoje = ($hoje[0] * 365) + ($hoje[1] * 30) + $hoje[2];
                if($ptsHoje > $ptsFim){
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }

        public function valida_inicio_fim(){
            $inicio = explode("-", $this->data_inicio_ad);;
            $fim = explode("-", $this->data_fim_ad);
            $ptsFim = ($fim[0] * 365) + ($fim[1] * 30) + $fim[2];
            $ptsInicio = ($inicio[0] * 365) + ($inicio[1] * 30) + $inicio[2];
            if($ptsInicio > $ptsFim){
                return false;
            }else{
                return true;
            }
        }

        public function formata_data_ad($modificador){
            # 1 = início, 2 = fim
            switch($modificador){
                case 1:
                    $data = explode("-", $this->data_inicio_ad);
                    echo $data[2]."/".$data[1]."/".$data[0];
                    break;
                case 2:
                    $data = explode("-", $this->data_fim_ad);
                    echo $data[2]."/".$data[1]."/".$data[0];
                    break;
                default:
                    echo "00/00/0000";
                    break;
            }
        }

        public function atualizaAdBanco($conexao){
            if($this->valida_status()){
                $idres = $this->id_res;
                $sql = "UPDATE `ad` SET `status_ad`='1' WHERE `id_res`='$idres'";
                $conexao->query($sql);
                return true;
            }else{
                $idres = $this->id_res;
                $sql = "UPDATE `ad` SET `status_ad`='2' WHERE `id_res`='$idres'";
                $conexao->query($sql);
                return false;
            }
        }

        # Retorna o valor do anúncio, cada dia custa 1,35 reais
        public function valorAd(){
            $inicio = explode("-", $this->data_inicio_ad);;
            $fim = explode("-", $this->data_fim_ad);
            $ptsFim = ($fim[0] * 365) + ($fim[1] * 30) + $fim[2];
            $ptsInicio = ($inicio[0] * 365) + ($inicio[1] * 30) + $inicio[2];
            $total = ($ptsFim - $ptsInicio) * 1.35;
            if($total == 0){
                $total = 1.35;
            }
            return $total;
        }

        public function calculaParcelaAd($qtdParcelas){
            $valor = $this->valorAd();
            return $valor / $qtdParcelas;
        }

        public function formataParcelaAd($qtdParcelas){
            $valor = $this->calculaParcelaAd($qtdParcelas);
            $valor = number_format($valor, 2, ',', '.');
            echo 'R$ '.$valor.'';
        }
    }
    # Fim da Classe

?>