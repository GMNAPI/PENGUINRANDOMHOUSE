# ADR-0002: Búsqueda case-insensitive por subcadena con stripos

## Estado

Aceptado

## Contexto

El enunciado pide buscar por "palabras exactas". Esta expresión admite varias interpretaciones:

1. **Coincidencia exacta de la cadena completa** (`$campo === $texto`): "Jorge" solo devolvería registros cuyo campo vale exactamente "Jorge", sin nada más.
2. **Subcadena literal, case-insensitive** (`stripos`): "Jorge" aparece en "Jorge Franco", "jorge amado", "Borges" (falso positivo en este caso).
3. **Coincidencia de palabra completa con límites de palabra** (regex `\bJorge\b`): evita falsos positivos como "Borges", pero añade complejidad y el enunciado no especifica ese nivel de precisión.
4. **Fuzzy matching / similitud fonética**: descartado explícitamente por el enunciado.

El dataset contiene nombres de autor en formato "Nombre Apellido" y títulos con preposiciones, artículos y palabras compuestas. Una coincidencia exacta de campo completo devolvería demasiado pocos resultados para ser útil. El enunciado contextualiza "palabras exactas" como oposición a fuzzy, no como oposición a subcadenas.

## Decisión

Usar `stripos($campo, $texto) !== false` para la búsqueda. Esto realiza una búsqueda de subcadena literal sin distinción de mayúsculas/minúsculas, sin transformaciones fonéticas ni aproximaciones.

## Consecuencias

**Positivas:**
- Comportamiento predecible y transparente: el usuario obtiene resultados que contienen literalmente el texto buscado.
- Respeta acentos y caracteres UTF-8 correctamente con la configuración de locale adecuada.
- Rendimiento O(n·m) suficiente para 2000 registros sin índices.

**Negativas:**
- No distingue límites de palabra: buscar "an" matchea "an", "Franco", "fantasma", etc. Si el requisito real fuera límites de palabra, habría que migrar a `preg_match('/\b' . preg_quote($texto, '/') . '\b/i', $campo)`.
- Sensible a espacios extra en el texto de búsqueda (no se aplica `trim` implícito en `stripos`). Se podría añadir `trim($texto)` en el preprocesado del parámetro.
