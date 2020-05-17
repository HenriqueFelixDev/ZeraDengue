<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Denuncia\Denuncia;
use App\Models\Denuncia\DenunciaInfo;
use App\Repositories\IDenunciaRepository;
use App\Repositories\MySQL\DenunciaRepositoryMySQL;
use App\Utils\DadosUtil;
use App\Utils\GerenciadorImagensSimples;
use App\Utils\IGerenciadorImagens;
use DateTime;
use DI\NotFoundException;
use Pecee\SimpleRouter\SimpleRouter;

class DenunciaController
{
    private IDenunciaRepository $denunciaRepository;
    private IGerenciadorImagens $gerenciadorImagens;

    public function __construct()
    {
        $this->denunciaRepository = new DenunciaRepositoryMySQL(DB::obterConexao());
        $this->gerenciadorImagens = new GerenciadorImagensSimples();
    }

    public function index()
    {
        switch(SimpleRouter::request()->getMethod()) {
            case "get":
                $this->obterDenuncias();
                break;
            case "post":
                $this->cadastrar();
                break;
            default:
                throw new NotFoundException("Rota não encontrada", 404);
        }
    }

    public function cadastrar() : void
    {
        $dados = SimpleRouter::request()->getInputHandler();
        $denuncia = new Denuncia();
        $denuncia->usuarioId = SimpleRouter::request()->user->id;
        $denuncia->assunto = filter_var($dados->value("assunto", ""), FILTER_SANITIZE_STRING);
        $denuncia->descricao = filter_var($dados->value("descricao", ""), FILTER_SANITIZE_STRING);
        $denuncia->coordenadasFoco->latitude = filter_var($dados->value("latitude_foco", 0.0), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $denuncia->coordenadasFoco->latitude = floatval($denuncia->coordenadasFoco->latitude);
        $denuncia->coordenadasFoco->longitude = filter_var($dados->value("longitude_foco", 0.0), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $denuncia->coordenadasFoco->longitude = floatval($denuncia->coordenadasFoco->longitude);
        $denuncia->localFoco = filter_var($dados->value("local_foco", ""), FILTER_SANITIZE_STRING);
        $denuncia->fotos = DadosUtil::getValorArray($_FILES, "fotos");
        $denuncia->dataPublicacao = new DateTime();
        $denuncia->situacao = Denuncia::AGUARDANDO_ANALISE;

        $resultadoValidacao = $denuncia->validarCadastro();

        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()->httpCode(422)->json($resultadoValidacao->resultadosEmJson(), JSON_UNESCAPED_UNICODE);
        }

        $caminhosImagens = $this->gerenciadorImagens->salvarImagens(SimpleRouter::request()->user->id, $_FILES["fotos"]);
        
        $denuncia->fotos = $caminhosImagens;

        $res = $this->denunciaRepository->cadastrar($denuncia);

        if(!$res) {
            SimpleRouter::response()
                ->httpCode(500)
                ->json([
                    "errors" => "Ocorreu um erro ao salvar a denúncia"
                ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function obterDenuncias()
    {
        $dados = SimpleRouter::request()->getInputHandler();

        $pesquisa = $dados->value("q");
        $situacao = $dados->value("situacao");
        $dataInicial = $dados->value("data_inicial");
        $dataFinal = $dados->value("data_final");
        $limite = intval($dados->value("limite", "15"));
        $paginaAtual = intval($dados->value("pagina_atual", "1"));

        $dataInicial = !empty($dataInicial) ? new DateTime($dataInicial) : null;
        $dataFinal = !empty($dataFinal) ? new DateTime($dataFinal) : null;

        if (isset($dataFinal) && isset($dataInicial)) {
            if (($dataInicial->diff($dataFinal))->invert) {
                $antigaDataFinal = $dataInicial;
                $dataInicial = $dataFinal;
                $dataFinal = $antigaDataFinal;
            }
        }

        $denuncias = $this->denunciaRepository->obterDenunciasDoUsuario(SimpleRouter::request()->user->id, $pesquisa, $situacao, $dataInicial, $dataFinal, $limite, $paginaAtual);

        $denunciasDados = array();
        foreach($denuncias["resultados"] as $denuncia) {
            $denunciasDados[] = $this->obterHATEOASDenuncia($denuncia);
        }

        $denunciaInfo = new DenunciaInfo();
        $denunciaInfo->totalItens = intval($denuncias["total"]);
        $denunciaInfo->itensPorPagina = $limite;
        $denunciaInfo->paginaAnterior = $paginaAtual - 1 < 1 ? null : $paginaAtual - 1;
        $denunciaInfo->proximaPagina =  $paginaAtual + 1 > ceil($denuncias["total"]/$limite) ? null : $paginaAtual + 1;
        $denunciaInfo->self = SimpleRouter::request()->getUrl();
        $denunciaInfo->itens = $denunciasDados;

        SimpleRouter::response()->json($denunciaInfo, JSON_UNESCAPED_UNICODE);
    }

    public function obterDenunciaPorId(int $denunciaId)
    {
        $denuncia = $this->denunciaRepository->obterPorId($denunciaId);

        if(!isset($denuncia)) {
            SimpleRouter::response()->httpCode(404)->json([
                "errors" => "Denúncia não encontrada"
            ], JSON_UNESCAPED_UNICODE);
        }

        $denunciaDados = $this->obterHATEOASDenuncia($denuncia);

        SimpleRouter::response()->json($denunciaDados, JSON_UNESCAPED_UNICODE);
    }

    private function obterHATEOASDenuncia($denuncia)
    {
        $denunciaUrl = SimpleRouter::request()->getUrl()->getPath().$denuncia->denuncia_id;
        $denunciaDados = [
            "self" => $denunciaUrl,
            "dados" => $denuncia
        ];
        return $denunciaDados;
    }
    
}