<?php

namespace App\Controllers;

use App\Models\Mensagens\GerenciadorMensagens;
use App\Models\Mensagens\Mensagem;
use App\Repositories\ComentarioRepository;
use App\Repositories\DenunciaRepository;
use Pecee\SimpleRouter\SimpleRouter;

require_once "vendor/pecee/simple-router/helpers.php";

class DenunciaController extends Controller
{
    private DenunciaRepository $denunciaRepository;
    private ComentarioRepository $comentarioRepository;

    public function __construct()
    {
        $this->denunciaRepository = new DenunciaRepository();
        $this->comentarioRepository = new ComentarioRepository();
    }

    public function index()
    {
        global $twig;
        $denunciasInfo = $this->consultar();
        $filtros = (object) $_GET;

        $url = SimpleRouter::request()->getUrl();
        $urlPaginacao = rtrim($url->getPath(), '/')."?";
        foreach($url->getParams() as $param=>$valor) {
            if($param == "pagina") {
                continue;
            }
            $urlPaginacao .= "$param=$valor&";
        }
        $urlPaginacao .= "pagina=";

        $paginaAtual = isset($url->getParams()["pagina"]) ? $url->getParams()["pagina"] : 1;
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("denuncias/consulta_denuncias.php", ["mensagens" => $mensagens, "denunciasInfo" => $denunciasInfo, "filtros" => $filtros, "urlPaginacao" => $urlPaginacao, "paginaAtual" => $paginaAtual]);
        GerenciadorMensagens::deletarMensagens();
    }

    public function visualizacaoDenuncia($id)
    {
        global $twig;
        $denunciaInfo = $this->buscarPorId((int) $id);
        $comentarios = $this->comentarioRepository->obterPorDenunciaId(intval($id));
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("denuncias/pagina_denuncia.php", ["denunciaInfo" => $denunciaInfo, "mensagens" => $mensagens, "comentarios" => $comentarios]);
        GerenciadorMensagens::deletarMensagens();
    }

    public function novaDenuncia()
    {
        global $twig;
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("denuncias/edicao_denuncia.php", ["mensagens" => $mensagens]);
        GerenciadorMensagens::deletarMensagens();
    }

    /**
     * @Action
     */
    public function consultar()
    {
        $dados = SimpleRouter::request()->getInputHandler();
        $pesquisa = $dados->value("pesquisa");
        $situacao = $dados->value("situacao");
        $situacao = $situacao == "todas" ? null : $situacao;
        $dataInicial = $dados->value("data_inicial");
        $dataFinal = $dados->value("data_final");
        $limite = $dados->value("limite");
        $paginaAtual = $dados->value("pagina");

        return $this->denunciaRepository->consultar($pesquisa, $situacao, $dataInicial, $dataFinal, $limite, $paginaAtual);
    }

    /**
     * @Action
     */
    public function buscarPorId(int $id)
    {
        return $this->denunciaRepository->buscarPorId($id);
    }

    /**
     * @Action
     */
    public function cadastrarOuAtualizarDenuncia()
    {
        $dados = (object) $_POST;
        $dados->local_foco = $dados->localizacao;
        $dados->latitude_foco = $dados->lat;
        $dados->longitude_foco = $dados->lng;
        unset($dados->localizacao);
        unset($dados->lat);
        unset($dados->lng);

        $urlRedirect = "denuncias.nova";

        if (empty($dados->denuncia_id)) {
            $result = $this->denunciaRepository->cadastrar($dados);
        } else {
            $result = $this->denunciaRepository->atualizar($dados);
            $urlRedirect = "denuncias.editar";
        }

        parent::salvarMensagensDeErro($result, "denuncia_status");
        if (isset($result->errors)) {
            SimpleRouter::response()->redirect(url($urlRedirect));
        }
        GerenciadorMensagens::salvarMensagem(new Mensagem("denuncia_status", "Denúncia enviada com sucesso!", Mensagem::TIPO_SUCESSO));

        SimpleRouter::response()->redirect(url("denuncias"));
    }
}