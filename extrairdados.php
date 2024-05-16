<?php
// URL do site Recorte Digital que você deseja raspar
$url = 'https://recortedigital.oabmg.org.br/';

// Inicializa o cURL
$ch = curl_init();

// Configura as opções do cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Limite de redirecionamentos
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36'); // Simular um navegador
curl_setopt($ch, CURLOPT_TIMEOUT, 90); // Tempo limite da requisição em segundos

// Executa a requisição e obtém o HTML
$html = curl_exec($ch);

// Verifica se ocorreu algum erro durante a requisição
if(curl_errno($ch)) {
    echo 'Erro ao fazer a requisição: ' . curl_error($ch);
    exit;
}

// Fecha a conexão cURL
curl_close($ch);

// Suprime os erros de HTML mal formado
libxml_use_internal_errors(true);

// Cria um novo objeto DOMDocument
$dom = new DOMDocument();

// Carrega o HTML na DOMDocument, tratando-o como UTF-8
$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

// Restaura quaisquer erros que ocorreram durante o carregamento do HTML
libxml_clear_errors();

// Tratamento de erro para ignorar exceções
try {
    // Encontra todos os elementos <input>
    $inputs = $dom->getElementsByTagName('input');

    // Itera sobre os elementos encontrados e imprime seus atributos
    foreach ($inputs as $input) {
        // Obtém o valor do atributo "name"
        $name = $input->getAttribute('name');
        
        // Obtém o valor do atributo "value"
        $value = $input->getAttribute('value');

        // Imprime o nome e valor do atributo para cada elemento <input>
        echo "Nome: $name, Valor: $value\n";
    }
} catch (Exception $e) {
    // Trata exceções, se houver
    echo "Ocorreu um erro durante o scraping: " . $e->getMessage();
}
