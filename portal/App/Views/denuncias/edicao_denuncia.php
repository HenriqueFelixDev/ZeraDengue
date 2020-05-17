{% extends "layout/layout.php" %}

{% block titulo %}Edição de Denúncia{% endblock %}

{% block head %}
<!-- HERE MAPS -->
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js" type="text/javascript" charset="utf-8"></script>
<script src="{{ ASSET_URL }}/js/edicao_denuncia.js"></script>
<script src="{{ ASSET_URL }}/js/upload_preview.js"></script>
<link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/edicao_denuncia_style.css" />
{% endblock %}

{% block conteudo %}
{% set denuncia = denunciaInfo.dados %}
<div class="custom-container">
    <h2 class="title">
        {% if denuncia.denuncia_id %}
            Editar Denúncia
        {% else %}
            Nova Denúncia
        {% endif %}
    </h2>

    <div class="vertical-spacer"></div>

    <form action="{{ BASE_URL }}/denuncias/salvar" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="denuncia_id" id="denuncia_id" value="{{ denuncia.denuncia_id }}">

        <input type="text" name="assunto" id="assunto" placeholder="assunto" maxlength="128" required value="{{denuncia.assunto}}" />
        {% if mensagens.assunto != null %}
            <div class="message {{ mensagens.assunto.tipo }}-message">
                {{ mensagens.assunto.mensagem }}
            </div>
        {% endif %}

        <textarea name="descricao" id="descricao" rows="5" placeholder="Descrição" maxlength="5000" required>{{ denuncia.descricao }}</textarea>
        {% if mensagens.descricao != null %}
            <div class="message {{ mensagens.descricao.tipo }}-message">
                {{ mensagens.descricao.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <div id="fotos-container">
            <h3>Fotos</h3>
            <div id="fotos-list">
                <label for="fotos" class="photo btn">
                    <div class="btn-add-photo">
                        Escolher Fotos
                        
                        <div class="vertical-spacer"></div>

                        <i class="fa fa-image fa-lg"></i>
                    </div>
                </label>
            </div>
            
            <input type="file" name="fotos[]" id="fotos" multiple required accept="image/*" />
        </div>
        {% if mensagens.fotos != null %}
            <div class="message {{ mensagens.fotos.tipo }}-message">
                {{ mensagens.fotos.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer-3x"></div>

        <div id="suggestion-container">
            <input type="search" name="localizacao" id="localizacao" placeholder="Localização" autocomplete="off" value="{{ denuncia.local_foco }}" />
            <input type="hidden" name="lat" id="lat" value="{{ denuncia.latitude_foco }}" />
            <input type="hidden" name="lng" id="lng" value="{{ denuncia.longitude_foco }}" />
        </div>
        {% if mensagens.local_foco != null %}
            <div class="message {{ mensagens.local_foco.tipo }}-message">
                {{ mensagens.local_foco.mensagem }}
            </div>
        {% endif %}
        {% if mensagens.coordenadas != null %}
            <div class="message {{ mensagens.coordenadas.tipo }}-message">
                {{ mensagens.coordenadas.mensagem }}
            </div>
        {% endif %}
        
        
        <div class="map-container">
            <div id="map"></div>
        </div>

        <br/>

        <div class="vertical-spacer"></div>
        
        <button class="btn-primary">Salvar</button>
    </form>
</div>
{% endblock %}

{% block footer %}

    {% if mensagens.denuncia_status != null %}
        <script type="text/javascript">
            function exibirMensagem() {
                showModal({
                    title: 'Status da denúncia',
                    content: '{{ mensagens.denuncia_status.mensagem }}',
                    buttonType: 'ok',
                    onConfirm: () => {
                        window.location.href = url
                    },
                    onCancel: () => {
                        closeModal()
                    } 
                })
            }
        </script>
    {% endif %}

{% endblock %}