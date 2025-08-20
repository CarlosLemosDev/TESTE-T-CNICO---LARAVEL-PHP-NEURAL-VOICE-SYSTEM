<!DOCTYPE html>
<html>
<head>
    <title>Teste API</title>
</head>
<body>
    <h1>Teste da API Text-to-Speech</h1>
    
    <button onclick="testarAPI()">Testar API</button>
    <div id="resultado"></div>

    <script>
        async function testarAPI() {
            const resultado = document.getElementById('resultado');
            resultado.innerHTML = 'Testando...';
            
            try {
                const response = await fetch('/text-to-speech/convert', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        text: 'Olá mundo',
                        language: 'pt-BR'
                    })
                });
                
                const data = await response.json();
                
                resultado.innerHTML = `
                    <h3>Status: ${response.status}</h3>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                `;
                
            } catch (error) {
                resultado.innerHTML = `<p style="color: red;">Erro: ${error.message}</p>`;
            }
        }
    </script>
</body>
</html>
