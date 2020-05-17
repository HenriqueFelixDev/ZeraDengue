<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Zer@Dengue - {% block titulo %}{% endblock %}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- LINKS -->
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/blocks/elements.css" />
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/blocks/form.css" />
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/blocks/buttons.css" />
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/all.css" />
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/reset.css" />

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/bootstrap-grid.min.css" />
    
    <!-- FONT AWESOME -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    {% block head %}{% endblock %}
</head>
<body>
    <header class="app-bar">
        <h1 class="logo"><a href="{{ BASE_URL }}/">Zer@Dengue</a></h1>

        {% block menu %}
        <nav class="menu">
            <ul>
                <li><a href="{{ BASE_URL }}/" title="Início"><i class="fa fa-home fa-lg"></i></a></li>
                <li><a href="{{ BASE_URL }}/denuncias" title="Denúncias"><i class="fa fa-info-circle fa-lg"></i></a></li>
                <li><a href="#">{{ USUARIO }} <i class="fa fa-caret-down arrow"></i></a>
                    <ul>
                        <li><a href="{{ BASE_URL }}/conta"><i class="fa fa-cog"></i> Minha Conta</a></li>
                        <li><a href="{{ BASE_URL }}/deslogar"><i class="fa fa-sign-out"></i> Sair</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        {% endblock %}
    </header>
<!-- ******************* CONTEUDO ****************************-->
{% block conteudo %} {% endblock %}
<!-- ******************* FIM CONTEUDO ****************************-->
<footer>
    <div class="footer-bar">
        <nav class="menu">
            <h2>Páginas do Governo</h2>
            <ul>
                <li><a href="https://saude.gov.br/">Ministério da Saúde</a></li>
                <li><a href="https://www.gov.br/pt-br">Governo Federal</a></li>
                <li>
                    <img src="https://www.saude.gov.br/templates/padraogoverno01/images/brasil.png" alt="Slogan do governo federal: Pátria amada Brasil">
                </li>
            </ul>
        </nav>

        <nav class="menu">
            <h2>Redes Sociais</h2>
            <ul>
                <li><a href="http://twitter.com/minsaude">Twitter</a></li>
                <li><a href="http://www.youtube.com/minsaudebr">Youtube</a></li>
                <li><a href="https://www.facebook.com/minsaude">Facebook</a></li>
                <li><a href="https://www.flickr.com/photos/ministeriodasaude/albums">Flickr</a></li>
                <li><a href="https://instagram.com/minsaude/">Instagram</a></li>
            </ul>
        </nav>

        <nav class="menu">
            <h2>Links Relacionados</h2>
            <ul>
                <li><a href="https://drauziovarella.uol.com.br/doencas-e-sintomas/dengue/">Dráuzio Varella</a></li>
                <li><a href="https://pt.wikipedia.org/wiki/Dengue">Wikipédia</a></li>
                <li><a href="https://www.saude.gov.br/saude-de-a-z/dengue">Ministério da Saúde</a></li>
                <li><a href="https://www.saude.gov.br/campanhas/45789-combate-ao-mosquito-2019-2020">Campanha de Combate à Dengue 2019/2020</a></li>
            </ul>
        </nav>

        <nav class="menu">
            <h2>Contato</h2>
            <ul>
                <li>Esplanada dos Ministérios, bloco G</li>
                <li>2º andar, sala 210</li>
                <li>CEP 70058-900, Brasília - DF</li>
                <li>Email: <a href="mailto:ctisus2017@saude.gov.br">ctisus2017@saude.gov.br</a></li>
            </ul>
        </nav>
    </div>
    <p class="copyright">Desenvolvido por Henrique Félix - 2020</p>
</footer>

<div class="modal-container modal-hide" data-modal="container">
        <div class="modal" data-modal="modal">
            <header id="modal-header">
                <p id="title"></p>
                <button class="btn-close" data-modal-action="close"><i class="fa fa-close"></i></button>
            </header>
            <section id="modal-content" class="content">
                <p> Mensagem </p>
            </section>
            <footer id="modal-footer">
                <div id="container-btn-ok">
                    <button data-modal-action="close">OK</button>
                </div>

                <div id="container-btn-yes-cancel">
                    <button data-modal-action="yes">Sim</button>
                    <button class="btn-danger" data-modal-action="cancel">Cancelar</button>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="{{ ASSET_URL }}/js/modal.js"></script>
{% block footer %}{% endblock %}

</body>
</html>