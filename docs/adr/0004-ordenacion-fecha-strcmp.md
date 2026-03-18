# ADR-0004: Ordenación de libros por fecha usando comparación de strings

## Estado

Aceptado

## Contexto

El campo `fecha_nov` contiene fechas en formato `YYYYMMDD` (e.g., `"20170101"`). Para obtener los 2 últimos libros de cada autor hay que ordenar su catálogo por fecha descendente.

Las opciones evaluadas fueron:

1. **Parsear a `DateTime`**: convertir cada valor con `DateTime::createFromFormat('Ymd', $fecha)` y comparar objetos. Correcto semánticamente, pero introduce overhead de construcción de objetos por cada comparación.
2. **Castear a entero**: `(int)$fecha` convierte `"20170101"` a `20170101`. La comparación numérica funciona igual que la lexicográfica para este formato, pero el cast añade una operación innecesaria.
3. **Comparación directa de strings con `strcmp`**: el formato `YYYYMMDD` es lexicográficamente equivalente al orden cronológico. `strcmp("20200315", "20170101") > 0` es verdadero, igual que la comparación de fechas real.

## Decisión

Usar `strcmp($b['fecha_nov'], $a['fecha_nov'])` como comparador en `usort`. El orden de argumentos (`$b` antes que `$a`) produce ordenación descendente (más reciente primero).

```php
usort($librosAutor, fn($a, $b) => strcmp($b['fecha_nov'], $a['fecha_nov']));
```

## Consecuencias

**Positivas:**
- Sin overhead: `strcmp` es una operación nativa de bajo nivel, más rápida que construir objetos `DateTime`.
- Código conciso y directo: la intención (ordenar por fecha descendente) es legible sin conversiones intermedias.
- Correcto para el formato dado: `YYYYMMDD` garantiza que el orden lexicográfico coincide con el cronológico.

**Negativas:**
- Frágil ante cambios de formato: si `fecha_nov` cambiara a `DD/MM/YYYY` o timestamp Unix, esta comparación produciría resultados incorrectos sin error visible. Cualquier cambio en el formato del campo requiere revisar este comparador.
- No detecta valores malformados: una fecha inválida como `"99999999"` o una cadena vacía no lanzará ningún error, simplemente producirá un orden incorrecto.
