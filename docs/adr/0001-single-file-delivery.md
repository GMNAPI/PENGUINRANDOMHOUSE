# ADR-0001: Entrega como fichero PHP único sin framework

## Estado

Aceptado

## Contexto

El enunciado especifica explícitamente *"se espera un fichero PHP"* (singular). Las opciones disponibles eran:

- Un único fichero `search.php` con toda la lógica embebida
- Una aplicación Symfony o Laravel con controladores, servicios y rutas
- Un micro-framework como Slim o Lumen
- Múltiples ficheros PHP sin framework (router, servicio, repositorio…)

El objetivo declarado de la prueba es evaluar el **flujo de trabajo y la forma de pensar**, no la capacidad de configurar un framework. El dataset es estático (un fichero JSON de 2000 registros), no existe estado persistente, y el endpoint es de solo lectura.

## Decisión

Entregar un único fichero `search.php` que contenga toda la lógica: lectura del dataset, búsqueda, construcción de la respuesta y serialización JSON.

## Consecuencias

**Positivas:**
- Cumple literalmente el requisito del enunciado.
- Sin dependencias externas: no requiere `composer install`, configuración de entorno ni servidor de aplicación específico. Funciona con el servidor integrado de PHP (`php -S`).
- La lógica es completamente legible en una sola pasada del fichero.

**Negativas:**
- No es escalable hacia un producto real: añadir autenticación, caché, logging estructurado o tests unitarios requeriría refactorizar a una arquitectura multicapa.
- Sin inyección de dependencias: el fichero carga el dataset directamente con `file_get_contents`, lo que dificulta el testing aislado.
