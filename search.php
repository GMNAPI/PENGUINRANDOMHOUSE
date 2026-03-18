<?php
header('Content-Type: application/json; charset=utf-8');

$texto = $_GET['texto'] ?? '';

if (empty($texto)) {
    echo json_encode(['libros' => [], 'autores' => []]);
    exit;
}

$dataset = json_decode(file_get_contents(__DIR__ . '/dataset.json'), true);

$libros = [];
$autoresMap = [];

foreach ($dataset as $libro) {
    if (stripos($libro['titulo'], $texto) !== false) {
        $libros[] = $libro;
    }
    if (stripos($libro['autor'], $texto) !== false) {
        $autoresMap[$libro['autor']] = true;
    }
}

$autores = array_map(
    fn($nombre) => ['autor' => $nombre],
    array_keys($autoresMap)
);

echo json_encode(['libros' => $libros, 'autores' => $autores], JSON_UNESCAPED_UNICODE);
