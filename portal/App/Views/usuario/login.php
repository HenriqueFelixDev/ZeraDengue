{% extends "layout/layout.php" %}

{% block titulo %}Login{% endblock %}

{% block head %}
<link rel="stylesheet" type="text/css" href="{{ ASSET_URL }}/css/pages/login_style.css" />
{% endblock %}

{% block menu %}{% endblock %}

{% block conteudo %}
<div id="login-content" class="fullscreen">
    <div class="login-container card px-5 py-5 col-12 col-sm-7 col-md-4 col-lg-3 offset-0 offset-sm-3 offset-md-7 offset-lg-8 my-5">
        <h2>Login</h2>
        <form action="{{ BASE_URL }}/entrar" method="POST">
            <input type="email" name="email" id="email" placeholder="E-mail" maxlength="64" required />
            {% if mensagens.email != null %}
            <div class="message {{ mensagens.email.tipo }}-message">
                {{ mensagens.email.mensagem }}
            </div>
            {% endif %}
            
            <div class="text-decorated">
                <input type="password" name="senha" id="senha" placeholder="Senha" maxlength="32" required />
                <button type="button" style="padding-bottom: 13px;" onclick="togglePassword('#senha')">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
            {% if mensagens.senha != null %}
            <div class="message {{ mensagens.senha.tipo }}-message">
                {{ mensagens.senha.mensagem }}
            </div>
            {% endif %}

            {% if mensagens.login != null %}
            <div class="message {{ mensagens.login.tipo }}-message">
                {{ mensagens.login.mensagem }}
            </div>
            {% endif %}
            
            <div class="vertical-spacer"></div>
    
            <button class="btn-block btn-primary">Entrar</button>
            <div class="vertical-spacer"></div>
            <p>
                Ainda não possui uma conta? <a href="{{ BASE_URL }}/cadastro">Cadastre-se já</a>
            </p>
        </form>
    </div>
</div>

{% endblock %}

{% block footer %}
<script src="{{ ASSET_URL }}/js/scripts.js"></script>

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