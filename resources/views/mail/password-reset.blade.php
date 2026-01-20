<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #a2d4f5, #75c3f2);
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            font-size: 28px;
            color: #00a7c7;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin-top: 30px;
            background-color: #00a7c7;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    @if($logoUrl)
        <div class="header">
            <img src="{{ $logoUrl }}" alt="Logo da empresa {{ $firmName }}">
            <div>{{ $firmName }}</div>
        </div>
    @else
        <div class="header">
            {{ $firmName }}
        </div>
    @endif

    <div class="header">
        Recuperação de Senha
    </div>

    <div class="content">
        <p>Olá!</p>
        <p>Para recuperar sua senha, clique no botão abaixo:</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="button" target="_blank">Redefinir Senha</a>
        </div>

        <p>Este link expirará em 60 minutos.</p>
        <p>Se você não solicitou esta recuperação, por favor, ignore este e-mail.</p>
        <p>Atenciosamente,<br>{{ $firmName }}</p>
    </div>

    <div class="footer">
        © 2024 {{ $firmName }}. Todos os direitos reservados.
    </div>
</div>
</body>
</html>
