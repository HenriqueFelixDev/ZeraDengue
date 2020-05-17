{% extends "layout/layout.php" %}

{% block titulo %}Denúncia{% endblock %}

{% block head %}
<script src="{{ ASSET_URL }}/js/slider.js"></script>
<link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/pagina_denuncia_style.css" />
{% endblock %}

{% block conteudo %}
{% set denuncia = denunciaInfo.dados %}
<div class="custom-container">
    <h2 class="title">
        {{ denuncia.assunto  }}
    </h2>
    <span class="hint"><i class="fa fa-map-marker"></i> {{ denuncia.local_foco  }}</span>
    <br/>
    <div class="ship">{{ formatarSituacao(denuncia.situacao)  }}</div>

    <div class="vertical-spacer"></div>

    <div class="slider">
        <div class="slider-handler slider-handler-previous" data-handler="previous">
            <i class="fa fa-caret-left fa-3x"></i>
        </div>
        <div class="slider-handler slider-handler-next" data-handler="next">
            <i class="fa fa-caret-right fa-3x"></i>
        </div>
        <div class="slider-mask">
            <div class="slider-slider-content" data-slider="slider">
                {% for foto in denuncia.fotos %}
                <div class="slider-item" data-slider-item="0">
                    <img src="http://localhost/projetos/zeradengue/api/v1/App/Resources/Images/{{ foto.foto }}" alt="">
                </div>
                {% endfor %}
            </div>
        </div>
        <div class="slider-dots">
            {% for foto in denuncia.fotos %}
                <div class="slider-dot" data-slider-item-index="{{ loop.index - 1 }}"></div>
            {% endfor %}
        </div>
    </div>

    <div class="vertical-spacer-2x"></div>

    <h3 class="subtitle">Descrição</h3>
    <p>{{ denuncia.descricao }}</p>

    <div class="vertical-spacer-2x"></div>

    <h3 class="subtitle">Localização</h3>
    <img src="https://image.maps.ls.hereapi.com/mia/1.6/mapview?apiKey=tIMJ9_pz8Vv5Pv-kna6_HJgBFdQESEoQLssdF0PWZCA&c={{ denuncia.latitude_foco }},{{ denuncia.longitude_foco }}&t=0&z=15&w=350&h=250&nodot'" id="map" alt="">

    <h2 class="comment-title">Comentários ({{ comentarios.totalItens }})</h2>
    <div class="vertical-spacer"></div>
    <form action="{{ BASE_URL }}/denuncias/{{ denuncia.denuncia_id }}/comentarios" method="POST">
        <textarea name="comentario" id="comentario" rows="4" placeholder="Escreva um comentário..."></textarea>
        {% if mensagens.comentario != null %}
            <div class="message {{ mensagens.comentario.tipo }}-message">
                {{ mensagens.comentario.mensagem }}
            </div>
        {% endif %}
        <button class="btn-secondary">Enviar</button>
    </form>

    {% if comentarios.totalItens > 0%}
        {% for comentarioInfo in comentarios.itens %}
            {% set comentario = comentarioInfo.dados %}
            <article class="comment">
                <h4>{{ comentario.autor }}</h4>
                <span class="hint">{{ comentario.data_publicacao|date("d/m/Y H:i:s") }}</span>
                <div class="vertical-spacer"></div>
                <p>{{ comentario.comentario }}</p>
                {% if comentario.usuario_id == USUARIO_ID %}
                    <button class="btn-mini" onclick="abrirEdicaoComentario('{{ BASE_URL }}/denuncias/{{ comentario.denuncia_id }}/comentarios/{{ comentario.comentario_id }}', '{{ comentario.comentario }}')">Editar</button>
                    <button class="btn btn-danger btn-mini" onclick="excluirComentario('{{ BASE_URL }}/denuncias/{{ denuncia.denuncia_id }}/comentarios/{{ comentario.comentario_id }}/excluir')">Excluir</button>
                {% endif %}
            </article>
        {% endfor %}
    {% else %}
        <br/>
        <p>Nenhum comentário publicado ainda. Seja o primeiro a comentar!</p>
    {% endif %}
</div>
{% endblock %}

{% block footer %}

    <script type="text/javascript">

        function abrirEdicaoComentario(url, comentario) {
            showModal({
                title: 'Editar comentário',
                content: '<form action="' + url + '" method="POST"><input type="hidden" name="_method" value="PATCH" /><textarea name="comentario" rows=5 cols=50>' + comentario + '</textarea><button class="btn-primary">Salvar</button></form>',
                buttonType: 'none',
                onConfirm: () => {
                    window.location.href = url
                },
                onCancel: () => {
                    closeModal()
                } 
            })
        }

        function excluirComentario(url) {
            showModal({
                title: 'Exclusão de comentário',
                content: 'Deseja realmente excluir este comentário?',
                buttonType: 'yes_cancel',
                onConfirm: () => {
                    window.location.href = url
                },
                onCancel: () => {
                    closeModal()
                } 
            })
            
        }
    </script>
    
    {% if mensagens.comentario_status != null %}
    <script type="text/javascript">
        showModal({
            title: 'Status do comentário',
            content: '{{ mensagens.comentario_status.mensagem }}',
            buttonType: 'ok'
        })
    </script>
    {% endif %}
{% endblock %}