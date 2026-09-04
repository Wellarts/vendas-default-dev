<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NZ3T2B4DW3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-NZ3T2B4DW3');
    </script>

    <meta charset="UTF-8">
    <title>Catálogo de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonte moderna do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .header-delicaty {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 0 0 18px 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 24px 40px;
            margin-bottom: 18px;
        }

        .header-logo {
            display: flex;
            align-items: center;
        }

        .header-delicaty img {
            height: 70px;
            width: auto;
            border-radius: 12px;
            background: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .header-title {
            text-align: center;
            flex: 1;
        }

        .header-nome {
            font-size: 2rem;
            font-weight: 700;
            color: #ff9800;
            letter-spacing: 1px;
            margin: 0;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            color: #bdbdbd;
            font-size: 0.85rem;
        }

        .header-instagram {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-tel {
            font-weight: 500;
        }

        .busca-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .busca-form {
            display: flex;
            gap: 10px;
        }

        .busca-input {
            flex: 1;
            padding: 12px;
            border: 2px solid #ff9800;
            border-radius: 8px;
            font-size: 1rem;
        }

        .busca-btn {
            background: #ff9800;
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .catalogo-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }

        .produto-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .produto-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .produto-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .produto-info {
            padding: 16px;
            text-align: center;
        }

        .produto-nome {
            font-size: 1.1rem;
            color: #ff9800;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .produto-codbar {
            font-size: 0.95rem;
            color: #757575;
            margin-bottom: 10px;
        }

        .produto-preco {
            font-size: 1.2rem;
            color: #444;
            font-weight: 600;
        }

        .categorias-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin: 0 auto 20px auto;
            max-width: 1200px;
            padding: 0 20px;
        }

        .categoria-btn {
            background: #fff;
            border: 1px solid #ff9800;
            color: #ff9800;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .categoria-btn.active,
        .categoria-btn:hover {
            background: #ff9800;
            color: #fff;
        }

        /* Modal para zoom da imagem */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
            border-radius: 10px;
        }

        .modal-content,
        .close {
            animation: zoom 0.3s ease-in-out;
        }

        @keyframes zoom {
            from {
                transform: scale(0.7);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            top: 30px;
            right: 50px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .header-delicaty {
                flex-direction: column;
                gap: 12px;
                padding: 18px 20px;
            }

            .header-title {
                order: 2;
            }

            .header-logo {
                order: 1;
            }

            .header-info {
                order: 3;
                align-items: center;
            }

            .header-nome {
                font-size: 1.3rem;
            }

            .produto-img {
                height: 160px;
            }

            .catalogo-container {
                padding: 20px;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 16px;
            }
        }

        /* PAGINAÇÃO PERSONALIZADA - NOVA LÓGICA */
        .pagination-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
            margin: 30px 0 50px 0;
            padding: 0;
            list-style: none;
            flex-wrap: wrap;
        }

        .pagination-custom li {
            margin: 0;
            padding: 0;
        }

        .pagination-custom a,
        .pagination-custom span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            border-radius: 4px;
            border: 1px solid #ddd;
            color: #666;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            line-height: 1;
            padding: 0 5px;
            background: white;
        }

        /* Setas extremamente pequenas usando texto simples */
        .pagination-custom .arrow {
            font-size: 10px;
            min-width: 24px;
            width: 24px;
            height: 24px;
            padding: 0;
        }

        /* Página ativa */
        .pagination-custom .active span {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
            font-weight: 600;
        }

        /* Hover */
        .pagination-custom a:hover {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
        }

        /* Setas desabilitadas */
        .pagination-custom .disabled span {
            opacity: 0.3;
            cursor: default;
            background: #f5f5f5;
            color: #999;
            border-color: #eee;
        }

        /* Três pontos */
        .pagination-custom .dots {
            border: none;
            background: transparent;
            font-size: 14px;
            min-width: 20px;
            color: #999;
        }

        /* Contêiner da paginação */
        .pagination-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Contador de resultados */
        .resultados-info {
            text-align: center;
            color: #757575;
            font-size: 0.85rem;
            margin: 10px 0 20px 0;
            padding: 0 20px;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state p {
            font-size: 1.2rem;
            color: #757575;
            margin: 0;
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .pagination-custom {
                gap: 2px;
            }

            .pagination-custom a,
            .pagination-custom span {
                min-width: 26px;
                height: 26px;
                font-size: 11px;
            }

            .pagination-custom .arrow {
                min-width: 22px;
                width: 22px;
                height: 22px;
                font-size: 9px;
            }
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            color: #FFFFFF;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .whatsapp-float:hover {
            background-color: #128C7E;
            transform: scale(1.1);
        }

        .whatsapp-float-icon {
            width: 32px;
            height: 32px;
        }

        .produto-comprar-btn {
            width: 100%;
            margin-top: 12px;
            background: #25D366;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 0;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .produto-comprar-btn:hover {
            background: #128C7E;
        }

        /* Estado do botão quando o produto já está no carrinho:
           fica cinza e com o texto "Adicionar + 1" (continua clicável
           para permitir adicionar mais unidades). */
        .produto-comprar-btn.adicionado {
            background: #9e9e9e;
        }

        .produto-comprar-btn.adicionado:hover {
            background: #8a8a8a;
        }

        /* ===================================================================
           Toast de notificação (aviso de "produto adicionado ao carrinho")
           =================================================================== */

        .toast-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1002;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            pointer-events: none;
        }

        .toast-item {
            background: #323232;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-15px);
            transition: opacity 0.25s ease, transform 0.25s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-item.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-item-icone {
            color: #25D366;
            font-weight: 700;
        }

        /* ===================================================================
           CSS do Carrinho de Pedido (botão flutuante + modal com lista)
           =================================================================== */

        /* Botão redondo fixo no canto inferior esquerdo que abre o carrinho */
        .carrinho-float {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background-color: #ff9800;
            color: #fff;
            border: none;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .carrinho-float:hover {
            background-color: #e68900;
            transform: scale(1.1);
        }

        .carrinho-float svg {
            width: 28px;
            height: 28px;
            fill: #fff;
        }

        /* Círculo vermelho com o número de itens no carrinho */
        .carrinho-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #d32f2f;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* Fundo escurecido que cobre a tela quando o modal do carrinho está aberto */
        .pedido-modal-overlay {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Caixa branca central do modal (contém a lista de itens e o formulário) */
        .pedido-modal-box {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            width: 100%;
            max-width: 420px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            animation: zoom 0.2s ease-in-out;
        }

        .pedido-modal-box h3 {
            margin: 0 0 16px 0;
            color: #ff9800;
            font-size: 1.15rem;
        }

        /* Área com scroll que contém as linhas de itens do carrinho */
        .carrinho-lista {
            overflow-y: auto;
            margin-bottom: 12px;
        }

        /* Cada linha de item: nome/preço + campo de quantidade + botão remover */
        .carrinho-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .carrinho-item-info {
            flex: 1;
            min-width: 0;
        }

        .carrinho-item-nome {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .carrinho-item-preco {
            font-size: 0.8rem;
            color: #757575;
        }

        .carrinho-item-qtd {
            width: 52px;
            padding: 6px;
            border: 2px solid #eee;
            border-radius: 6px;
            font-size: 0.9rem;
            text-align: center;
        }

        .carrinho-item-qtd:focus {
            outline: none;
            border-color: #ff9800;
        }

        .carrinho-item-remover {
            background: none;
            border: none;
            color: #d32f2f;
            font-size: 1.3rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 4px;
        }

        /* Mensagem exibida quando não há itens no carrinho */
        .carrinho-vazio {
            text-align: center;
            color: #757575;
            padding: 30px 0;
            font-size: 0.9rem;
        }

        /* Linha de total geral do pedido, no rodapé da lista */
        .carrinho-total {
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            font-weight: 700;
            color: #444;
            padding: 12px 0;
            border-top: 2px solid #f0f0f0;
        }

        .pedido-modal-box label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #444;
        }

        .pedido-modal-box input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 16px;
        }

        .pedido-modal-box input:focus {
            outline: none;
            border-color: #ff9800;
        }

        .pedido-modal-actions {
            display: flex;
            gap: 10px;
        }

        .pedido-modal-actions button {
            flex: 1;
            border: none;
            border-radius: 8px;
            padding: 12px 0;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }

        .pedido-cancelar-btn {
            background: #f1f1f1;
            color: #555;
        }

        .pedido-enviar-btn {
            background: #25D366;
            color: #fff;
        }

        .pedido-enviar-btn:disabled {
            background: #bdbdbd;
            cursor: not-allowed;
        }

        .pedido-cancelar-btn:hover {
            background: #e2e2e2;
        }

        .pedido-enviar-btn:hover:not(:disabled) {
            background: #128C7E;
        }
    </style>

</head>

<body>

    <!-- Cabeçalho Delicaty -->
    <div class="header-delicaty">
        <div class="header-logo">
            <a href="{{ route('catalogo') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </a>
        </div>
        <div class="header-title">
            <a href="{{ route('catalogo') }}" style="text-decoration: none;">
                <div class="header-nome">Catálogo</div>
            </a>
        </div>
        <div class="header-info">
            <div class="header-nome" style="font-size: 0.85rem; color: #bdbdbd;">Delicaty Acessórios</div>
            <div class="header-instagram">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram"
                    style="height: 16px; vertical-align: middle;">
                @delicatyacessorios
            </div>
            <!-- Botão Flutuante no Canto Inferior Direito -->
            <a href="https://wa.me/5587999317326" target="_blank" class="whatsapp-float"
                aria-label="Contato via WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="whatsapp-float-icon">
                    <path fill="currentColor"
                        d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3 18.6-68.1-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                </svg>
            </a>

            <style>

            </style>
        </div>
    </div>

    <!-- Formulário de busca -->
    <div class="busca-container">
        <form method="GET" action="{{ url()->current() }}" class="busca-form">
            <input type="hidden" name="categoria" value="{{ request('categoria', 'todas') }}">
            <input type="text" name="busca" value="{{ request('busca') }}"
                placeholder="Buscar produtos por nome ou código..." class="busca-input">
            <button type="submit" class="busca-btn">Buscar</button>
        </form>
    </div>

    <!-- Contador de resultados -->
    <div class="resultados-info">
        Mostrando {{ $produtos->firstItem() ?? 0 }} - {{ $produtos->lastItem() ?? 0 }} de {{ $produtos->total() }}
        produtos
        @if (request('categoria') && request('categoria') != 'todas')
            na categoria "{{ request('categoria') }}"
        @endif
        @if (request('busca'))
            para "{{ request('busca') }}"
        @endif
    </div>

    <!-- Barra de categorias -->
    <div class="categorias-bar">
        <!-- DESCOMENTAR PARA HABILITAR A CATEGORIA TODAS -->
        <!--   <a href="{{ url()->current() }}?categoria=todas{{ request('busca') ? '&busca=' . request('busca') : '' }}"
           class="categoria-btn {{ !request('categoria') || request('categoria') == 'todas' ? 'active' : '' }}">
            Todas
        </a> -->

        @foreach ($categorias as $categoria)
            <a href="{{ url()->current() }}?categoria={{ urlencode($categoria->nome) }}{{ request('busca') ? '&busca=' . request('busca') : '' }}"
                class="categoria-btn {{ request('categoria') == $categoria->nome ? 'active' : '' }}">
                {{ $categoria->nome }}
            </a>
        @endforeach
    </div>

    <!-- Container de produtos -->
    <div class="catalogo-container">
        @forelse ($produtos as $produto)
            @php
                $foto = 'default.jpg';
                if (is_array($produto->foto) && !empty($produto->foto[0])) {
                    $foto = $produto->foto[0];
                } elseif (is_string($produto->foto) && !empty($produto->foto)) {
                    $foto = $produto->foto;
                }
                $catNome = $produto->categoria->nome ?? '';
            @endphp

            <div class="produto-card" data-categoria="{{ $catNome }}">
                <img src="{{ asset('storage/' . $foto) }}" alt="{{ $produto->nome }}" class="produto-img"
                    onclick="openModal(this)">
                <div class="produto-info">
                    <div class="produto-nome">{{ $produto->nome }}</div>
                    <div class="produto-preco">R$ {{ number_format($produto->valor_venda, 2, ',', '.') }}</div>
                    <div class="produto-codbar">Cod: {{ $produto->codbar }}</div>
                    <!-- Adiciona este produto ao carrinho (não abre modal individual).
                         data-codbar identifica o botão para que o JS consiga
                         atualizar sua aparência (cor cinza + texto "Adicionar + 1")
                         quando o produto já estiver no carrinho. -->
                    <button type="button" class="produto-comprar-btn" data-codbar="{{ $produto->codbar }}"
                        onclick="adicionarAoCarrinho('{{ addslashes($produto->nome) }}', {{ (float) $produto->valor_venda }}, '{{ addslashes($produto->codbar) }}')">
                        Comprar
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>Nenhum produto encontrado.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginação Personalizada -->
    <div class="pagination-container">
        @if ($produtos->hasPages())
            <ul class="pagination-custom">
                {{-- Link Anterior --}}
                @if ($produtos->onFirstPage())
                    <li class="disabled">
                        <span class="arrow">&laquo;</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $produtos->previousPageUrl() }}{{ request('categoria') ? '&categoria=' . request('categoria') : '' }}{{ request('busca') ? '&busca=' . request('busca') : '' }}"
                            class="arrow" aria-label="Anterior">
                            &laquo;
                        </a>
                    </li>
                @endif

                {{-- Primeira página --}}
                @if ($produtos->currentPage() > 3)
                    <li>
                        <a
                            href="{{ $produtos->url(1) }}{{ request('categoria') ? '&categoria=' . request('categoria') : '' }}{{ request('busca') ? '&busca=' . request('busca') : '' }}">
                            1
                        </a>
                    </li>
                    @if ($produtos->currentPage() > 4)
                        <li><span class="dots">...</span></li>
                    @endif
                @endif

                {{-- Páginas ao redor da atual --}}
                @foreach (range(1, $produtos->lastPage()) as $i)
                    @if ($i >= $produtos->currentPage() - 2 && $i <= $produtos->currentPage() + 2)
                        @if ($i == $produtos->currentPage())
                            <li class="active"><span>{{ $i }}</span></li>
                        @else
                            <li>
                                <a
                                    href="{{ $produtos->url($i) }}{{ request('categoria') ? '&categoria=' . request('categoria') : '' }}{{ request('busca') ? '&busca=' . request('busca') : '' }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach

                {{-- Última página --}}
                @if ($produtos->currentPage() < $produtos->lastPage() - 2)
                    @if ($produtos->currentPage() < $produtos->lastPage() - 3)
                        <li><span class="dots">...</span></li>
                    @endif
                    <li>
                        <a
                            href="{{ $produtos->url($produtos->lastPage()) }}{{ request('categoria') ? '&categoria=' . request('categoria') : '' }}{{ request('busca') ? '&busca=' . request('busca') : '' }}">
                            {{ $produtos->lastPage() }}
                        </a>
                    </li>
                @endif

                {{-- Link Próximo --}}
                @if ($produtos->hasMorePages())
                    <li>
                        <a href="{{ $produtos->nextPageUrl() }}{{ request('categoria') ? '&categoria=' . request('categoria') : '' }}{{ request('busca') ? '&busca=' . request('busca') : '' }}"
                            class="arrow" aria-label="Próximo">
                            &raquo;
                        </a>
                    </li>
                @else
                    <li class="disabled">
                        <span class="arrow">&raquo;</span>
                    </li>
                @endif
            </ul>
        @endif
    </div>

    <!-- Container das notificações (toasts) de "produto adicionado ao carrinho" -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Botão Flutuante do Carrinho: mostra a quantidade de itens (badge)
         e, ao ser clicado, abre o modal com a lista do pedido -->
    <button type="button" class="carrinho-float" onclick="abrirCarrinho()" aria-label="Ver carrinho">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <path
                d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.6c2.2 11.3 12.1 19.7 23.7 19.7H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
        </svg>
        <!-- Badge com o total de itens; fica oculto quando o carrinho está vazio -->
        <span id="carrinhoBadge" class="carrinho-badge" style="display:none;">0</span>
    </button>

    <!-- Modal de Pedido / Carrinho: lista os itens adicionados, permite
         editar quantidade ou excluir, mostra o total e só dispara o envio
         para o WhatsApp quando o cliente confirma no botão "Enviar pedido" -->
    <div id="pedidoModalOverlay" class="pedido-modal-overlay" onclick="fecharPedido()">
        <div class="pedido-modal-box" onclick="event.stopPropagation()">
            <h3>Seu pedido</h3>

            <!-- Preenchido dinamicamente por renderizarCarrinho() -->
            <div id="carrinhoLista" class="carrinho-lista"></div>

            <div id="carrinhoTotal" class="carrinho-total" style="display:none;">
                <span>Total</span>
                <span id="carrinhoTotalValor">R$ 0,00</span>
            </div>

            <label for="pedidoNome">Seu nome</label>
            <input type="text" id="pedidoNome" placeholder="Digite seu nome">

            <div class="pedido-modal-actions">
                <button type="button" class="pedido-cancelar-btn" onclick="fecharPedido()">Cancelar</button>
                <!-- Fica desabilitado enquanto o carrinho estiver vazio -->
                <button type="button" id="pedidoEnviarBtn" class="pedido-enviar-btn" onclick="enviarPedido()">Enviar pedido</button>
            </div>
        </div>
    </div>

    <!-- Modal de Imagem Ampliada -->
    <div id="imgModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImg">
        <div id="modalInfo" style="text-align:center; margin-top:18px; color:#fff;">
            <div id="modalNome" style="font-size:1.3rem; font-weight:700; color:#ff9800;"></div>
            <div id="modalPreco" style="font-size:1.1rem; font-weight:600; color:#fff;"></div>
            <div id="modalCod" style="font-size:0.95rem; color:#bdbdbd;"></div>
            <button id="shareBtn"
                style="margin-top:18px; background:#ff9800; color:#fff; border:none; border-radius:8px; padding:10px 22px; font-size:1rem; font-weight:600; cursor:pointer;">Compartilhar</button>
        </div>
    </div>

    <script>
        // Abre o modal de imagem ampliada ao clicar na foto do produto,
        // exibindo nome, preço e código, além de configurar o botão
        // de compartilhamento (usa a Web Share API quando disponível).
        function openModal(imgElement) {
            var card = imgElement.closest('.produto-card');
            var nome = card.querySelector('.produto-nome').textContent;
            var preco = card.querySelector('.produto-preco').textContent;
            var cod = card.querySelector('.produto-codbar').textContent;
            var modal = document.getElementById("imgModal");
            var modalImg = document.getElementById("modalImg");
            document.getElementById("modalNome").textContent = nome;
            document.getElementById("modalPreco").textContent = preco;
            document.getElementById("modalCod").textContent = cod;
            modal.style.display = "block";
            modalImg.src = imgElement.src;

            // Configura botão de compartilhamento
            var shareBtn = document.getElementById("shareBtn");
            shareBtn.onclick = function(e) {
                e.stopPropagation();
                if (navigator.share) {
                    navigator.share({
                        title: nome,
                        text: nome + "\n" + preco + "\n" + cod,
                        url: imgElement.src
                    }).catch(() => {});
                } else {
                    alert('Seu navegador não suporta compartilhamento direto.');
                }
            };
        }

        // Fecha o modal de imagem ampliada
        function closeModal() {
            document.getElementById("imgModal").style.display = "none";
        }

        // Permite fechar tanto o modal de imagem quanto o modal do
        // carrinho pressionando a tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeModal();
                fecharPedido();
            }
        });

        // =====================================================================
        // CARRINHO DE PEDIDO VIA WHATSAPP
        // O carrinho é um array de objetos { nome, preco, codbar, qtd }
        // salvo no localStorage do navegador, o que permite que o cliente
        // navegue entre páginas/categorias/buscas sem perder os itens
        // adicionados. O pedido só é enviado ao WhatsApp quando o cliente
        // clica em "Enviar pedido" no modal do carrinho.
        // =====================================================================

        // Chave usada para salvar/ler o carrinho no localStorage
        var CARRINHO_STORAGE_KEY = "delicaty_carrinho_pedido";

        // Formata um número (ex: 19.9) como moeda brasileira (ex: "R$ 19,90")
        function formatarMoeda(valor) {
            return "R$ " + valor.toFixed(2).replace('.', ',');
        }

        // Lê o carrinho salvo no localStorage.
        // Retorna sempre um array (mesmo se não houver nada salvo ou
        // se o conteúdo estiver corrompido/inválido).
        function getCarrinho() {
            try {
                var dados = JSON.parse(localStorage.getItem(CARRINHO_STORAGE_KEY));
                return Array.isArray(dados) ? dados : [];
            } catch (e) {
                return [];
            }
        }

        // Persiste o array do carrinho no localStorage e atualiza tanto
        // o contador (badge) do botão flutuante quanto a aparência dos
        // botões "Comprar" de cada produto (cinza/"Adicionar + 1" ou normal).
        function salvarCarrinho(carrinho) {
            localStorage.setItem(CARRINHO_STORAGE_KEY, JSON.stringify(carrinho));
            atualizarBadge(carrinho);
            atualizarBotoesProdutos(carrinho);
        }

        // Atualiza o número exibido no badge do botão flutuante do carrinho,
        // somando a quantidade de todos os itens. Se o carrinho estiver
        // vazio, o badge fica escondido.
        function atualizarBadge(carrinho) {
            carrinho = carrinho || getCarrinho();
            var totalItens = carrinho.reduce(function(soma, item) {
                return soma + item.qtd;
            }, 0);
            var badge = document.getElementById("carrinhoBadge");
            if (totalItens > 0) {
                badge.textContent = totalItens;
                badge.style.display = "flex";
            } else {
                badge.style.display = "none";
            }
        }

        // Percorre todos os botões "Comprar" da página (identificados pelo
        // atributo data-codbar) e ajusta a aparência de cada um conforme o
        // produto já esteja ou não no carrinho: se estiver, fica cinza com
        // o texto "Adicionar + 1"; caso contrário, volta ao normal ("Comprar").
        function atualizarBotoesProdutos(carrinho) {
            carrinho = carrinho || getCarrinho();
            var codbarsNoCarrinho = carrinho.map(function(item) {
                return item.codbar;
            });

            document.querySelectorAll('.produto-comprar-btn[data-codbar]').forEach(function(btn) {
                var codbar = btn.getAttribute('data-codbar');
                if (codbarsNoCarrinho.indexOf(codbar) !== -1) {
                    btn.classList.add('adicionado');
                    btn.textContent = 'Adicionar + 1';
                } else {
                    btn.classList.remove('adicionado');
                    btn.textContent = 'Comprar';
                }
            });
        }

        // Cria e exibe uma notificação (toast) temporária no topo da tela,
        // usada para avisar que um produto foi adicionado ao carrinho.
        // O elemento some sozinho após alguns segundos.
        function mostrarNotificacao(texto) {
            var container = document.getElementById('toastContainer');

            var toast = document.createElement('div');
            toast.className = 'toast-item';
            toast.innerHTML = '<span class="toast-item-icone">&#10003;</span><span>' + texto + '</span>';
            container.appendChild(toast);

            // Pequeno atraso para garantir a transição de entrada (fade/slide)
            setTimeout(function() {
                toast.classList.add('show');
            }, 10);

            // Remove o toast após um tempo, com transição de saída
            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() {
                    toast.remove();
                }, 250);
            }, 2200);
        }

        // Chamada pelo botão "Comprar" de cada produto.
        // Se o produto (identificado pelo código de barras) já está no
        // carrinho, apenas incrementa a quantidade em 1. Caso contrário,
        // adiciona um novo item com quantidade inicial 1.
        // Em ambos os casos, exibe uma notificação (toast) confirmando
        // a ação para o usuário.
        function adicionarAoCarrinho(nome, preco, codbar) {
            var carrinho = getCarrinho();
            var existente = carrinho.find(function(item) {
                return item.codbar === codbar;
            });

            if (existente) {
                existente.qtd += 1;
            } else {
                carrinho.push({ nome: nome, preco: preco, codbar: codbar, qtd: 1 });
            }

            salvarCarrinho(carrinho);
            mostrarNotificacao(nome + ' adicionado ao carrinho');
        }

        // Chamada quando o cliente edita manualmente o campo de quantidade
        // de um item dentro do modal do carrinho.
        // Se o valor digitado for inválido ou menor que 1, o item é removido
        // do carrinho (equivalente a zerar a quantidade).
        function atualizarQtdItem(codbar, novaQtd) {
            novaQtd = parseInt(novaQtd, 10);
            var carrinho = getCarrinho();

            if (!novaQtd || novaQtd < 1) {
                carrinho = carrinho.filter(function(item) {
                    return item.codbar !== codbar;
                });
            } else {
                var item = carrinho.find(function(item) {
                    return item.codbar === codbar;
                });
                if (item) item.qtd = novaQtd;
            }

            salvarCarrinho(carrinho);
            renderizarCarrinho(); // redesenha a lista para refletir a mudança
        }

        // Remove um item específico do carrinho (botão "×" de cada linha).
        function removerItemCarrinho(codbar) {
            var carrinho = getCarrinho().filter(function(item) {
                return item.codbar !== codbar;
            });
            salvarCarrinho(carrinho);
            renderizarCarrinho();
        }

        // Redesenha a lista de itens dentro do modal do carrinho, com base
        // no que está salvo no localStorage. Monta o HTML de cada linha
        // (nome, preço, campo de quantidade editável e botão de remover),
        // calcula o total geral e habilita/desabilita o botão "Enviar pedido"
        // conforme o carrinho está vazio ou não.
        function renderizarCarrinho() {
            var carrinho = getCarrinho();
            var listaEl = document.getElementById("carrinhoLista");
            var totalEl = document.getElementById("carrinhoTotal");
            var totalValorEl = document.getElementById("carrinhoTotalValor");
            var enviarBtn = document.getElementById("pedidoEnviarBtn");

            // Carrinho vazio: mostra aviso e trava o botão de envio
            if (carrinho.length === 0) {
                listaEl.innerHTML = '<div class="carrinho-vazio">Seu carrinho está vazio.</div>';
                totalEl.style.display = "none";
                enviarBtn.disabled = true;
                return;
            }

            var html = "";
            var total = 0;

            // Monta uma linha de HTML para cada item do carrinho
            carrinho.forEach(function(item) {
                var subtotal = item.preco * item.qtd;
                total += subtotal;
                html += '<div class="carrinho-item">' +
                    '<div class="carrinho-item-info">' +
                        '<div class="carrinho-item-nome">' + item.nome + '</div>' +
                        '<div class="carrinho-item-preco">' + formatarMoeda(item.preco) + ' cada &middot; ' + formatarMoeda(subtotal) + '</div>' +
                    '</div>' +
                    // Campo de quantidade: ao alterar, chama atualizarQtdItem
                    '<input type="number" class="carrinho-item-qtd" min="1" value="' + item.qtd + '" ' +
                        'onchange="atualizarQtdItem(\'' + item.codbar + '\', this.value)">' +
                    // Botão de excluir o item
                    '<button type="button" class="carrinho-item-remover" onclick="removerItemCarrinho(\'' + item.codbar + '\')" aria-label="Remover item">&times;</button>' +
                '</div>';
            });

            listaEl.innerHTML = html;
            totalValorEl.textContent = formatarMoeda(total);
            totalEl.style.display = "flex";
            enviarBtn.disabled = false;
        }

        // Abre o modal do carrinho, garantindo que a lista exibida
        // esteja sempre atualizada antes de aparecer na tela.
        function abrirCarrinho() {
            renderizarCarrinho();
            document.getElementById("pedidoModalOverlay").style.display = "flex";
        }

        // Fecha o modal do carrinho (botão "Cancelar", ESC ou clique fora da caixa).
        function fecharPedido() {
            document.getElementById("pedidoModalOverlay").style.display = "none";
        }

        // Chamada pelo botão "Enviar pedido". Valida que o carrinho não está
        // vazio e que o nome do cliente foi informado, monta uma única
        // mensagem de texto com todos os itens (nome, código, quantidade e
        // subtotal) mais o total geral, abre o WhatsApp com a mensagem
        // pré-preenchida e, por fim, limpa o carrinho e fecha o modal.
        function enviarPedido() {
            var carrinho = getCarrinho();
            if (carrinho.length === 0) {
                alert("Seu carrinho está vazio.");
                return;
            }

            var nomeCliente = document.getElementById("pedidoNome").value.trim();
            if (!nomeCliente) {
                alert("Por favor, informe seu nome.");
                return;
            }

            // Monta uma linha de texto para cada item e acumula o total
            var total = 0;
            var linhas = carrinho.map(function(item) {
                var subtotal = item.preco * item.qtd;
                total += subtotal;
                return "- " + item.nome + " (Cod: " + item.codbar + ") x" + item.qtd + " = " + formatarMoeda(subtotal);
            });

            var mensagem = "Olá! Gostaria de fazer o seguinte pedido:\n\n" +
                linhas.join("\n") +
                "\n\nTotal: " + formatarMoeda(total) +
                "\n\nNome: " + nomeCliente;

            // Número do WhatsApp que receberá o pedido (formato internacional: 55 + DDD + número)
            var numeroWhatsapp = "5587999317326";
            var url = "https://wa.me/" + numeroWhatsapp + "?text=" + encodeURIComponent(mensagem);
            window.open(url, "_blank");

            // Pedido enviado: esvazia o carrinho e fecha o modal
            salvarCarrinho([]);
            renderizarCarrinho();
            fecharPedido();
        }

        // Ao carregar a página, sincroniza o badge do botão flutuante e a
        // aparência dos botões "Comprar" com o que já estiver salvo no
        // localStorage (caso o cliente tenha itens de uma visita anterior).
        atualizarBadge();
        atualizarBotoesProdutos();
    </script>
</body>

</html>