<?php 
// Nombre: Luis Vega Rodríguez
// Versión: 1.1
// Autor: Luis Vega Rodríguez
// Fecha: 2023-10-23

/**
 * @author Luis Vega Rodríguez
 * @version 1.0
 * @date 2023-10-23
 *
 * Descripción: Funciones para gestionar productos en la base de datos.
 */

/**
 * Guarda un nuevo producto en la base de datos.
 *
 * @param PDO $pdo La instancia PDO con conexión válida a la base de datos.
 * @param array $data Un array asociativo que contiene los datos del producto a guardar.
 *   - 'nombre' (string): Nombre del producto.
 *   - 'codigo_ean' (string): Código EAN del producto.
 *   - 'unidades' (int): Cantidad de unidades disponibles.
 *   - 'precio' (float): Precio del producto.
 *   - 'categoria' (string): Categoría del producto.
 *   - 'propiedades' (array, opcional): Propiedades adicionales del producto.
 *
 * @return int|false Retorna el ID del producto insertado si tiene éxito, o false en caso de error.
 *
 * @note Esta función utiliza consultas preparadas para prevenir inyección SQL y manejar los datos correctamente.
 */
function guardarProducto($pdo, $data) { 
    try { 
        // Preparamos la consulta SQL para insertar un nuevo producto en la tabla 'productos'.
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, codigo_ean, unidades, precio, categoria, propiedades) VALUES (:nombre, :codigo_ean, :unidades, :precio, :categoria, :propiedades)"); 

        // Convertimos el array de propiedades a una cadena separada por comas.
        // Si 'propiedades' no está definido en $data, se asigna null.
        $propiedades = isset($data['propiedades']) ? implode(',', $data['propiedades']) : null; 

        // Vinculamos los parámetros a la consulta preparada.
        $stmt->bindParam(':nombre', $data['nombre']); 
        $stmt->bindParam(':codigo_ean', $data['codigo_ean']); 
        $stmt->bindParam(':unidades', $data['unidades']); 
        $stmt->bindParam(':precio', $data['precio']); 
        $stmt->bindParam(':categoria', $data['categoria']); 
        $stmt->bindParam(':propiedades', $propiedades); 

        // Ejecutamos la consulta SQL. Si tiene éxito...
        if ($stmt->execute()) { 
            return $pdo->lastInsertId(); // Retorna el ID autogenerado del último registro insertado.
        } else { 
            return false; // En caso de error al ejecutar la consulta, retorna false.
        } 
    } catch (PDOException $e) { 
        // Aquí podrías registrar el error si es necesario:
        // error_log($e->getMessage());
        return false; // Manejo de errores: si ocurre una excepción durante la operación, retorna false.
    } 
} 

/**
 * Elimina un producto de la base de datos por su ID.
 *
 * @param PDO $pdo La instancia PDO con conexión válida a la base de datos.
 * @param int $id_producto El ID del producto a eliminar.
 *
 * @return bool Retorna true si se eliminó un registro, false en caso contrario.
 *
 * @note Se recomienda validar que el ID sea válido antes de llamar a esta función para evitar eliminaciones accidentales.
 */
function eliminarProducto(PDO $pdo, int $id_producto): bool { 
    // Preparamos la consulta SQL para eliminar el producto especificando el ID.
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id"); 

    // Ejecutamos la consulta pasando el ID del producto como parámetro.
    $stmt->execute([':id' => $id_producto]); 

    // Comprobamos cuántas filas fueron afectadas por la consulta DELETE.
    return $stmt->rowCount() > 0; 
} 

?>