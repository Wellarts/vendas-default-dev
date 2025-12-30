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
    </style>

</head>

<body>
    @php
        $p = null;
        try {
            $p = DB::table('parametros')->first();
        } catch (Exception $e) {
            $p = null;
        }
        $companyName = $p->empresa_nome ?? ($p->nome_empresa ?? ($p->nome ?? 'Nome da Empresa'));
        $companyCnpj = $p->cnpj ?? '00.000.000/0000-00';
        $companyAddress = $p->endereco_completo ?? 'Sem Endereço Informado';
        $companyPhones = $p->telefone ?? '(00) 0000-0000';
        $companyInsta = $p->redes_sociais ?? '@empresa';
        $logo = $p->logo;
       

    @endphp

    <!-- Cabeçalho Delicaty -->
    <div class="header-delicaty">
        <div class="header-logo">
            <a href="{{ route('catalogo') }}">
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo da Empresa" style="height: 90px;">
            </a>
        </div>
        <div class="header-title">
            <a href="{{ route('catalogo') }}" style="text-decoration: none;">
                <div class="header-nome">Catálogo</div>
            </a>
        </div>
        <div class="header-info">
            <div class="header-nome" style="font-size: 0.85rem; color: #bdbdbd;">{{ $companyName }}</div>
            <div class="header-instagram">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram"
                    style="height: 16px; vertical-align: middle;">
                {{ $companyInsta }}
            </div>
            <div class="header-tel">{{ $companyPhones }}</div>
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

        function closeModal() {
            document.getElementById("imgModal").style.display = "none";
        }

        // Fecha com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });
    </script>
</body>

</html>
