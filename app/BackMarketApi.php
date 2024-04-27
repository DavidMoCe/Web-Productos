<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;



class BackMarketApi{
    protected $client;
    protected $headers;

    // Constructor de la clase
    public function __construct(){
        // Configuración predeterminada de los encabezados
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Accept-Language' => 'es-es',
            'Authorization' => 'Basic ' .env('BACKMARKET_ACCESS_TOKEN'),
            'User-Agent' => 'FULLRENEW SL',
        ];

        // Inicialización del cliente GuzzleHTTP con la base URL y los encabezados predeterminados
        $this->client = new Client([
            'base_uri' => env('BACKMARKET_BASE_URL'), // URL base de la API de Back Market obtenida del archivo .env
            'headers' => $this->headers, // Encabezados combinados con los encabezados predeterminados y los proporcionados opcionalmente
        ]);
    }


   // Método para realizar solicitudes GET a la API de Back Market
    public function apiGet($end_point) {
        // Dar formato a $end_point quitándole una "/" al principio si existe
        if(substr($end_point, 0, 1) === '/') {
            $end_point = substr($end_point, 1);
        }

        $target_url = env('BACKMARKET_BASE_URL') . $end_point; // Construir la URL completa para la solicitud GET

        try {
            // Realizar la solicitud GET
            $response = $this->client->request('GET', $target_url);

            // Verificar si la solicitud fue exitosa (código de estado dentro del rango 2xx)
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                // Decodificar el contenido JSON de la respuesta y devolverlo
                $data = json_decode($response->getBody()->getContents(), true);
                return $data;
            } else {
                // Lanzar una excepción si el servidor de Back Market no devolvió una respuesta válida
                throw new \Exception("Error al realizar la solicitud GET: El servidor de Back Market no devolvió una respuesta válida (Código de estado: $statusCode)");
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Manejar errores de solicitud a la API de Back Market
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                switch ($statusCode) {
                    case 400:
                        throw new \Exception("Error al realizar la solicitud GET: Solicitud incorrecta (Código de estado: $statusCode)");
                        break;
                    case 403:
                        throw new \Exception("Error al realizar la solicitud GET: Acceso prohibido (Código de estado: $statusCode)");
                        break;
                    case 404:
                        throw new \Exception("Error al realizar la solicitud GET: El recurso solicitado no fue encontrado (Código de estado: $statusCode)");
                        break;
                    case 429:
                        throw new \Exception("Error al realizar la solicitud GET: Demasiadas solicitudes. Ha sido limitado por la tarifa (Código de estado: $statusCode)");
                        break;
                    default:
                        throw new \Exception("Error al realizar la solicitud GET: Error en la solicitud POST a la API de Back Market (Código de estado: $statusCode)");
                        break;
                }
            } else {
                throw new \Exception("Error al realizar la solicitud GET a la API de Back Market: " . $e->getMessage());
            }
        }
    }



    // Método para realizar solicitudes POST a la API de Back Market
    public function apiPost($end_point, $request = []) {
        // Dar formato a $end_point quitándole una "/" al principio si existe
        if(substr($end_point, 0, 1) === '/') {
            $end_point = substr($end_point, 1);
        }

        $target_url = env('BACKMARKET_BASE_URL') . $end_point; // Construir la URL completa para la solicitud POST

        try {
            // Realizar la solicitud POST
            $response = $this->client->request('POST', $target_url, [
                'headers' => $this->headers, // Utilizar los encabezados del constructor
                'json' => $request, // Datos a enviar en la solicitud POST en formato JSON
            ]);

            // Verificar si la solicitud fue exitosa (código de estado dentro del rango 2xx)
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 300) {
                // Decodificar el contenido JSON de la respuesta y devolverlo
                $data = json_decode($response->getBody()->getContents(), true);
                return $data;
            } else {
                 // Lanzar una excepción si el servidor de Back Market no devolvió una respuesta válida
                throw new \Exception("Error al realizar la solicitud POST: El servidor de Back Market no devolvió una respuesta válida (Código de estado: $statusCode)");
            }
        } catch (RequestException $e) {
            // Manejar errores de solicitud a la API de Back Market
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                switch ($statusCode) {
                    case 400:
                        throw new \Exception("Error al realizar la solicitud POST: Solicitud incorrecta (Código de estado: $statusCode)");
                        break;
                    case 403:
                        throw new \Exception("Error al realizar la solicitud POST: Acceso prohibido (Código de estado: $statusCode)");
                        break;
                    case 404:
                        throw new \Exception("Error al realizar la solicitud POST: El recurso solicitado no fue encontrado (Código de estado: $statusCode)");
                        break;
                    case 429:
                        throw new \Exception("Error al realizar la solicitud POST: Demasiadas solicitudes. Ha sido limitado por la tarifa (Código de estado: $statusCode)");
                        break;
                    default:
                        throw new \Exception("Error al realizar la solicitud POST: Error en la solicitud POST a la API de Back Market (Código de estado: $statusCode)");
                        break;
                }
            } else {
                throw new \Exception("Error al realizar la solicitud POST a la API de Back Market: " . $e->getMessage());
            }
        }
    }


    // Método para obtener todas las órdenes de la API de Back Market.
    // Si se proporciona $date_modification, devuelve todas las órdenes modificadas después de esa fecha.
    // Si se proporciona $date_creation, devuelve todas las órdenes creadas después de esa fecha.
    // @param string|false $date_modification - Fecha de modificación de las órdenes (en formato 'Y-m-d H:i:s'). Por defecto, se obtienen las órdenes modificadas en los últimos 60 días.
    // @param string|false $date_creation - Fecha de creación de las órdenes (en formato 'Y-m-d H:i:s'). Si se proporciona, se obtienen las órdenes creadas después de esta fecha.
    // @param array $param - Parámetros adicionales para filtrar las órdenes (opcional).
    // @return array - Un array de objetos que representan las órdenes.
    
    public function getAllOrders($date_modification = false, $date_creation = false, $param = array()) {
        $end_point = 'orders';

        // Si no se proporciona $date_modification, se obtienen las órdenes modificadas en los últimos 60 días por defecto
        if (!$date_modification) {
            $date_modification = date("Y-m-d H:i:s", time() - 60 * 24 * 60 * 60);
        }
        
        $end_point .= "?date_modification=$date_modification";

        if(count($param) > 0) {
            $end_point .= '&'.http_build_query($param);
        }

        // Resultado de la primera página
        $result = $this->client->apiGet($end_point);

        // Array de resultados de la primera página
        $result_array = $result->results;

        $result_next = $result;

        $page = 1;
        // Verificar si existe la siguiente página
        while (($result_next->next) != null) {
            $page++;
            // Obtener el nuevo endpoint
            $end_point_next_tail = '&page='."$page";
            $end_point_next = $end_point.$end_point_next_tail;
            // Obtener la nueva página de resultados
            $result_next = $this->client->apiGet($end_point_next);
            // Obtener el array de resultados de la nueva página
            $result_next_array = $result_next->results;
            // Agregar todas las órdenes de la página actual al $result_array
            foreach ($result_next_array as $value) {
                $result_array[] = $value;
            }
        }

        return $result_array;
    }


    // Método para obtener los datos de una orden según un $order_id específico.
    // @param string $order_id - ID de la orden específica.
    // @return object - Información de la orden como un objeto.

    public function getOneOrder($order_id) {
        $end_point = "orders/$order_id";
        return $this->client->apiGet($end_point);
    }


    // Método para obtener datos de nuevas órdenes cuyos estados son 0 o 1.
    // @param array $param - Parámetros de filtro adicionales (opcional).
    // @return array - Información de las nuevas órdenes en un array.
     
    public function getNewOrders($param = array()) {
        // Define los puntos finales para órdenes con estado 0 y 1
        $end_point_0 = 'orders?state=0';
        $end_point_1 = 'orders?state=1';

        // Si hay parámetros adicionales, agregarlos a los puntos finales
        if (count($param) > 0) {
            $end_point_0 .= '&' .http_build_query($param);
            $end_point_1 .= '&' .http_build_query($param);
        }

        // Obtener las órdenes con estado 0 y 1 respectivamente
        $result0 = $this->apiGet($end_point_0);
        $result1 = $this->apiGet($end_point_1);

        // Array para almacenar órdenes con estado 0 y 1
        $res0_array = $result0->results;
        $res1_array = $result1->results;

        // Obtener todas las páginas de resultados, si existen
        $result0_next = $result0;
        $result1_next = $result1;

        $page0 = 1;
        while (($result0_next->next) != null) {
            $page0++;
            $end_point_next0_tail = '&page=' ."$page0";
            $end_point_next0 = $end_point_0 .$end_point_next0_tail;
            $result0_next = $this->apiGet($end_point_next0);
            $result_next0_array = $result0_next->results;
            foreach ($result_next0_array as $value) {
                $res0_array[] = $value;
            }
        }

        $page1 = 1;
        while (($result1_next->next) != null) {
            $page1++;
            $end_point_next1_tail = '&page=' ."$page1";
            $end_point_next1 = $end_point_1 .$end_point_next1_tail;
            $result1_next = $this->apiGet($end_point_next1);
            $result_next1_array = $result1_next->results;
            foreach ($result_next1_array as $value) {
                $res1_array[] = $value;
            }
        }

        // Combinar las órdenes con estado 0 y 1 en un solo array
        $res0_array = array_merge($res0_array, $res1_array);

        // Devolver el array combinado
        return $res0_array;
    }


    // Método para validar o cancelar líneas de pedido.
    // Actualiza el estado de las líneas de pedido cuando el estado es 1: 1 -> 2 (o 1 -> 4)
    // Estado 1 -> 2 significa que la 'Orderline' ha sido aceptada por el comerciante, quien ahora debe preparar el 'Producto' para su envío.
    // (Estado 1 -> 4 significa que la 'Orderline' ha sido cancelada. El cliente recibirá un reembolso por la 'Orderline'.)
    // @param string $order_id - ID específico del pedido.
    // @param string $sku - SKU específico del listado.
    // @param boolean $validated - Indica si debe ser validado (true) o cancelado (false) (opcional, por defecto true).
    // @return object - Respuesta HTTP de la solicitud POST.

    public function validateOrderlines($order_id, $sku, $validated = true) {
        // Construir el punto final de la solicitud
        $end_point = 'orders/'.$order_id;

        // Determinar el nuevo estado de la línea de pedido (2 para validar, 4 para cancelar)
        $new_state = ($validated) ? 2 : 4;

        // Construir el cuerpo de la solicitud
        $request = array('order_id' => $order_id, 'new_state' => $new_state, 'sku' => $sku);
        $request_JSON = json_encode($request);

        // Realizar la solicitud POST
        $result = $this->apiPost($end_point, $request_JSON);

        return $result;
    }


    // Método para actualizar el estado de las líneas de pedido cuando el estado es 2: 2 -> 3 (o 2 -> 5)
    // Estado 2 -> 3 significa que el comerciante ha entregado la 'Orderline' a la empresa de envíos. La entrega del paquete está en curso.
    // (Estado 2 -> 5 significa que la 'Orderline' ha sido reembolsada antes del envío)
    // @param boolean $shipping - Indica si el pedido está en proceso de envío (true) o cancelado (false) (opcional, por defecto true).
    // @param string $order_id - ID específico del pedido.
    // @param string $tracking_num - Número de seguimiento específico.
    // @param string $shipper - Empresa o persona encargada de este pedido.
    // @param string|null $date_shipping - Marca de tiempo para la fecha y hora de envío (opcional).
    // @param string|null $tracking_url - URL correspondiente para el seguimiento (opcional).
    // @param string|null $sku - SKU específico del listado (opcional).
    // @return object - Respuesta HTTP de la solicitud POST.
    
    public function shippingOrderlines($shipping = true, $order_id, $tracking_num, $shipper, $date_shipping = null, $tracking_url = null, $sku = null) {
        $end_point = 'orders/'.$order_id;

        // Determinar el nuevo estado de la línea de pedido (3 para envío, 5 para cancelar)
        $new_state = ($shipping) ? 3 : 5;

        // Construir el cuerpo de la solicitud
        $request_shipping = array('order_id' => $order_id, 'new_state' => $new_state, 'tracking_number' => $tracking_num);
        if ($tracking_url != null) $request_shipping['tracking_url'] = $tracking_url;
        if ($date_shipping != null) $request_shipping['date_shipping'] = $date_shipping;
        if ($shipper != null) $request_shipping['shipper'] = $shipper;

        $request_JSON = json_encode($request_shipping);

        // Realizar la solicitud POST
        $result = $this->apiPost($end_point, $request_JSON);

        return $result;
    }


    //  Método para actualizar el estado de las líneas de pedido cuando el estado es 3: 3 -> 6
    //  Estado 3 -> 6 significa que la línea de pedido es reembolsada después del envío. El cliente realizó una solicitud de reembolso.
    //  @param string $order_id - ID específico del pedido.
    //  @param string $sku - SKU específico del listado.
    //  @param int $return_reason - Código de estado de la razón de devolución:
    //                              0: Error de stock.
    //                              1: Retiro durante el período legal de 14 días.
    //                              11: No vive en la dirección proporcionada.
    //                              12: El paquete no llegó a su destino.
    //                              13: Paquete perdido.
    //                              21: Producto defectuoso al abrir el paquete.
    //                              22: Falla durante el primer uso.
    //                              23: Falla durante el período de garantía.
    //                              24: Producto no conforme.
    //                              25: Otro.
    //  @param string|null $return_message - Mensaje enviado al cliente para una cancelación o un reembolso (opcional).
    //  @return object - Respuesta HTTP de la solicitud POST.

    public function refundAfterShipping($order_id, $sku, $return_reason, $return_message = null) {
        $end_point = 'orders/'.$order_id;

        // Determinar el nuevo estado de la línea de pedido (6 para reembolsar después del envío)
        $new_state = 6;

        // Construir el cuerpo de la solicitud
        $request = array('order_id' => $order_id, 'new_state' => $new_state, 'sku' => $sku, 'return_reason' => $return_reason);
        if ($return_message != null) $request['return_message'] = $return_message;

        $request_JSON = json_encode($request);

        // Realizar la solicitud POST
        $result = $this->apiPost($end_point, $request_JSON);

        return $result;
    }


    // Método para actualizar el estado de las líneas de pedido después de un reembolso o cancelación para los estados 1 y 2.
    // Estado 1 -> 4 significa que la 'Línea de pedido' ha sido cancelada. El cliente recibirá un reembolso por la 'Línea de pedido'.
    // Estado 2 -> 5 significa que la 'Línea de pedido' ha sido reembolsada antes del envío.
    // @param string $order_id - ID específico del pedido
    // @param string $sku - SKU específico del producto
    // @param boolean $trueForValidate - Si este indicador es verdadero, significa que es para el proceso de validación y el nuevo estado es 4,
    // de lo contrario, es para el proceso de envío y el nuevo estado es 5
    // @return object - la respuesta HTTP de la solicitud POST
    
    public function refundOrCancellation($order_id, $sku, $trueForValidate) {
        $end_point = 'orders/' . $order_id;
        
        // Durante el proceso de validación
        if ($trueForValidate) {
            $new_state = 4;
            // Construir el cuerpo de la solicitud cuando el estado es 4
            $request = array('order_id' => $order_id, 'new_state' => $new_state, 'sku' => $sku);
            $request_JSON = json_encode($request);

            $result = $this->apiPost($end_point, $request_JSON);
        }
        // Antes del proceso de envío
        else {
            $new_state = 5;
            // Construir el cuerpo de la solicitud cuando el estado es 5
            $request_cancelled = array('order_id' => $order_id, 'new_state' => $new_state, 'sku' => $sku);
            $request_JSON = json_encode($request_cancelled);

            $result = $this->apiPost($end_point, $request_JSON);
        }

        return $result;
    }

    
    // Método para obtener los datos de todos los listados basados en una fecha exacta y limitaciones.
    // @param string $publication_state - expresa la condición del producto
    // @param array $param - contiene todos los demás parámetros de filtro
    // @return array - la información de los listados en un array
    // @link GET https://www.backmarket.com/ws/$end_point

    public function getAllListings($publication_state = null, $param = array()) {
        $end_point = 'listings';
    
        // Agregar el estado de publicación si está presente
        if ($publication_state !== null) {
            $end_point .= "?publication_state=$publication_state";
        }
    
        // Agregar otros parámetros de filtro si están presentes
        if (count($param) > 0) {
            $end_point .= '&' . http_build_query($param);
        }
    
        // Resultado de la primera página
        $result = $this->apiGet($end_point);
        // print_r($result);

        // Array de resultados de la primera página
        $result_array = $result->results;

        $result_next = $result;

        $page = 1;
        // Verificar si existe la siguiente página
        while (($result_next->next) != null) {
            $page++;
            // Obtener el nuevo punto final
            $end_point_next_tail = '&page=' . "$page";
            $end_point_next = $end_point . $end_point_next_tail;
            // print_r($end_point_next);
            // El nuevo objeto de página
            $result_next = $this->apiGet($end_point_next);
            // El nuevo array de página
            $result_next_array = $result_next->results;
            // Agregar todos los listados en la página actual al $result_array
            foreach ($result_next_array as $key => $value) {
                array_push($result_array, $result_next_array[$key]);
            }
        }
        print_r($result_array);

        return $result_array;
    }






    // // Método para obtener las categorías de la API de Back Market
    // public function obtenerCategorias()
    // {
    //     try {
    //         // Realiza una solicitud GET para obtener las categorías
    //         $response = $this->client->request('GET', '/ws/category/tree');

    //         // Verifica si la solicitud fue exitosa (código de estado 200)
    //         if ($response->getStatusCode() == 200) {
    //             // Decodifica el contenido JSON de la respuesta y lo devuelve
    //             $categorias = json_decode($response->getBody()->getContents(), true);
    //             return $categorias;
    //         } else {
    //             // Lanza una excepción si el servidor de Back Market no devolvió una respuesta válida
    //             throw new \Exception("Error al obtener categorías: El servidor de Back Market no devolvió una respuesta válida.");
    //         }
    //     } catch (ConnectException $e) {
    //         // Maneja errores de conexión al servidor de Back Market
    //         throw new \Exception("Error de conexión al servidor de Back Market: " . $e->getMessage());
    //     } catch (RequestException $e) {
    //         // Maneja errores de solicitud a la API de Back Market
    //         if ($e->hasResponse()) {
    //             $statusCode = $e->getResponse()->getStatusCode();
    //             throw new \Exception("Error al realizar la solicitud a la API de Back Market: Código de estado {$statusCode}");
    //         } else {
    //             throw new \Exception("Error al realizar la solicitud a la API de Back Market: " . $e->getMessage());
    //         }
    //     } catch (\Exception $e) {
    //         // Maneja otras excepciones que puedan ocurrir durante la solicitud
    //         throw new \Exception("Error al obtener categorías: " . $e->getMessage());
    //     }
    // }






    // Otros métodos para interactuar con la API de Back Market...
}
