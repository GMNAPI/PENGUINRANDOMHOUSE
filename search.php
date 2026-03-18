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

$autores = [];
foreach (array_keys($autoresMap) as $nombreAutor) {
    $librosAutor = array_values(array_filter(
        $dataset,
        fn($l) => $l['autor'] === $nombreAutor
    ));
    usort($librosAutor, fn($a, $b) => strcmp($b['fecha_nov'], $a['fecha_nov']));
    $autores[] = [
        'autor'         => $nombreAutor,
        'ultimos_libros' => array_slice($librosAutor, 0, 2),
    ];
}

echo json_encode(['libros' => $libros, 'autores' => $autores], JSON_UNESCAPED_UNICODE);
