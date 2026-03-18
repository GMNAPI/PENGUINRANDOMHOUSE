# ADR-0003: Deduplicación de autores mediante hash map

## Estado

Aceptado

## Contexto

Varios registros del dataset comparten el mismo valor en el campo `autor` (por ejemplo, "Jorge Franco" aparece en múltiples libros). El array `autores` de la respuesta debe contener cada autor **una sola vez**, independientemente de cuántos libros suyos coincidan con la búsqueda.

Las opciones evaluadas fueron:

1. **Array plano + `array_unique`**: acumular los nombres en un array y deduplicar al final. Requiere una pasada extra y tiene coste O(n log n).
2. **Hash map** (`$autoresMap[$nombre] = true`): usar el nombre del autor como clave del array asociativo. PHP garantiza unicidad de claves; la inserción y la comprobación de existencia son O(1) amortizado.
3. **Búsqueda con `in_array`**: comprobar antes de insertar si el autor ya existe. O(n²) en el peor caso, inaceptable.

## Decisión

Usar un array asociativo `$autoresMap` donde la clave es el nombre exacto del autor y el valor es `true`. Esto deduplica automáticamente durante la iteración del dataset, sin necesitar una fase de limpieza posterior.

```php
$autoresMap[$libro['autor']] = true;
// ...
foreach (array_keys($autoresMap) as $nombreAutor) { ... }
```

## Consecuencias

**Positivas:**
- Deduplicación implícita y eficiente: una única iteración del dataset produce el conjunto de autores únicos.
- Sin allocaciones adicionales: no se crea un array intermedio duplicado que luego se limpia.
- `array_keys()` sobre el mapa produce la lista de nombres en orden de primera aparición, que es determinista.

**Negativas:**
- Sensible a diferencias tipográficas: "Jorge Franco" y "jorge franco" serían dos claves distintas. En este dataset los nombres parecen normalizados, pero en un sistema real habría que normalizar la clave (e.g., `mb_strtolower`).
- El valor `true` es semánticamente vacío; en una refactorización futura podría almacenarse el objeto autor completo para evitar el segundo recorrido del dataset en la fase de `ultimos_libros`.
