# ADR-0002: Búsqueda por coincidencia exacta de campo

## Estado

Actualizado (decisión revisada)

## Contexto

El enunciado pide buscar por "palabras exactas". Esta expresión admite varias interpretaciones:

1. **Coincidencia exacta de la cadena completa** (`$campo === $texto`): "Jorge Franco" devuelve solo registros cuyo campo vale exactamente "Jorge Franco".
2. **Subcadena literal, case-insensitive** (`stripos`): "Jorge" aparece en "Jorge Franco", "jorge amado", "Borges" (falso positivo).
3. **Coincidencia de palabra completa con límites de palabra** (regex `\bJorge\b`): evita falsos positivos como "Borges", pero añade complejidad.
4. **Fuzzy matching / similitud fonética**: descartado explícitamente por el enunciado.

La implementación inicial usaba `stripos` (opción 2). Tras revisar el enunciado, "palabras exactas" se interpreta literalmente: el campo completo debe coincidir con el texto buscado.

## Decisión

Usar `$campo === $texto` para la búsqueda. Coincidencia exacta, case-sensitive, sin transformaciones.

## Consecuencias

**Positivas:**
- Semánticamente fiel al enunciado ("palabras exactas" = campo idéntico al parámetro).
- Sin falsos positivos: buscar "Jorge" no devuelve "Jorge Franco".
- Rendimiento O(n) — comparación de strings, sin backtracking.

**Negativas:**
- La API es muy estricta: el cliente debe enviar el nombre de autor o título exacto.
- Case-sensitive: "jorge franco" ≠ "Jorge Franco". Si se necesitara case-insensitive, bastaría con `strtolower($campo) === strtolower($texto)`.
- "Sin patrones" del enunciado se interpreta como sin fuzzy/regex, no como sin subcadenas — esta interpretación podría revisarse si el entrevistador aclara lo contrario.
