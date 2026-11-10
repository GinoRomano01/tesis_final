<?php
/*
require_once __DIR__ . '/../config/core_config.php';

class Db {

    private static $instance = null;
    private $connection;

    private function __construct() {

        $engine  = IS_LOCAL ? LDB_ENGINE  : DB_ENGINE;
        $host    = IS_LOCAL ? LDB_HOST    : DB_HOST;
        $dbname  = IS_LOCAL ? LDB_NAME    : DB_NAME;
        $user    = IS_LOCAL ? LDB_USER    : DB_USER;
        $pass    = IS_LOCAL ? LDB_PASS    : DB_PASS;
        $charset = IS_LOCAL ? LDB_CHARSET : DB_CHARSET;

        try {
            $dsn = "$engine:host=$host;dbname=$dbname;charset=$charset";

            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

        } catch (PDOException $e) {
            die('❌ Error DB: ' . $e->getMessage());
        }
    }
*/
    /**
     * Devuelve una única instancia de la DB (Singleton)
     */
/*
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Devuelve la conexión PDO
     */
/*
    public function getConnection() {
        return $this->connection;
    }
}
*/

require_once __DIR__ . '/../config/core_config.php';

class Db {

    private static $instance = null;
    private $connection;

    private function __construct() {
        $engine  = IS_LOCAL ? LDB_ENGINE  : DB_ENGINE;
        $host    = IS_LOCAL ? LDB_HOST    : DB_HOST;
        $dbname  = IS_LOCAL ? LDB_NAME    : DB_NAME;
        $user    = IS_LOCAL ? LDB_USER    : DB_USER;
        $pass    = IS_LOCAL ? LDB_PASS    : DB_PASS;
        $charset = IS_LOCAL ? LDB_CHARSET : DB_CHARSET;

        try {
            $dsn = "$engine:host=$host;dbname=$dbname;charset=$charset";

            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

        } catch (PDOException $e) {
            die('❌ Error DB: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve una única instancia de la DB (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Devuelve la conexión PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * MÉTODO AGREGADO: Ejecutar consultas SQL preparadas
     * Este método es usado por la clase Model
     * 
     * @param string $sql Consulta SQL con placeholders (?)
     * @param array $params Parámetros para bind
     * @return PDOStatement Objeto con los resultados
     */
    public static function query($sql, $params = []) {
        $instance = self::getInstance();
        $connection = $instance->getConnection();
        
        try {
            $stmt = $connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die('❌ Error en consulta: ' . $e->getMessage() . '<br>SQL: ' . $sql);
        }
    }
}