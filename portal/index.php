<?php

require_once "vendor/autoload.php";

use Pecee\SimpleRouter\SimpleRouter;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

require_once "routes.php";

session_start();

// GLOBAIS
define("BASE_URL", "http://localhost/projetos/zeradengue/portal");
define ("ASSET_URL", BASE_URL."/Public/Assets");
define("ESTADOS", [
    "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES",
    "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR",
    "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC",
    "SP", "SE", "TO"
]);
define("SITUACOES", [
    "todas" => "Todas",
    "aguardando_analise" => "Aguardando Análise",
    "sob_analise" => "Sob Análise",
    "resolvida" => "Resolvida"
]);
define("LIMITE_ITENS_PAGINA", [15, 30, 45, 60]);
define("USUARIO", array_key_exists("autenticacao", $_SESSION) ? preg_split("/\s/", $_SESSION["autenticacao"]->nome)[0] : "");
define("USUARIO_ID", array_key_exists("autenticacao", $_SESSION) ? $_SESSION["autenticacao"]->id : null);

// FUNÇÕES
$ceil = new TwigFunction('ceil', 'ceil');
$formatarSituacao = new TwigFunction("formatarSituacao", function ($situacao) {
    return key_exists(trim($situacao), SITUACOES) ? SITUACOES[$situacao] : "Desconhecida";
});


$loader = new FileSystemLoader("App/Views");
//$twig = new Environment($loader, ["cache" => "App/Views/Cache"]);
$twig = new Environment($loader);
$twig->addGlobal("BASE_URL", BASE_URL);
$twig->addGlobal("ASSET_URL", ASSET_URL);
$twig->addGlobal("ESTADOS", ESTADOS);
$twig->addGlobal("SITUACOES", SITUACOES);
$twig->addGlobal("LIMITE_ITENS_PAGINA", LIMITE_ITENS_PAGINA);
$twig->addGlobal("USUARIO", USUARIO);
$twig->addGlobal("USUARIO_ID", USUARIO_ID);

$twig->addFunction($formatarSituacao);
$twig->addFunction($ceil);

SimpleRouter::start();