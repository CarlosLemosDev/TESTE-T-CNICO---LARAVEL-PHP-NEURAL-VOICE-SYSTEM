<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teste Técnico Laravel + PHP | Text-to-Speech System</title>
    
    <!-- Google Fonts para estilo Matrix -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Fira+Code:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #000;
            color: #00ff00;
            font-family: 'Fira Code', 'Courier New', monospace;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Efeito Matrix Rain */
        .matrix-rain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }
        
        .matrix-column {
            position: absolute;
            top: -100%;
            font-size: 14px;
            line-height: 14px;
            white-space: nowrap;
            animation: matrix-fall linear infinite;
            opacity: 0.8;
        }
        
        @keyframes matrix-fall {
            0% {
                top: -100%;
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                top: 100vh;
                opacity: 0;
            }
        }
        
        /* Container principal */
        .main-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .terminal-window {
            background: rgba(0, 0, 0, 0.95);
            border: 2px solid #00ff00;
            border-radius: 10px;
            box-shadow: 0 0 30px #00ff00, inset 0 0 20px rgba(0, 255, 0, 0.1);
            max-width: 650px;
            width: 100%;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .terminal-header {
            background: linear-gradient(90deg, #003300, #001100);
            border-bottom: 1px solid #00ff00;
            padding: 8px 15px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .terminal-buttons {
            display: flex;
            gap: 8px;
        }
        
        .terminal-btn {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff0000;
        }
        
        .terminal-btn.yellow {
            background: #ffff00;
        }
        
        .terminal-btn.green {
            background: #00ff00;
        }
        
        .terminal-title {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 12px;
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
            margin-left: 15px;
        }
        
        .terminal-body {
            padding: 15px;
        }
        
        .ascii-art {
            font-family: 'Fira Code', monospace;
            font-size: 6px;
            line-height: 1;
            color: #00ff00;
            text-align: center;
            margin-bottom: 8px;
            text-shadow: 0 0 5px #00ff00;
            white-space: pre;
        }
        
        .system-info {
            color: #00ff00;
            font-size: 9px;
            margin-bottom: 8px;
            opacity: 0.8;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .voice-controls {
            background: rgba(0, 30, 0, 0.6);
            border: 1px solid #004400;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .control-row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }
        
        .control-group {
            flex: 1;
            min-width: 120px;
        }
        
        .control-label {
            color: #00ff00;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .control-select,
        .control-range {
            width: 100%;
            background: #000;
            border: 1px solid #004400;
            border-radius: 4px;
            color: #00ff00;
            font-family: 'Fira Code', monospace;
            font-size: 10px;
            padding: 5px;
            transition: all 0.3s ease;
        }
        
        .control-select:focus,
        .control-range:focus {
            outline: none;
            border-color: #00ff00;
            box-shadow: 0 0 8px rgba(0, 255, 0, 0.3);
        }
        
        .control-select option {
            background: #000;
            color: #00ff00;
        }
        
        .control-range {
            -webkit-appearance: none;
            height: 6px;
            background: #003300;
            border-radius: 3px;
        }
        
        .control-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: #00ff00;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 5px #00ff00;
        }
        
        .control-range::-moz-range-thumb {
            width: 16px;
            height: 16px;
            background: #00ff00;
            border-radius: 50%;
            cursor: pointer;
            border: none;
            box-shadow: 0 0 5px #00ff00;
        }
        
        .range-value {
            color: #00ff00;
            font-size: 8px;
            text-align: center;
            margin-top: 2px;
            font-family: 'Fira Code', monospace;
        }
        
        .preset-buttons {
            display: flex;
            gap: 5px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .preset-btn {
            background: linear-gradient(45deg, #001100, #002200);
            border: 1px solid #004400;
            color: #00ff00;
            font-family: 'Fira Code', monospace;
            font-size: 8px;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        
        .preset-btn:hover {
            background: linear-gradient(45deg, #002200, #003300);
            border-color: #00ff00;
            box-shadow: 0 0 8px rgba(0, 255, 0, 0.3);
        }
        
        .preset-btn.active {
            background: linear-gradient(45deg, #003300, #004400);
            border-color: #00ff00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .form-container {
            background: rgba(0, 20, 0, 0.8);
            border: 1px solid #004400;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
        }
        
        .form-label {
            color: #00ff00;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .form-textarea {
            width: 100%;
            background: #000;
            border: 2px solid #004400;
            border-radius: 5px;
            color: #00ff00;
            font-family: 'Fira Code', monospace;
            font-size: 12px;
            padding: 10px;
            resize: vertical;
            transition: all 0.3s ease;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.3);
            background: rgba(0, 40, 0, 0.3);
        }
        
        .form-textarea::placeholder {
            color: #006600;
            opacity: 0.7;
        }
        
        .char-counter {
            text-align: right;
            font-size: 11px;
            color: #006600;
            margin-top: 5px;
        }
        
        .translation-display {
            margin-top: 15px;
            border: 1px solid #00ff00;
            border-radius: 5px;
            padding: 10px;
            background: rgba(0, 50, 0, 0.3);
            animation: slideDown 0.3s ease;
        }
        
        .translation-header {
            color: #00ff00;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
            font-family: 'Fira Code', monospace;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .translated-text {
            color: #00ffff;
            font-size: 14px;
            line-height: 1.4;
            padding: 5px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
            border-left: 3px solid #00ffff;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .matrix-btn {
            background: linear-gradient(45deg, #001100, #003300);
            border: 2px solid #00ff00;
            color: #00ff00;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 12px;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .matrix-btn:hover {
            background: linear-gradient(45deg, #002200, #004400);
            box-shadow: 0 0 20px #00ff00;
            transform: translateY(-2px);
        }
        
        .matrix-btn:active {
            transform: translateY(0);
        }
        
        .matrix-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 0.8; }
        }
        
        .status-display {
            margin-top: 10px;
            min-height: 30px;
        }
        
        .alert {
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert-success {
            background: rgba(0, 100, 0, 0.2);
            border-left-color: #00ff00;
            color: #00ff00;
        }
        
        .alert-info {
            background: rgba(0, 100, 100, 0.2);
            border-left-color: #00ffff;
            color: #00ffff;
        }
        
        .alert-warning {
            background: rgba(100, 100, 0, 0.2);
            border-left-color: #ffff00;
            color: #ffff00;
        }
        
        .alert-danger {
            background: rgba(100, 0, 0, 0.2);
            border-left-color: #ff0000;
            color: #ff0000;
        }
        
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
        
        .glitch {
            animation: glitch 0.3s ease-in-out;
        }
        
        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }
        
        /* Scrollbar customizado */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #000;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #00ff00;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #00aa00;
        }
        
        /* Responsivo */
        @media (max-width: 768px) {
            .terminal-window {
                margin: 10px;
                border-radius: 5px;
            }
            
            .terminal-body {
                padding: 20px;
            }
            
            .ascii-art {
                font-size: 8px;
            }
            
            .matrix-btn {
                font-size: 14px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Matrix Rain Effect -->
    <div class="matrix-rain" id="matrixRain"></div>
    
    <div class="main-container">
        <div class="terminal-window">
            <div class="terminal-header">
                <div class="terminal-buttons">
                    <div class="terminal-btn"></div>
                    <div class="terminal-btn yellow"></div>
                    <div class="terminal-btn green"></div>
                </div>
                <div class="terminal-title">TESTE TÉCNICO - LARAVEL + PHP | NEURAL VOICE SYSTEM</div>
            </div>
            
            <div class="terminal-body">
                <div class="ascii-art" id="asciiArt">
╔══════════════════════════════════════════════════════════╗
║  ████████╗███████╗███████╗████████╗███████╗              ║
║  ╚══██╔══╝██╔════╝██╔════╝╚══██╔══╝██╔════╝              ║
║     ██║   █████╗  ███████╗   ██║   █████╗   ████████╗    ║
║     ██║   ██╔══╝  ╚════██║   ██║   ██╔══╝   ╚══██╔══╝    ║
║     ██║   ███████╗███████║   ██║   ███████╗    ██║       ║
║     ╚═╝   ╚══════╝╚══════╝   ╚═╝   ╚══════╝    ╚═╝       ║
║                                                          ║
║           TEXT-TO-SPEECH | LARAVEL + PHP 2025           ║
╚══════════════════════════════════════════════════════════╝
                </div>
                
                <div class="system-info">
                    <span>STATUS: <span style="color: #00ff00;">ONLINE</span></span>
                    <span>NEURAL: <span style="color: #00ff00;">ACTIVE</span></span>
                    <span>VOICE: <span style="color: #00ff00;">READY</span></span>
                    <span>CONN: <span style="color: #00ff00;">SECURE</span></span>
                </div>
                
                <div class="voice-controls">
                    <div class="control-row">
                        <div class="control-group">
                            <label class="control-label">� Language</label>
                            <select id="languageSelect" class="control-select">
                                <option value="pt-BR">🇧🇷 Português Brasileiro</option>
                            </select>
                        </div>
                        <div class="control-group">
                            <label class="control-label">�🎙️ Voice Selection</label>
                            <select id="voiceSelect" class="control-select">
                                <option value="">🔄 Loading voices...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="control-row">
                        <div class="control-group">
                            <label class="control-label">⚡ Speed</label>
                            <input type="range" id="rateControl" class="control-range" 
                                   min="0.1" max="2" step="0.1" value="0.8">
                            <div class="range-value" id="rateValue">0.8x</div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">🎵 Pitch</label>
                            <input type="range" id="pitchControl" class="control-range" 
                                   min="0" max="2" step="0.1" value="1">
                            <div class="range-value" id="pitchValue">1.0</div>
                        </div>
                    </div>
                    
                    <div class="control-row">
                        <div class="control-group">
                            <label class="control-label">🔊 Volume</label>
                            <input type="range" id="volumeControl" class="control-range" 
                                   min="0" max="1" step="0.1" value="1">
                            <div class="range-value" id="volumeValue">100%</div>
                        </div>
                        <div class="control-group"></div>
                    </div>
                    
                    <div class="preset-buttons">
                        <button type="button" class="preset-btn" data-preset="robot">🤖 Robot</button>
                        <button type="button" class="preset-btn" data-preset="natural">👤 Natural</button>
                        <button type="button" class="preset-btn active" data-preset="default">⚡ Default</button>
                        <button type="button" class="preset-btn" data-preset="slow">🐌 Slow</button>
                        <button type="button" class="preset-btn" data-preset="fast">⚡ Fast</button>
                        <button type="button" class="preset-btn" data-preset="deep">🎭 Deep</button>
                        <button type="button" class="preset-btn" id="testVoiceBtn">🔊 Test</button>
                    </div>
                </div>
                
                <form id="matrixTtsForm" class="form-container">
                    @csrf
                    <label for="textInput" class="form-label">
                        &gt; ENTER NEURAL INPUT DATA:
                    </label>
                    <textarea 
                        id="textInput" 
                        name="text" 
                        class="form-textarea" 
                        rows="3" 
                        placeholder="Digite aqui em português - será traduzido automaticamente..."
                        required
                        maxlength="1000"
                    ></textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span>/1000 CHARACTERS
                    </div>
                    
                    <!-- Translation Display -->
                    <div id="translationDisplay" class="translation-display" style="display: none;">
                        <div class="translation-header">
                            <i class="fas fa-language"></i> TRADUÇÃO AUTOMÁTICA:
                        </div>
                        <div id="translatedText" class="translated-text"></div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 10px;">
                        <button type="submit" class="matrix-btn" id="processBtn">
                            ⚡ INITIATE SYNTHESIS ⚡
                        </button>
                    </div>
                </form>
                
                <div id="statusDisplay" class="status-display"></div>
            </div>
        </div>
    </div>

    <script>
        // Matrix Rain Effect
        function createMatrixRain() {
            const container = document.getElementById('matrixRain');
            const characters = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
            
            function createColumn() {
                const column = document.createElement('div');
                column.className = 'matrix-column';
                column.style.left = Math.random() * 100 + '%';
                column.style.animationDuration = (Math.random() * 3 + 2) + 's';
                column.style.animationDelay = Math.random() * 2 + 's';
                
                let text = '';
                for (let i = 0; i < 20; i++) {
                    text += characters[Math.floor(Math.random() * characters.length)] + '<br>';
                }
                column.innerHTML = text;
                
                container.appendChild(column);
                
                setTimeout(() => {
                    container.removeChild(column);
                }, 5000);
            }
            
            setInterval(createColumn, 300);
        }
        
        // Inicializar Matrix Rain
        createMatrixRain();
        
        // Configurações do formulário
        const textInput = document.getElementById('textInput');
        const charCount = document.getElementById('charCount');
        const form = document.getElementById('matrixTtsForm');
        const processBtn = document.getElementById('processBtn');
        const statusDisplay = document.getElementById('statusDisplay');
        const translationDisplay = document.getElementById('translationDisplay');
        const translatedTextDiv = document.getElementById('translatedText');
        
        // CSRF Token com verificação
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;
        
        if (!csrfToken) {
            console.error('CSRF Token não encontrado!');
            showStatus('ERRO: TOKEN DE SEGURANÇA NÃO ENCONTRADO', 'danger');
        }
        
        // Controles de voz
        const voiceSelect = document.getElementById('voiceSelect');
        const rateControl = document.getElementById('rateControl');
        const pitchControl = document.getElementById('pitchControl');
        const volumeControl = document.getElementById('volumeControl');
        const rateValue = document.getElementById('rateValue');
        const pitchValue = document.getElementById('pitchValue');
        const volumeValue = document.getElementById('volumeValue');
        const presetButtons = document.querySelectorAll('.preset-btn');
        
        // Variáveis para vozes e idiomas
        let availableVoices = [];
        let currentVoice = null;
        let selectedLanguage = 'pt-BR';
        
        const languageSelect = document.getElementById('languageSelect');
        
        // Mapear idiomas para códigos de línguas do navegador com vozes preferenciais
        const languageMap = {
            'pt-BR': {
                codes: ['pt-BR', 'pt'],
                preferredVoices: ['Microsoft Maria Desktop', 'Google português do Brasil', 'Portuguese (Brazil)', 'Luciana', 'Fernanda']
            }
        };
        
        // Carregar vozes disponíveis
        function loadVoices() {
            availableVoices = speechSynthesis.getVoices();
            updateVoicesForLanguage();
        }
        
        // Atualizar vozes baseadas no idioma selecionado
        function updateVoicesForLanguage() {
            const langConfig = languageMap[selectedLanguage] || { codes: ['pt'], preferredVoices: [] };
            
            // Filtrar vozes para o idioma selecionado
            const filteredVoices = availableVoices.filter(voice => 
                langConfig.codes.some(code => voice.lang.toLowerCase().includes(code.toLowerCase()))
            );
            
            // Ordenar vozes: preferidas primeiro, depois outras
            const sortedVoices = [...filteredVoices].sort((a, b) => {
                const aPreferred = langConfig.preferredVoices.some(preferred => 
                    a.name.toLowerCase().includes(preferred.toLowerCase())
                );
                const bPreferred = langConfig.preferredVoices.some(preferred => 
                    b.name.toLowerCase().includes(preferred.toLowerCase())
                );
                
                if (aPreferred && !bPreferred) return -1;
                if (!aPreferred && bPreferred) return 1;
                return a.name.localeCompare(b.name);
            });
            
            // Se não houver vozes específicas, usar todas disponíveis
            const voicesToUse = sortedVoices.length > 0 ? sortedVoices : availableVoices;
            
            voiceSelect.innerHTML = '';
            
            // Adicionar opção padrão
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = `🔊 Voz Padrão do Sistema (${selectedLanguage})`;
            voiceSelect.appendChild(defaultOption);
            
            // Adicionar vozes disponíveis com indicadores visuais
            voicesToUse.forEach((voice, index) => {
                const option = document.createElement('option');
                option.value = voice.name;
                
                // Verificar se é uma voz preferida
                const isPreferred = langConfig.preferredVoices.some(preferred => 
                    voice.name.toLowerCase().includes(preferred.toLowerCase())
                );
                
                // Detectar tipo de voz (Microsoft, Google, Sistema)
                let voiceType = '🔊';
                if (voice.name.toLowerCase().includes('microsoft')) {
                    voiceType = '🏢';
                } else if (voice.name.toLowerCase().includes('google')) {
                    voiceType = '🌐';
                } else if (voice.localService) {
                    voiceType = '💻';
                }
                
                const preferredIcon = isPreferred ? '⭐ ' : '';
                option.textContent = `${preferredIcon}${voiceType} ${voice.name} (${voice.lang})`;
                
                voiceSelect.appendChild(option);
            });
            
            const count = voicesToUse.length;
            const preferredCount = voicesToUse.filter(voice => 
                langConfig.preferredVoices.some(preferred => 
                    voice.name.toLowerCase().includes(preferred.toLowerCase())
                )
            ).length;
            
            const langName = {
                'pt-BR': 'PORTUGUÊS BRASILEIRO'
            }[selectedLanguage] || 'IDIOMA SELECIONADO';
            
            const statusMessage = preferredCount > 0 
                ? `✅ ${count} VOZES ${langName} (${preferredCount} ⭐ PREFERIDAS)`
                : `✅ ${count} VOZES ${langName} DISPONÍVEIS`;
                
            showStatus(statusMessage, 'success');
        }
        
        // Event listener para mudança de idioma
        languageSelect.addEventListener('change', function() {
            selectedLanguage = this.value;
            updateVoicesForLanguage();
            updatePlaceholder();
            autoSelectBestVoice();
            
            // Limpar tradução anterior
            translationDisplay.style.display = 'none';
            translatedTextDiv.textContent = '';
        });
        
        // Clique duplo no selector para otimização automática
        languageSelect.addEventListener('dblclick', function() {
            optimizeForCurrentLanguage();
        });
        
        // Função para selecionar automaticamente a melhor voz
        function autoSelectBestVoice() {
            const langConfig = languageMap[selectedLanguage];
            if (!langConfig) return;
            
            // Procurar por vozes preferenciais
            for (const preferredVoice of langConfig.preferredVoices) {
                const foundVoice = availableVoices.find(voice => 
                    voice.name.toLowerCase().includes(preferredVoice.toLowerCase()) &&
                    langConfig.codes.some(code => voice.lang.toLowerCase().includes(code.toLowerCase()))
                );
                
                if (foundVoice) {
                    voiceSelect.value = foundVoice.name;
                    showStatus(`🎯 MELHOR VOZ SELECIONADA: ${foundVoice.name}`, 'info');
                    return;
                }
            }
            
            // Se não encontrar voz preferencial, usar a primeira disponível do idioma
            const filteredVoices = availableVoices.filter(voice => 
                langConfig.codes.some(code => voice.lang.toLowerCase().includes(code.toLowerCase()))
            );
            
            if (filteredVoices.length > 0) {
                voiceSelect.value = filteredVoices[0].name;
                showStatus(`🔊 VOZ ALTERNATIVA SELECIONADA: ${filteredVoices[0].name}`, 'info');
            }
        }
        
        // Atualizar placeholder baseado no idioma
        function updatePlaceholder() {
            const placeholders = {
                'pt-BR': "Digite em português brasileiro..."
            };
            
            textInput.placeholder = placeholders[selectedLanguage] || placeholders['pt-BR'];
        }
        
        // Carregar vozes quando disponíveis
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        }
        setTimeout(loadVoices, 100);
        
        // Inicializar placeholder
        updatePlaceholder();
        
        // Atualizar valores dos controles
        rateControl.addEventListener('input', function() {
            rateValue.textContent = this.value + 'x';
        });
        
        pitchControl.addEventListener('input', function() {
            pitchValue.textContent = parseFloat(this.value).toFixed(1);
        });
        
        volumeControl.addEventListener('input', function() {
            volumeValue.textContent = Math.round(this.value * 100) + '%';
        });
        
        // Presets de voz
        const voicePresets = {
            robot: { rate: 0.6, pitch: 0.3, volume: 1 },
            natural: { rate: 0.9, pitch: 1.0, volume: 0.8 },
            default: { rate: 0.8, pitch: 1.0, volume: 1.0 },
            slow: { rate: 0.4, pitch: 1.0, volume: 1.0 },
            fast: { rate: 1.5, pitch: 1.2, volume: 1.0 },
            deep: { rate: 0.7, pitch: 0.5, volume: 1.0 }
        };
        
        // Aplicar presets
        presetButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const preset = this.dataset.preset;
                const settings = voicePresets[preset];
                
                if (settings) {
                    rateControl.value = settings.rate;
                    pitchControl.value = settings.pitch;
                    volumeControl.value = settings.volume;
                    
                    rateValue.textContent = settings.rate + 'x';
                    pitchValue.textContent = settings.pitch.toFixed(1);
                    volumeValue.textContent = Math.round(settings.volume * 100) + '%';
                    
                    // Atualizar botão ativo
                    presetButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    showStatus('🎵 PRESET "' + preset.toUpperCase() + '" APLICADO', 'info');
                }
            });
        });
        
        // Botão de teste de voz com configurações otimizadas por idioma
        document.getElementById('testVoiceBtn').addEventListener('click', function() {
            const testTexts = {
                'pt-BR': "Olá! Este é um teste do sistema Matrix de síntese de voz em português brasileiro."
            };
            
            // Configurações otimizadas por idioma
            const languageSettings = {
                'pt-BR': { rate: 0.9, pitch: 1.0, volume: 0.8 }
            };
            
            const testText = testTexts[selectedLanguage] || testTexts['pt-BR'];
            const settings = languageSettings[selectedLanguage] || languageSettings['pt-BR'];
            
            // Aplicar configurações otimizadas
            rateControl.value = settings.rate;
            pitchControl.value = settings.pitch;
            volumeControl.value = settings.volume;
            
            // Atualizar displays
            rateValue.textContent = settings.rate + 'x';
            pitchValue.textContent = Math.round(settings.pitch * 100) + '%';
            volumeValue.textContent = Math.round(settings.volume * 100) + '%';
            
            // Simular o processo de tradução
            textInput.value = testText;
            charCount.textContent = testText.length;
            
            const langNames = {
                'pt-BR': 'PORTUGUÊS'
            };
            
            showStatus(`🎮 TESTE DE VOZ ${langNames[selectedLanguage]} INICIADO...`, 'info');
            
            // Submeter o formulário programaticamente
            setTimeout(() => {
                form.dispatchEvent(new Event('submit'));
            }, 500);
        });
        
        // Função para otimizar configurações para o idioma atual
        function optimizeForCurrentLanguage() {
            const languageSettings = {
                'pt-BR': { rate: 0.9, pitch: 1.0, volume: 0.8 }
            };
            
            const settings = languageSettings[selectedLanguage] || languageSettings['pt-BR'];
            
            // Aplicar configurações otimizadas
            rateControl.value = settings.rate;
            pitchControl.value = settings.pitch;
            volumeControl.value = settings.volume;
            
            // Atualizar displays
            rateValue.textContent = settings.rate + 'x';
            pitchValue.textContent = Math.round(settings.pitch * 100) + '%';
            volumeValue.textContent = Math.round(settings.volume * 100) + '%';
            
            // Selecionar melhor voz
            autoSelectBestVoice();
            
            const langNames = {
                'pt-BR': 'PORTUGUÊS'
            };
            
            showStatus(`🎯 CONFIGURAÇÕES OTIMIZADAS PARA ${langNames[selectedLanguage]}`, 'success');
        }
        
        // Contador de caracteres
        textInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            // Efeito de digitação
            if (Math.random() < 0.1) {
                this.classList.add('glitch');
                setTimeout(() => this.classList.remove('glitch'), 300);
            }
        });
        
        // Efeito de digitação no ASCII art
        function typeWriter(element, text, speed = 50) {
            element.innerHTML = '';
            let i = 0;
            function typing() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                }
            }
            typing();
        }
        
        // Manipular envio do formulário
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const text = textInput.value.trim();
            
            if (!text) {
                showStatus('ERRO: DADOS DE ENTRADA INSUFICIENTES', 'danger');
                return;
            }
            
            // Desabilitar botão e mostrar loading
            processBtn.disabled = true;
            processBtn.innerHTML = '⚡ PROCESSANDO NEURAL DATA<span class="loading-dots"></span>';
            
            showStatus('INICIANDO SÍNTESE NEURAL...', 'info');
            
            // Simular processamento
            setTimeout(() => {
                // Verificar se temos CSRF token
                if (!csrfToken) {
                    showStatus('ERRO: TOKEN CSRF INVÁLIDO', 'danger');
                    processBtn.disabled = false;
                    processBtn.innerHTML = '⚡ INITIATE SYNTHESIS ⚡';
                    return;
                }
                
                // Enviar para o Laravel
                fetch('{{ route("tts.convert") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        text: text,
                        language: selectedLanguage 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar tradução se foi traduzido
                        if (data.translationStatus === 'translated') {
                            translatedTextDiv.textContent = data.translatedText;
                            translationDisplay.style.display = 'block';
                            
                            const langNames = {
                                'pt-BR': 'PORTUGUÊS'
                            };
                            
                            const langName = langNames[data.language] || data.language;
                            showStatus(`✅ PROCESSADO EM ${langName}. INICIANDO SÍNTESE...`, 'success');
                            
                        } else if (data.translationStatus === 'basic_translation') {
                            translatedTextDiv.textContent = data.translatedText;
                            translationDisplay.style.display = 'block';
                            
                            const langNames = {
                                'pt-BR': 'PORTUGUÊS'
                            };
                            
                            const langName = langNames[data.language] || data.language;
                            showStatus(`✅ PROCESSADO EM ${langName}. INICIANDO SÍNTESE...`, 'info');
                            
                        } else if (data.translationStatus === 'error') {
                            translationDisplay.style.display = 'none';
                            showStatus('⚠️ TRADUÇÃO FALHOU. USANDO TEXTO ORIGINAL...', 'warning');
                            
                        } else {
                            translationDisplay.style.display = 'none';
                            showStatus('SÍNTESE NEURAL COMPLETA. INICIANDO TRANSMISSÃO DE ÁUDIO...', 'success');
                        }
                        
                        // Usar o texto traduzido para síntese
                        const textToSpeak = data.translatedText || data.originalText;
                        
                        // Debug essencial
                        console.log('🔍 Texto para síntese:', textToSpeak, '| Status:', data.translationStatus);
                        
                        if (!textToSpeak || textToSpeak.trim() === '') {
                            showStatus('❌ ERRO: TEXTO VAZIO PARA SÍNTESE', 'danger');
                            return;
                        }
                        
                        processVoiceSynthesis(textToSpeak);
                    } else {
                        showStatus('ERRO: FALHA NO PROCESSAMENTO NEURAL', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showStatus('ERRO: CONEXÃO COM SERVIDOR NEURAL PERDIDA - ' + error.message, 'danger');
                    
                    // Se falhar completamente, tentar síntese local
                    if (text) {
                        showStatus('TENTANDO SÍNTESE LOCAL DE EMERGÊNCIA...', 'warning');
                        setTimeout(() => {
                            processVoiceSynthesis(text);
                        }, 1000);
                    }
                })
                .finally(() => {
                    processBtn.disabled = false;
                    processBtn.innerHTML = '⚡ INITIATE NEURAL SYNTHESIS ⚡';
                });
            }, 1000);
        });
        
        function processVoiceSynthesis(text) {
            console.log('🎵 Síntese:', text.substring(0, 50) + (text.length > 50 ? '...' : ''), '| Idioma:', selectedLanguage);
            
            if ('speechSynthesis' in window) {
                speechSynthesis.cancel();
                
                const utterance = new SpeechSynthesisUtterance(text);
                
                // Aplicar configurações dos controles
                utterance.rate = parseFloat(rateControl.value);
                utterance.pitch = parseFloat(pitchControl.value);
                utterance.volume = parseFloat(volumeControl.value);
                utterance.lang = selectedLanguage;
                
                // Aplicar voz selecionada
                const selectedVoiceName = voiceSelect.value;
                
                if (selectedVoiceName) {
                    const selectedVoice = availableVoices.find(voice => voice.name === selectedVoiceName);
                    if (selectedVoice) {
                        utterance.voice = selectedVoice;
                        currentVoice = selectedVoice;
                        console.log('✅ Voz:', selectedVoice.name);
                    }
                } else {
                    // Tentar encontrar uma voz para o idioma selecionado
                    const langConfig = languageMap[selectedLanguage] || { codes: ['pt'], preferredVoices: [] };
                    const autoVoice = availableVoices.find(voice => 
                        langConfig.codes.some(code => voice.lang.toLowerCase().includes(code.toLowerCase()))
                    );
                    if (autoVoice) {
                        utterance.voice = autoVoice;
                        currentVoice = autoVoice;
                        console.log('🎯 Auto-voz:', autoVoice.name);
                    }
                }
                
                utterance.onstart = function() {
                    const voiceName = currentVoice ? currentVoice.name : 'Padrão';
                    const langName = {
                        'pt-BR': 'PORTUGUÊS'
                    }[selectedLanguage] || selectedLanguage;
                    
                    showStatus(`🎵 TRANSMISSÃO ${langName} | VOZ: ${voiceName.toUpperCase()}`, 'info');
                    document.body.style.animation = 'pulse 0.5s infinite alternate';
                };
                
                utterance.onend = function() {
                    showStatus('✅ SÍNTESE NEURAL CONCLUÍDA COM SUCESSO', 'success');
                    document.body.style.animation = '';
                };
                
                utterance.onerror = function(event) {
                    showStatus('❌ ERRO NA TRANSMISSÃO: ' + event.error.toUpperCase(), 'danger');
                    document.body.style.animation = '';
                };
                
                // Log das configurações
                console.log('🎵 Configurações de Voz:', {
                    language: selectedLanguage,
                    voice: currentVoice?.name || 'Padrão',
                    voiceLang: currentVoice?.lang || 'auto',
                    rate: utterance.rate,
                    pitch: utterance.pitch,
                    volume: utterance.volume
                });
                
                speechSynthesis.speak(utterance);
                
            } else {
                showStatus('❌ SISTEMA DE SÍNTESE NEURAL NÃO SUPORTADO', 'danger');
            }
        }
        
        function showStatus(message, type) {
            const alertClass = `alert alert-${type}`;
            
            statusDisplay.innerHTML = `
                <div class="${alertClass}">
                    > ${message}
                </div>
            `;
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                const alert = statusDisplay.querySelector('.alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }
        
        // Efeito de inicialização
        window.addEventListener('load', function() {
            setTimeout(() => {
                showStatus('SISTEMA NEURAL INICIALIZADO. PRONTO PARA SÍNTESE DE VOZ.', 'success');
            }, 1000);
        });
        
        // Efeitos de teclado Matrix
        document.addEventListener('keydown', function(e) {
            if (Math.random() < 0.05) {
                const glitchElements = document.querySelectorAll('.terminal-window');
                glitchElements.forEach(el => {
                    el.classList.add('glitch');
                    setTimeout(() => el.classList.remove('glitch'), 300);
                });
            }
        });
    </script>
</body>
</html>
