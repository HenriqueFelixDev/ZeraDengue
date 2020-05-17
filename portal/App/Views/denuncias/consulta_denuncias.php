{% extends "layout/layout.php" %}

{% block titulo %}Denúncias{% endblock %}

{% block head %}
<link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/consulta_denuncias_style.css" />
{% endblock %}

{% block conteudo %}
<div class="container">
    <div class="row">
        <div id="filters" class="col-12 col-md-4 col-lg-3">
            <form action="" method="">
                <div class="text-decorated">
                    <input type="search" name="pesquisa" id="pesquisa" placeholder="Pesquisar..." value="{{ filtros.pesquisa }}" />
                    <button>
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <div class="vertical-spacer"></div>

                <label for="data_inicial">Data de Publicação</label>
                <div class="form-inline">
                    <label for="data_inicial">De</label>
                    <input type="date" name="data_inicial" id="data_inicial" value="{{ filtros.data_inicial }}" />
                </div>

                <div class="form-inline">
                    <label for="data_final">Até</label>
                    <input type="date" name="data_final" id="data_final" value="{{ filtros.data_final }}" />
                </div>

                <div class="vertical-spacer"></div>

                <label for="situacao">Situação</label>
                <select name="situacao" id="situacao">
                    {% for situacao, nome in SITUACOES %}
                        <option value="{{ situacao }}" {% if filtros.situacao == situacao %}selected{% endif %}>{{ nome }}</option>
                    {% endfor %}
                </select>

                <div class="vertical-spacer"></div>

                <label for="limite">Denúncias por página</label>
                <select name="limite" id="limite">
                    {% for limite in LIMITE_ITENS_PAGINA %}
                        <option value="{{ limite }}" {% if filtros.limite == limite %}selected{% endif %}>{{ limite }}</option>
                    {% endfor %}
                </select>

                <div class="vertical-spacer"></div>

                <button class="btn-block btn-primary">Filtrar</button>

                <div class="vertical-spacer"></div>

                <button type="reset" class="btn-block btn-secondary">Limpar Filtros</button>
            </form>

            <div class="vertical-spacer-3x"></div>
        </div>

        
        <div class="col-12 col-md-8 col-lg-8 offset-0 offset-lg-1">
            <h2 class="title">Denúncias</h2>
            <div class="vertical-spacer"></div>
            <a href="{{ BASE_URL }}/denuncias/nova" class="btn btn-primary">Nova Denúncia</a>

            <div class="vertical-spacer-2x"></div>

            <p class="hint">{{ denunciasInfo.totalItens }} resultado(s) encontrados(s)!</p>

            <div class="responsive-table">
            {% if (denunciasInfo.itens) %}
                <table class="complaint-items">
                    <thead>
                        <tr>
                            <th>Assunto</th>
                            <th>Data</th>
                            <th>Local</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>

                    {% for denunciaInfo in denunciasInfo.itens %}
                        {% set denuncia = denunciaInfo.dados %}

                        <tr class="card" onclick="location.href='{{ BASE_URL }}/denuncias/{{ denuncia.denuncia_id }}'">
                            <td class="subject" title="{{ denuncia.dados.assunto }}">
                                {{ denuncia.assunto }}
                            </td>
            
                            <td>{{ denuncia.data_publicacao|date('d/m/Y') }}</td>
            
                            <td class="local" title="{{ denuncia.dados.local_foco }}">
                                {{ denuncia.local_foco }}
                            </td>
                            
                            <td>
                                <div class="ship">{{ formatarSituacao(denuncia.situacao) }}</div>
                            </td>
                        </tr>
                        
                    {% endfor %}
                    </tbody>
                </table>

                {% else %}
                    <div class="vertical-spacer"></div>
                    <p>Nenhuma denúncia encontrada!</p>
                {% endif %}

            </div>
        {% set paginas = ceil(denunciasInfo.totalItens/denunciasInfo.itensPorPagina) %}
        
        {% if paginas > 1 %}
            <div class="pagination">
                {% if denunciasInfo.paginaAnterior != null %}
                    <a href="{{ urlPaginacao }}{{ denunciasInfo.paginaAnterior }}" class="pagination-item"><i class="fa fa-caret-left"></i></a>
                {% endif %}
                {% for i in range(1, paginas) %}

                    <a class="pagination-item {% if i == paginaAtual %}disable{% endif %}" 
                        href="{% if i == paginaAtual %}
                            javascript:void(0)
                        {% else %}
                            {{ urlPaginacao }}{{ i }}
                        {% endif %}">{{i}}</a>

                {% endfor %}
                    {% if denunciasInfo.proximaPagina != null %}
                        <a href="{{ urlPaginacao }}{{ denunciasInfo.proximaPagina }}" class="pagination-item"><i class="fa fa-caret-right"></i></a>
                    {% endif %}
            </div>
        {% endif %}

        </div>
    </div>
</div>
{% endblock %}

{% block footer %}

    {% if mensagens.denuncia_status != null %}
        <script type="text/javascript">
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
        </script>
    {% endif %}

{% endblock %}