{% extends "layout/layout.php" %}

{% block titulo %}Cadastro{% endblock %}

{% block menu %}{% endblock %}

{% block conteudo %}
<div class="card col-12 col-sm-8 col-lg-6 offset-0 offset-sm-2 offset-lg-3 my-5 p-5">
    <h2 class="title">Cadastro</h2>
    <div class="vertical-spacer"></div>
    <form action="{{ BASE_URL }}/cadastrar" method="POST">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome" placeholder="Nome Completo" maxlenght="128" autofocus required />
        {% if mensagens.nome != null %}
            <div class="message {{ mensagens.nome.tipo }}-message">
                {{ mensagens.nome.mensagem }}
            </div>
        {% endif %}

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="E-mail" maxlength="64" required />
        {% if mensagens.email != null %}
            <div class="message {{ mensagens.email.tipo }}-message">
                {{ mensagens.email.mensagem }}
            </div>
        {% endif %}

        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" placeholder="Senha" maxlength="32" required />
        {% if mensagens.senha != null %}
            <div class="message {{ mensagens.senha.tipo }}-message">
                {{ mensagens.senha.mensagem }}
            </div>
        {% endif %}
        
        <div class="row">
            <div class="col-12 col-md-6">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" placeholder="CPF" maxlength="11" required />
            </div>

            <div class="col-12 col-md-6">
                <label for="rg">RG</label>
                <input type="text" name="rg" id="rg" placeholder="RG" maxlength="16" required />
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                {% if mensagens.cpf != null %}
                    <div class="message {{ mensagens.cpf.tipo }}-message">
                        {{ mensagens.cpf.mensagem }}
                    </div>
                {% endif %}
            </div>
            <div class="col-12 col-md-6">
                {% if mensagens.rg != null %}
                    <div class="message {{ mensagens.rg.tipo }}-message">
                        {{ mensagens.rg.mensagem }}
                    </div>
                {% endif %}
            </div>
        </div>

        <label for="cep">CEP</label>
        <input type="text" name="cep" id="cep" placeholder="CEP" maxlength="8" required />
        {% if mensagens.cep != null %}
            <div class="message {{ mensagens.cep.tipo }}-message">
                {{ mensagens.cep.mensagem }}
            </div>
        {% endif %}

        <div class="row">
            <div class="col-12 col-md-8 col-lg-9">
                <label for="logradouro">Logradouro</label>
                <input type="text" name="logradouro" id="logradouro" placeholder="Logradouro" maxlength="128" required />
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" placeholder="Número" maxlength="8" required />
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-9">
                {% if mensagens.logradouro != null %}
                    <div class="message {{ mensagens.logradouro.tipo }}-message">
                        {{ mensagens.logradouro.mensagem }}
                    </div>
                {% endif %}
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                {% if mensagens.numero != null %}
                    <div class="message {{ mensagens.numero.tipo }}-message">
                        {{ mensagens.numero.mensagem }}
                    </div>
                {% endif %}
            </div>
        </div>

        <label for="bairro">Bairro</label>
        <input type="text" name="bairro" id="bairro" placeholder="Bairro" maxlength="64" required />
        {% if mensagens.bairro != null %}
            <div class="message {{ mensagens.bairro.tipo }}-message">
                {{ mensagens.bairro.mensagem }}
            </div>
        {% endif %}

        <label for="cidade">Cidade</label>
        <input type="text" name="cidade" id="cidade" placeholder="Cidade" maxlength="64" required />
        {% if mensagens.cidade != null %}
            <div class="message {{ mensagens.cidade.tipo }}-message">
                {{ mensagens.cidade.mensagem }}
            </div>
        {% endif %}

        <label for="estado">Estado</label>
        <select name="estado" id="estado" required >
            <option value="-1" selected disabled>Selecione um estado</option>
            {% for Estado in ESTADOS %}
                <option value="{{ Estado|lower }}">{{ Estado }}</option>
            {% endfor %}
        </select>
        {% if mensagens.estado != null %}
            <div class="message {{ mensagens.estado.tipo }}-message">
                {{ mensagens.estado.mensagem }}
            </div>
        {% endif %}

        <label for="complemento">Complemento</label>
        <input type="text" name="complemento" id="complemento" placeholder="Complemento" maxlength="128" />
        {% if mensagens.complemento != null %}
            <div class="message {{ mensagens.complemento.tipo }}-message">
                {{ mensagens.complemento.mensagem }}
            </div>
        {% endif %}

        <div id="phones">
            <label for="tel1">Telefones</label>
            <div id="phone0" data-index="0" class="row">
                <div class="col-2">
                    <input type="tel" name="ddd[]" placeholder="DDD" maxlength="2" required value="{{ telefone.ddd }}" />
                </div>
                <div class="col">
                    <input type="tel" name="tel[]" id="tel0" placeholder="Telefone 1" maxlength="11" required />
                </div>
                    
                <div class="col"></div>
                    
            </div>
            
        </div>
        {% if mensagens.telefone != null %}
            <div class="message {{ mensagens.telefone.tipo }}-message">
                {{ mensagens.telefone.mensagem }}
            </div>
        {% endif %}

        <button type="button" onclick="addPhone()"><i class="fa fa-plus"></i></button>

        <div class="vertical-spacer"></div>
        
        <button class="btn-primary">Cadastrar</button>
    </form>
</div>
{% endblock %}

{% block footer %}
<script src="{{ ASSET_URL }}/js/cadastro.js"></script>

    {% if mensagens.usuario_status != null %}
        <script type="text/javascript">
            showModal({
                title: 'Status da denúncia',
                content: '{{ mensagens.usuario_status.mensagem }}',
                buttonType: 'ok',
                onConfirm: () => {
                    window.location.href = url
                },
                onCancel: closeModal
            })
        </script>
    {% endif %}
{% endblock %}