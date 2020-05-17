{% extends "layout/layout.php" %}

{% block titulo %}Página Inicial{% endblock %}

{% block head %}
<link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/home_style.css" />
{% endblock %}

{% block conteudo %}
<div class="custom-container">

    <h2 class="title">Artigos Relacionados</h2>

    <div class="vertical-spacer"></div>

    <div class="row">
        <div class="col-12 col-md-6 p-0">
            <div class="article-item-wrapper mr-0 mr-md-1">
                <a href="https://www.youtube.com/watch?v=HRN97yV-ROM" target="_blank" rel="noopener noreferrer">
                    <div id="art1" class="article-item"></div>
                    <h2>Como evitar criadouros do mosquito Aedes aegypti na caixa d'água</h2>
                </a>
            </div>
        </div>
        
        <div class="col-12 col-md-6 p-0 my-2 my-md-0">
            <div class="article-item-wrapper ml-0 ml-md-1">
                <a href="https://www.agazeta.com.br/revista-ag/vida/verao-e-propicio-para-dengue-chikungunya-e-zika-veja-como-prevenir-0120" target="_blank" rel="noopener noreferrer">
                    <div id="art2" class="article-item"></div>
                    <h2>Verão é propício para dengue, chikungunya e zika; veja como prevenir</h2>
                </a>
            </div>
        </div>

        <div class="article-item-wrapper col-12 p-0 mt-0 mt-md-2">
            <a href="https://g1.globo.com/rj/regiao-dos-lagos/especial-publicitario/brk-ambiental/presenca-que-transforma/noticia/2020/01/31/dengue-acoes-de-saneamento-basico-ajudam-na-prevencao-da-doenca.ghtml" target="_blank" rel="noopener noreferrer">
                <div id="art3" class="article-item"></div>
                <h2>Dengue: ações de saneamento básico ajudam na prevenção da doença</h2>
            </a>
        </div>
    </div>
</div>
{% endblock %}