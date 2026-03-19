# Prueba Técnica Penguin Random House: Buscador de Libros y Autores (PHP)

Este proyecto consiste en un microservicio desarrollado en PHP para la consulta de un dataset de libros y autores. Se ha priorizado la eficiencia, la legibilidad y la documentación de las decisiones arquitectónicas.

## 🚀 Instalación y Uso

El servicio no requiere dependencias externas ni configuración de base de datos.

1.  **Requisitos:** Tener PHP 7.4 o superior instalado.
2.  **Preparación:** Asegurarse de que el archivo `dataset.json` se encuentra en el mismo directorio que `search.php`.
3.  **Ejecución:** Puedes levantar el servidor interno de PHP para pruebas locales:
    ```bash
    php -S localhost:8000
    ```
4.  **Consulta:** El servicio acepta peticiones GET a través del parámetro `texto`.
    * Ejemplo: `http://localhost:8000/search.php?texto=Cervantes`

## 📋 Especificaciones del Servicio

- **Búsqueda Exacta:** Siguiendo estrictamente las notas del enunciado ("palabras exactas"), el sistema realiza una comparación de cadena completa (Case-Sensitive).
- **Formato de Salida:** Devuelve un objeto JSON con dos arrays independientes: `libros` (coincidencias por título) y `autores` (coincidencias por nombre).
- **Lógica de Autores (Ampliación):** Cada autor incluye una propiedad adicional con sus dos últimos libros publicados, ordenados por fecha de forma descendente.

## 🏗 Documentación de Decisiones (ADRs)

Para esta prueba se han redactado **Architecture Decision Records (ADR)** que detallan el razonamiento técnico tras la solución:

1.  [**ADR-0001: Entrega en fichero único**](./docs/adr/0001-single-file-delivery.md) - Por qué se optó por un script plano en lugar de un framework.
2.  [**ADR-0002: Búsqueda por coincidencia exacta**](./docs/adr/0002-busqueda-stripos.md) - Análisis del requisito "palabras exactas" y la decisión tomada tras la consulta técnica.
3.  [**ADR-0003: Deduplicación con Hash Map**](./docs/adr/0003-deduplicacion-autores-hashmap.md) - Optimización del rendimiento al procesar autores únicos.
4.  [**ADR-0004: Ordenación mediante strcmp**](./docs/adr/0004-ordenacion-fecha-strcmp.md) - Justificación del uso de comparación lexicográfica para fechas `YYYYMMDD`.

## 🛠 Consideraciones Técnicas

- **Eficiencia:** El dataset se procesa de forma lineal, realizando la búsqueda, deduplicación y preparación de datos en el menor número de iteraciones posible.
- **Robustez:** Se han incluido cabeceras adecuadas para la respuesta JSON y una validación básica de la existencia del dataset.
- **Escalabilidad:** Aunque la entrega es un fichero único, la lógica está separada funcionalmente para facilitar una futura migración a una arquitectura más compleja si fuera necesario.

---
**Nota sobre la búsqueda:** Ante la ambigüedad en la interpretación de "palabras exactas" y para asegurar el cumplimiento de las restricciones del enunciado, se ha implementado el **Match Exacto**. La lógica es fácilmente extensible a búsquedas por subcadena según se detalla en la documentación técnica adjunta.