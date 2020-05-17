{% extends "layout/layout.php" %}
   
{% block titulo %}Minha Conta{% endblock %}

{% block conteudo %}
<div class="custom-container">
    <h2 class="title">Minha Conta</h2>

    <form action="{{ BASE_URL }}/atualizar" method="POST">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome" placeholder="Nome" maxlength="128" required value="{{ dados.nome }}" />
        {% if mensagens.nome != null %}
            <div class="message {{ mensagens.nome.tipo }}-message">
                {{ mensagens.nome.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <label for="email">E-mail</label>
        <span>{{ dados.email }}</span>

        <div class="vertical-spacer"></div>

        <label for="senha">Senha</label>
        
        <div class="vertical-spacer"></div>
        
        <label for="cpf">CPF</label>
        <div>{{ dados.cpf }}</div>
        
        <div class="vertical-spacer"></div>

        <label for="rg">RG</label>
        <div>{{ dados.rg }}</div>
        
        <div class="vertical-spacer"></div>

        <label for="cep">CEP</label>
        <input type="text" name="cep" id="cep" placeholder="CEP" maxlength="8" required value="{{ dados.endereco.cep }}" />
        {% if mensagens.cep != null %}
            <div class="message {{ mensagens.cep.tipo }}-message">
                {{ mensagens.cep.mensagem }}
            </div>
        {% endif %}
        
        <div class="vertical-spacer"></div>
        <input type="hidden" name="endereco_id" value="{{ dados.endereco.endereco_id }}" />

        <label for="logradouro">Logradouro</label>
        <input type="text" name="logradouro" id="logradouro" placeholder="Logradouro" maxlength="128" required value="{{ dados.endereco.logradouro }}" />
        {% if mensagens.logradouro != null %}
            <div class="message {{ mensagens.logradouro.tipo }}-message">
                {{ mensagens.logradouro.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <label for="numero">Número</label>
        <input type="text" name="numero" id="numero" placeholder="Número" maxlength="8" required value="{{ dados.endereco.numero }}" />
        {% if mensagens.numero != null %}
            <div class="message {{ mensagens.numero.tipo }}-message">
                {{ mensagens.numero.mensagem }}
            </div>
        {% endif %}
        
        <div class="vertical-spacer"></div>

        <label for="bairro">Bairro</label>
        <input type="text" name="bairro" id="bairro" placeholder="Bairro" maxlength="64" required value="{{ dados.endereco.bairro }}" />
        {% if mensagens.bairro != null %}
            <div class="message {{ mensagens.bairro.tipo }}-message">
                {{ mensagens.bairro.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <label for="cidade">Cidade</label>
        <input type="text" name="cidade" id="cidade" placeholder="Cidade" maxlength="64" required value="{{ dados.endereco.cidade }}" />
        {% if mensagens.cidade != null %}
            <div class="message {{ mensagens.cidade.tipo }}-message">
                {{ mensagens.cidade.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <label for="estado">Estado</label>
        <select name="estado" id="estado" required >
            <option value="-1" selected disabled>Selecione um estado</option>
            {% for Estado in ESTADOS %}
                <option value="{{ Estado|lower }}" {% if Estado == dados.endereco.estado|upper %}selected{% endif %}>{{ Estado }}</option>
            {% endfor %}
        </select>
        {% if mensagens.estado != null %}
            <div class="message {{ mensagens.estado.tipo }}-message">
                {{ mensagens.estado.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <label for="complemento">Complemento</label>
        <input type="text" name="complemento" id="complemento" placeholder="Complemento" maxlength="128" value="{{ dados.endereco.complemento }}" />
        {% if mensagens.complemento != null %}
            <div class="message {{ mensagens.complemento.tipo }}-message">
                {{ mensagens.complemento.mensagem }}
            </div>
        {% endif %}

        <div class="vertical-spacer"></div>

        <div id="phones">
            <label for="tel1">Telefones</label>
            {% for telefone in dados.telefones %}
                {% set i = loop.index - 1 %}
                <div id="phone{{ i }}" data-index="{{ i }}" class="row">
                    <input type="hidden" name="telefone_id[{{ i }}]" value="{{ telefone.telefone_id }}" />
                    <div class="col-2">
                        <input type="tel" name="ddd[]" placeholder="DDD" maxlength="2" required value="{{ telefone.ddd }}" />
                    </div>
                    <div class="col">
                        <input type="tel" name="tel[]" id="tel{{ i }}" placeholder="Telefone" maxlength="11" required value="{{ telefone.telefone }}" />
                    </div>
                    
                    <div class="col">
                        {% if i > 0 %}
                            <button type="button" class="btn-remove-phone" onclick="removePhone('{{ i }}')"><i class="fa fa-minus"></i></button>
                        {% endif %}
                    </div>
                    
                </div>
            {% endfor %}
            
        </div>
        {% if mensagens.telefone != null %}
            <div class="message {{ mensagens.telefone.tipo }}-message">
                {{ mensagens.telefone.mensagem }}
            </div>
        {% endif %}

        <button type="button" onclick="addPhone()"><i class="fa fa-plus"></i></button>

        <div class="vertical-spacer"></div>
        
        <button class="btn-primary">Salvar Alterações</button>
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
                    onCancel: () => {
                        closeModal()
                    } 
                })
        </script>
    {% endif %}
{% endblock %}