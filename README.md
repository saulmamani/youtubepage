## DOCUMENTACIÓN DEL SISTEMA

© Saul Mamani M.  
+591 76137269  
[https://saulmamani.github.io](https://saulmamani.github.io/)

##### Demo: [https://yourubeapp.000webhostapp.com](https://yourubeapp.000webhostapp.com/)

---

En el presente documento se muestra las **desciciones tomadas** sobre la arquitectura y diseño del sistema.

## Arquitectura del sistema

El sistema “Bridge” se comunica con el servidor RapidApi para  recoger los datos, estos datos son procesados (mapeados) por el sistema “Bridge” y publicados en servicios REST para que el sistema Frontend los pueda utilizar. Los usuarios solamente interactuan con el sistema Frontend.

Para que el "Bridge" pueda consumir los servicios de RapidApi, este necesita enviar las solicitudes con Token (X-RapidAPI-Key).

Para que el sistema Frontend pueda consumir los servicios brindados por el “Bridge”, debe enviar las solicitudes con un token del tipo Bearer (api\_token) 

![](https://yourubeapp.000webhostapp.com/documents/Arquitectura.png)

*   Sistema Backend “Bridge” 
    *   Lenguaje de programación **PHP 8**
    *   **Lumen 8** como microframework Laravel
    *   Para almacenar información en **caché**, se utiliza **File caché**, intregrado en Lumen
*   Sistema Frontend “Web App”
    *   Lenguaje de programación **Javascript** y **TypeScript**
    *   **Vuejs 2** como framework JS
    *   **Vuetify** para el diseño de las interfaces de usuario
    *   **Pinia,** para manejar el **Store** de Vuejs
    *   **Axios Observable**, para las solicitudes **http** al sistema bridge 

## Diseño del sistema en el Backend

Se tomarón las siguientes  desciciones:

**Patrón de diseño “Factory Method”**

Para recuperar los datos desde el servicio de youtube (RapidApi), o desde el **Cache File** de la aplicación (con duración de dos horas) se ha visto por conveniente implementar el patron de diseñon **Factory Method**. 

![](https://yourubeapp.000webhostapp.com/documents/FactoryMethod.png)

*   **/app/Patterns/FactoryPattern/IDataSource**: Interfaz que van a implementer todas las clases que tiene acceso a los datos
*   **/app/Patterns/FactoryPattern/YoutubeData**: Clase que se cumunica con el servidor de **RapidApi** para recoger los datos y luego mapearlos. Una vez mapedo los datos mas importantes, éstos son almacenados en Cache File, con una duración de dos horas.
*   **/app/Patterns/FactoryPattern/LocalData**:  Clase que recoge la información solicitada desde el Cache File.
*   **/app/Patterns/FactoryPattern/DataFactory**:  Clase encargada de recoger los datos, ya sea desde el servidor RapidApi o desde el Cache File.
*   **/app/Http/Controllers/VideoController**: Clase controlador que implementa el patrón (Aplicando **Inyección de dependencias**) y publica los servicios API REST por medio de las rutas 

Además la clase **YoutubeData** usa las clases Mappers, para mapear los datos con la información necesaria para el Frontend.

![](https://yourubeapp.000webhostapp.com/documents/Mappers.png)

**Mecanismos de seguridad Backend**

Para que el "Bridge" pueda consumir los servicios de RapidApi, este necesita enviar las solicitudes con Token (X-RapidAPI-Key).

Para que el sistema Frontend pueda consumir los servicios brindados por el “Bridge”, debe enviar las solicitudes con un token del tipo Bearer (api\_token). Estos tokens, se los registrar en el archivo de configuración “**.env**” de Lumen. 

```plaintext
APP_NAME=YoutubeApp
APP_ENV=local
APP_KEY=xxxxxx   ## Token de Lumen para proteger las rotas de Lumen
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=America/La_Paz

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

APP_API_KEY=xxxxxx  ## Token para consumer los servicios de RapidApi

```

## Diseño del sistema en el Frontend

Para el desarrollo del Frontend, se ha utilizado Vuejs y TypeScript.

El proyecto tiene la siguiente organización de carpetas. 

*   /config: Axios para el consumo de servicios REST ofrecidos por el sistema “Bridge”
*   /models: Clases de los Modelos 
*   /services: Lógica de la aplicación
*   /views: Interfaces de usuario

**Mecanismos de seguridad Frontend**

```plaintext
VUE_APP_URL=https://youtubeassureapp.herokuapp.com  ## URL del backend
VUE_APP_API_KEY=xxxxxx    ## API_KEY Para consumir los servicios del sistema "Bridge"  
```

## Recomendaciones

*   Para almacenar el cache local, se recomienda utilizar una base de datos en memoria mas robusta como **REDIS** 
*   La autenticacion (api\_token) en el sistema “Bridge” es estática (Se hizo de esta manera por comodidad, ya que el sistema Bridge, no usa base de datos). Se recomienda autenticación con base de datos SQL. 

**Demo:**

[**https://yourubeapp.000webhostapp.com**](https://yourubeapp.000webhostapp.com)