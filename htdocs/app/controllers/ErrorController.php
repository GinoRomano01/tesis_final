<?php

class ErrorController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->title = 'Error - San Plácido';
    }
    public function index() {
        http_response_code(404);
        
        if (file_exists(VIEWS . 'error' . DS . 'View_error_404.php')) {
            require_once VIEWS . 'error' . DS . 'View_error_404.php';
        } else {
            echo '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Error 404 - Página no encontrada</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body class="bg-light">
                <div class="container mt-5">
                    <div class="card shadow text-center p-5">
                        <h1 class="display-1 text-danger">404</h1>
                        <h2 class="mb-4">Página no encontrada</h2>
                        <p class="text-muted mb-4">Lo sentimos, la página que buscás no existe.</p>
                        <a href="' . URL . '" class="btn btn-primary">Volver al inicio</a>
                    </div>
                </div>
            </body>
            </html>';
        }
    }

    public function servidor() {
        http_response_code(500);
        
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Error 500 - Error del servidor</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="card shadow text-center p-5">
                    <h1 class="display-1 text-danger">500</h1>
                    <h2 class="mb-4">Error del servidor</h2>
                    <p class="text-muted mb-4">Algo salió mal en el servidor.</p>
                    <a href="' . URL . '" class="btn btn-primary">Volver al inicio</a>
                </div>
            </div>
        </body>
        </html>';
    }
}