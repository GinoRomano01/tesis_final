<?php

class StockController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->verificarAdmin();
        $this->title = 'Stock — San Plácido Admin';

        if (!puedeVerStock()) {
            Toast::new('Sin permisos para acceder a Stock.', 'danger');
            Redirect::to('admin/LobbyAdmin');
            exit;
        }

        $this->model = new StockModel();
    }

    private function verificarAdmin(): void {
        if (!isset($_SESSION['usuario_id'])) {
            Toast::new('Debés iniciar sesión', 'warning');
            Redirect::to('login');
            exit;
        }
        if (($_SESSION['tipo_usuario'] ?? 2) == 2) {
            Toast::new('Sin permisos', 'danger');
            Redirect::to('');
            exit;
        }
    }

    public function index(): void {
        $model   = new StockModel();
        $buscar  = trim($_GET['buscar'] ?? '');
        $filtro  = (int)($_GET['tipo'] ?? 0);
        $stocks  = $model->listarStock($buscar, $filtro);
        $resumen = $model->resumenStock();
        $maderas = $model->getMaderasSelect();
        $insumos = $model->getInsumosSelect();
        $this->render('index', compact('stocks', 'resumen', 'buscar', 'filtro', 'maderas', 'insumos'));
    }

    public function guardarstock(): void {
        $model = new StockModel();
        $id    = (int)($_POST['Id'] ?? 0);

        $tipoMaterial = (int)($_POST['TipoMaterial'] ?? 0);
        $idMaterial   = (int)($_POST['IdMaterial']   ?? 0);

        if (!in_array($tipoMaterial, [1, 2]) || $idMaterial <= 0) {
            Toast::new('Seleccioná un material válido', 'warning');
            Redirect::to('stock');
            exit;
        }

        $d = [
            'IdMaterial'   => $idMaterial,
            'TipoMaterial' => $tipoMaterial,
            'Cantidad'     => str_replace(',', '.', $_POST['Cantidad'] ?? '0'),
            'FechaIngreso' => $_POST['FechaIngreso'] ?? null,
        ];

        if ((float)$d['Cantidad'] <= 0) {
            Toast::new('La cantidad debe ser mayor a 0', 'warning');
            Redirect::to('stock');
            exit;
        }

        if ($id > 0 && $this->_esConsumoDePedido($id)) {
            Toast::new('Ese registro es un consumo de pedido: se maneja desde Pedidos, no se edita a mano.', 'warning');
            Redirect::to('stock');
            exit;
        }

        if ($id > 0) {
            $model->editarStock($id, $d);
            Toast::new('Registro de stock actualizado', 'success');
        } else {
            $model->crearStock($d);
            Toast::new('Stock registrado correctamente', 'success');
        }

        $productoModel = new ProductoModel();
        $productoModel->recalcularTodos();

        Redirect::to('stock');
        exit;
    }

    public function eliminarstock($id = null): void {
        if (!puedeEliminar()) {
            Toast::new('No tenés permisos para eliminar registros.', 'danger');
            Redirect::to('stock');
            exit;
        }
        if (!$id) { Redirect::to('stock'); exit; }
        if ($this->_esConsumoDePedido((int)$id)) {
            Toast::new('Ese registro es un consumo de pedido: se devuelve al stock cuando el pedido vuelve a Pendiente o se cancela.', 'warning');
            Redirect::to('stock');
            exit;
        }
        (new StockModel())->borrarStock((int)$id);
        Toast::new('Registro eliminado', 'success');
        Redirect::to('stock');
        exit;
    }

    private function _esConsumoDePedido(int $id): bool {
        $row = (new StockModel())->getStockById($id);
        return $row && ($row['Motivo'] ?? '') === 'consumo';
    }

    public function importarexcel(): void {
        if (empty($_FILES['archivo_excel']['tmp_name'])) {
            Toast::new('No se seleccionó ningún archivo.', 'warning');
            Redirect::to('stock');
            exit;
        }

        $tmpPath = $_FILES['archivo_excel']['tmp_name'];
        $ext     = strtolower(pathinfo($_FILES['archivo_excel']['name'], PATHINFO_EXTENSION));
        $hojas   = $this->_parsearArchivoImportacion($tmpPath, $ext);

        if ($hojas === false) {
            Toast::new('No se pudo leer el archivo. Verificá el formato.', 'danger');
            Redirect::to('stock');
            exit;
        }

        $errores   = [];
        $okPrecios = 0;
        $okStock   = 0;

        if (!empty($hojas['maderas'])) {
            $r = $this->_procesarRefMaderas($hojas['maderas']);
            $okPrecios += $r['ok'];
            $errores    = array_merge($errores, $r['errores']);
        }
        if (!empty($hojas['insumos'])) {
            $r = $this->_procesarRefInsumos($hojas['insumos']);
            $okPrecios += $r['ok'];
            $errores    = array_merge($errores, $r['errores']);
        }

        if (!empty($hojas['stock'])) {
            $r = $this->_procesarHojaStock($hojas['stock']);
            $okStock += $r['ok'];
            $errores  = array_merge($errores, $r['errores']);
        }

        if ($okPrecios > 0) {
            Toast::new("{$okPrecios} fila(s) de catálogo actualizadas.", 'success');
        }
        if ($okStock > 0) {
            Toast::new("{$okStock} registro(s) de stock cargados/actualizados.", 'success');
        }
        if ($okPrecios === 0 && $okStock === 0 && empty($errores)) {
            Toast::new('El archivo no tenía filas para procesar.', 'warning');
        }
        if (!empty($errores)) {
            $_SESSION['import_errores'] = $errores;
            if ($okPrecios === 0 && $okStock === 0) {
                Toast::new('No se pudo importar nada. Revisá los errores.', 'danger');
            }
        }

        (new ProductoModel())->recalcularTodos();

        Redirect::to('stock');
        exit;
    }

    private function _procesarHojaStock(array $filas): array {
        $ok      = 0;
        $errores = [];

        foreach ($filas as $i => $fila) {
            $nro = $i + 2;

            $id             = (int)($fila['id'] ?? 0);
            $tipo           = strtolower(trim($fila['tipo'] ?? ''));
            $nombreMaterial = trim($fila['nombre_material'] ?? '');
            $cantidad       = (float)str_replace(',', '.', trim($fila['cantidad'] ?? ''));
            $fechaRaw       = trim($fila['fecha_ingreso'] ?? '');

            if (!in_array($tipo, ['madera', 'insumo'])) {
                $errores[] = "Fila $nro (Stock): tipo inválido '$tipo' — debe ser 'madera' o 'insumo'.";
                continue;
            }
            if (empty($nombreMaterial)) {
                $errores[] = "Fila $nro (Stock): Nombre Material está vacío.";
                continue;
            }
            if ($id > 0 && $this->_esConsumoDePedido($id)) {
                $errores[] = "Fila $nro (Stock): el registro #$id es un consumo de pedido y no se puede editar desde el Excel.";
                continue;
            }
            if ($cantidad <= 0) {
                $errores[] = "Fila $nro (Stock): Cantidad debe ser mayor a 0.";
                continue;
            }

            $tipoNum = $tipo === 'madera' ? 1 : 2;

            if ($tipo === 'madera') {
                $partes = $this->_parsearNombreMadera($nombreMaterial);
                if ($partes === null) {
                    $errores[] = "Fila $nro (Stock): no se pudo interpretar '{$nombreMaterial}' como madera. "
                        . "Usá '=' para traer el nombre exacto desde la hoja Ref Maderas, no lo escribas a mano.";
                    continue;
                }
                $idMaterial = $this->_resolverIdMaderaPorDimensiones(
                    $partes['tipo'], $partes['alto'], $partes['largo'], $partes['ancho'], $nro, $errores
                );
            } else {
                $idMaterial = $this->_resolverIdInsumoPorDescripcion($nombreMaterial, null, $nro, $errores);
            }

            if ($idMaterial === null) continue;

            $fecha = null;
            if (!empty($fechaRaw)) {
                $dt = \DateTime::createFromFormat('d/m/Y', $fechaRaw);
                if (!$dt) $dt = \DateTime::createFromFormat('d/m/Y H:i', $fechaRaw);
                if (!$dt) $dt = \DateTime::createFromFormat('Y-m-d', $fechaRaw);
                if ($dt)  $fecha = $dt->format('Y-m-d H:i:s');
            }
            $fecha = $fecha ?? date('Y-m-d H:i:s');

            try {
                if ($id > 0) {
                    Db::query("
                        UPDATE stock
                        SET IdMaterial = ?, TipoMaterial = ?, Cantidad = ?, FechaIngreso = ?
                        WHERE Id = ?
                    ", [$idMaterial, $tipoNum, $cantidad, $fecha, $id]);
                } else {
                    Db::query("
                        INSERT INTO stock (IdMaterial, TipoMaterial, Cantidad, FechaIngreso)
                        VALUES (?, ?, ?, ?)
                    ", [$idMaterial, $tipoNum, $cantidad, $fecha]);
                }
                $ok++;
            } catch (\Exception $e) {
                $errores[] = "Fila $nro (Stock): error al guardar — " . $e->getMessage();
            }
        }

        return ['ok' => $ok, 'errores' => $errores];
    }

    private function _procesarRefMaderas(array $filas): array {
        $ok      = 0;
        $errores = [];
        $model   = new StockModel();

        foreach ($filas as $i => $fila) {
            $nro = $i + 2;

            $id           = (int)($fila['id'] ?? 0);
            $tipoDeMadera = trim($fila['tipo_de_madera'] ?? '');
            $formato      = trim($fila['formato'] ?? '');
            $alto         = trim($fila['alto_cm']  ?? '');
            $largo        = trim($fila['largo_cm'] ?? '');
            $ancho        = trim($fila['ancho_cm'] ?? '');
            $precioRaw    = trim($fila['precio_unitario'] ?? '');

            if ($id <= 0 && $precioRaw === '') continue; 

            $precio = $precioRaw === '' ? null : (float)str_replace(',', '.', $precioRaw);
            if ($precio !== null && $precio < 0) {
                $errores[] = "Fila $nro (Ref Maderas): el precio no puede ser negativo.";
                continue;
            }

            if ($id > 0) {
                
                if ($tipoDeMadera === '' || $alto === '' || $largo === '' || $ancho === '') {
                    $errores[] = "Fila $nro (Ref Maderas): faltan datos (Tipo de Madera / Alto / Largo / Ancho).";
                    continue;
                }
                $idTipo = $model->buscarIdTipoMadera($tipoDeMadera);
                if ($idTipo === null) {
                    $errores[] = "Fila $nro (Ref Maderas): el tipo de madera '{$tipoDeMadera}' no existe en el "
                        . "catálogo de tipos. Creá el tipo primero desde el panel de Maderas.";
                    continue;
                }

                $model->editarMadera($id, [
                    'Alto'           => (float)str_replace(',', '.', $alto),
                    'Largo'          => (float)str_replace(',', '.', $largo),
                    'Ancho'          => (float)str_replace(',', '.', $ancho),
                    'IdTipodeMadera' => $idTipo,
                    'PrecioUnitario' => $precio ?? 0,
                    'Formato'        => $formato,
                ]);
                $ok++;
                continue;
            }

            
            if ($precio === null) continue;
            if ($tipoDeMadera === '' || $alto === '' || $largo === '' || $ancho === '') {
                $errores[] = "Fila $nro (Ref Maderas): faltan datos para identificar la madera "
                    . "(Tipo de Madera / Alto / Largo / Ancho). No modifiques esas columnas.";
                continue;
            }

            $idMaterial = $this->_resolverIdMaderaPorDimensiones(
                $tipoDeMadera,
                (float)str_replace(',', '.', $alto),
                (float)str_replace(',', '.', $largo),
                (float)str_replace(',', '.', $ancho),
                $nro, $errores
            );
            if ($idMaterial === null) continue;

            Db::query("UPDATE maderas SET PrecioUnitario = ? WHERE Id = ?", [$precio, $idMaterial]);
            $ok++;
        }

        return ['ok' => $ok, 'errores' => $errores];
    }

    private function _procesarRefInsumos(array $filas): array {
        $ok      = 0;
        $errores = [];
        $model   = new StockModel();

        foreach ($filas as $i => $fila) {
            $nro = $i + 2;

            $id             = (int)($fila['id'] ?? 0);
            $nombreMaterial = trim($fila['nombre_material'] ?? '');
            $tipoDeMaterial = trim($fila['tipo_de_material'] ?? '');
            $tipoDeCorte    = trim($fila['tipo_de_corte'] ?? '');
            $precioRaw      = trim($fila['precio_unitario'] ?? '');

            if ($id <= 0 && $precioRaw === '') continue;

            $precio = $precioRaw === '' ? null : (float)str_replace(',', '.', $precioRaw);
            if ($precio !== null && $precio < 0) {
                $errores[] = "Fila $nro (Ref Insumos): el precio no puede ser negativo.";
                continue;
            }

            if ($id > 0) {
                if ($nombreMaterial === '') {
                    $errores[] = "Fila $nro (Ref Insumos): el Nombre Material no puede quedar vacío.";
                    continue;
                }
                $idTipoMaterial = null;
                if ($tipoDeMaterial !== '') {
                    $idTipoMaterial = $model->buscarIdTipoMaterial($tipoDeMaterial);
                    if ($idTipoMaterial === null) {
                        $errores[] = "Fila $nro (Ref Insumos): el Tipo de Material '{$tipoDeMaterial}' no existe en el catálogo.";
                        continue;
                    }
                }
                $idTipoCorte = null;
                if ($tipoDeCorte !== '') {
                    $idTipoCorte = $model->buscarIdTipoCorte($tipoDeCorte);
                    if ($idTipoCorte === null) {
                        $errores[] = "Fila $nro (Ref Insumos): el Tipo de Corte '{$tipoDeCorte}' no existe en el catálogo.";
                        continue;
                    }
                }

                $actual = $model->getInsumoById($id);
                if (!$actual) {
                    $errores[] = "Fila $nro (Ref Insumos): el insumo Id {$id} ya no existe.";
                    continue;
                }

                $model->editarInsumo($id, [
                    'Descripcion'      => $nombreMaterial,
                    'IdTipodeMaterial' => $idTipoMaterial ?? $actual['IdTipodeMaterial'],
                    'IdTipodeCorte'    => $idTipoCorte    ?? $actual['IdTipodeCorte'],
                    'PrecioUnitario'   => $precio ?? (float)$actual['PrecioUnitario'],
                ]);
                $ok++;
                continue;
            }

            
            if ($precio === null) continue;
            if ($nombreMaterial === '') {
                $errores[] = "Fila $nro (Ref Insumos): falta el Nombre Material para identificar el insumo.";
                continue;
            }

            $idMaterial = $this->_resolverIdInsumoPorDescripcion(
                $nombreMaterial, $tipoDeMaterial ?: null, $nro, $errores
            );
            if ($idMaterial === null) continue;

            Db::query("UPDATE insumosdecarpinteria SET PrecioUnitario = ? WHERE Id = ?", [$precio, $idMaterial]);
            $ok++;
        }

        return ['ok' => $ok, 'errores' => $errores];
    }

    private function _resolverIdMaderaPorDimensiones(
        string $tipoMadera, float $alto, float $largo, float $ancho, int $nro, array &$errores
    ): ?int {
        $row = Db::query("
            SELECT ma.Id
            FROM maderas ma
            JOIN tipodemadera tm ON tm.Id = ma.IdTipodeMadera
            WHERE LOWER(TRIM(tm.Nombre)) = LOWER(TRIM(?))
              AND ABS(ma.Alto  - ?) < 0.01
              AND ABS(ma.Largo - ?) < 0.01
              AND ABS(ma.Ancho - ?) < 0.01
              AND ma.FechaBorrado IS NULL
            ORDER BY ma.Id ASC LIMIT 1
        ", [$tipoMadera, $alto, $largo, $ancho])->fetch();

        if (!$row) {
            $errores[] = "Fila $nro: no se encontró ninguna madera '{$tipoMadera}' de "
                . number_format($alto, 1) . 'x' . number_format($largo, 1) . 'x' . number_format($ancho, 1)
                . 'cm en el catálogo. Revisá la hoja Ref Maderas (no modifiques tipo ni dimensiones).';
            return null;
        }
        return (int)$row['Id'];
    }

    private function _resolverIdInsumoPorDescripcion(
        string $descripcion, ?string $tipoMaterial, int $nro, array &$errores
    ): ?int {
        $descripcion = trim($descripcion);

        $row = Db::query("
            SELECT Id FROM insumosdecarpinteria
            WHERE LOWER(TRIM(Descripcion)) = LOWER(?) AND FechaBorrado IS NULL
            ORDER BY Id ASC LIMIT 1
        ", [$descripcion])->fetch();

        if (!$row) {
            $params = ['%' . $descripcion . '%'];
            $sql    = "
                SELECT ic.Id
                FROM insumosdecarpinteria ic
                LEFT JOIN tipodematerial tmat ON tmat.Id = ic.IdTipodeMaterial
                WHERE ic.Descripcion LIKE ? AND ic.FechaBorrado IS NULL
            ";
            if (!empty($tipoMaterial)) {
                $sql     .= ' AND tmat.Nombre LIKE ?';
                $params[] = '%' . $tipoMaterial . '%';
            }
            $sql .= ' ORDER BY ic.Id ASC LIMIT 1';
            $row  = Db::query($sql, $params)->fetch();
        }

        if (!$row) {
            $errores[] = "Fila $nro: no se encontró el insumo '{$descripcion}' en el catálogo. Revisá la hoja Ref Insumos.";
            return null;
        }
        return (int)$row['Id'];
    }

    private function _parsearNombreMadera(string $nombre): ?array {
        if (preg_match('/^(.+?)\s+([\d.,]+)\s*x\s*([\d.,]+)\s*x\s*([\d.,]+)\s*cm\s*$/i', trim($nombre), $m)) {
            return [
                'tipo'  => trim($m[1]),
                'alto'  => (float)str_replace(',', '.', $m[2]),
                'largo' => (float)str_replace(',', '.', $m[3]),
                'ancho' => (float)str_replace(',', '.', $m[4]),
            ];
        }
        return null;
    }

    private function _parsearArchivoImportacion(string $tmpPath, string $ext): array|false {
        if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpPath);
                $resultado   = ['stock' => [], 'maderas' => [], 'insumos' => []];

                foreach ($spreadsheet->getAllSheets() as $sheet) {
                    $titulo = mb_strtolower(trim($sheet->getTitle()));
                    if ($titulo === 'stock') {
                        $resultado['stock'] = $this->_filasDesdeHoja($sheet);
                    } elseif (str_contains($titulo, 'madera')) {
                        $resultado['maderas'] = $this->_filasDesdeHoja($sheet);
                    } elseif (str_contains($titulo, 'insumo')) {
                        $resultado['insumos'] = $this->_filasDesdeHoja($sheet);
                    }
                }

                return $resultado;
            } catch (\Exception $e) {
                error_log('StockController::_parsearArchivoImportacion PhpSpreadsheet - ' . $e->getMessage());
            }
        }

        return $this->_parsearCsvSecciones($tmpPath);
    }

    private function _filasDesdeHoja(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array {
        $rows = $sheet->toArray(null, true, true, false);
        if (empty($rows)) return [];

        $headers = array_map([$this, '_slugHeader'], $rows[0]);

        while (!empty($headers) && end($headers) === '') {
            array_pop($headers);
        }

        $filas   = [];

        for ($i = 1; $i < count($rows); $i++) {
            $primeraCol = trim((string)($rows[$i][0] ?? ''));
            if (str_starts_with($primeraCol, '⚠') || str_starts_with($primeraCol, '👆')) {
                continue;
            }
            if (count($rows[$i]) < count($headers)) {
                $rows[$i] = array_pad($rows[$i], count($headers), '');
            } elseif (count($rows[$i]) > count($headers)) {
                $rows[$i] = array_slice($rows[$i], 0, count($headers));
            }
            $fila = array_combine($headers, $rows[$i]);
            if (empty(array_filter(array_map('trim', array_map('strval', $fila))))) continue;
            $filas[] = $fila;
        }
        return $filas;
    }

    private function _slugHeader(?string $h): string {
        $h = mb_strtolower(trim((string)($h ?? '')));
        $h = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'n'],
            $h
        );
        $h = preg_replace('/[^a-z0-9]+/', '_', $h);
        return trim($h, '_');
    }

    private function _parsearCsvSecciones(string $tmpPath): array|false {
        $handle = fopen($tmpPath, 'r');
        if (!$handle) return false;

        $resultado     = ['stock' => [], 'maderas' => [], 'insumos' => []];
        $seccionActual = null;
        $headers       = null;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $primera = trim((string)($row[0] ?? ''));

            if ($primera === '=== STOCK (importable) ===') {
                $seccionActual = 'stock'; $headers = null; continue;
            }
            if ($primera === '=== REFERENCIA MADERAS ===') {
                $seccionActual = 'maderas'; $headers = null; continue;
            }
            if ($primera === '=== REFERENCIA INSUMOS ===') {
                $seccionActual = 'insumos'; $headers = null; continue;
            }
            if ($seccionActual === null || $primera === '') continue;

            if (!$headers) {
                $headers = array_map([$this, '_slugHeader'], $row);
                continue;
            }

            if (count($row) < count($headers)) {
                $row = array_pad($row, count($headers), '');
            }
            $fila = array_combine($headers, $row);
            if (empty(array_filter($fila))) continue;
            $resultado[$seccionActual][] = $fila;
        }
        fclose($handle);
        return $resultado;
    }

    public function exportarexcel(): void {
        $model      = new StockModel();
        $buscar     = trim($_GET['buscar'] ?? '');
        $filtro     = (int)($_GET['tipo'] ?? 0);
        $filasStock = $model->exportarDatos($buscar, $filtro);
        $filasRefM  = $model->exportarRefMaderas();
        $filasRefI  = $model->exportarRefInsumos();
        $nombreBase = 'stock_' . date('Ymd_His');

        if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            try {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $shStock = $spreadsheet->getActiveSheet();
                $shStock->setTitle('Stock');
                $this->_escribirHoja($shStock, $filasStock, 'FF5C2D0A', [
                    'A' => 6, 'B' => 10, 'C' => 42, 'D' => 12, 'E' => 14, 'F' => 14, 'G' => 17,
                ]);
                $shStock->getColumnDimension('A')->setVisible(false);

                $ultimaFilaStock = count($filasStock) + 1;
                $shStock->setCellValue("A{$ultimaFilaStock}",
                    ' Ahora podés editar Cantidad y Fecha de Ingreso de un registro existente directamente en '
                    . 'esta hoja (se identifica por un Id interno oculto, no lo borres). Para cargar stock nuevo: '
                    . 'agregá una fila al final, completá Tipo y Cantidad, y en "Nombre Material" escribí "=" y '
                    . 'hacé clic en la celda correspondiente de Ref Maderas o Ref Insumos para traer el nombre '
                    . 'automáticamente. Precio Unitario y Monto Total son informativos y se ignoran al importar.'
                );
                $shStock->getStyle("A{$ultimaFilaStock}")->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['argb' => 'FF7A3E14'], 'size' => 9],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                               'startColor' => ['argb' => 'FFFFF8E1']],
                ]);
                $shStock->mergeCells("A{$ultimaFilaStock}:G{$ultimaFilaStock}");

                $shMad = $spreadsheet->createSheet();
                $shMad->setTitle('Ref Maderas');
                $this->_escribirHoja($shMad, $filasRefM, 'FF7A4E2D');
                $shMad->getColumnDimension('A')->setVisible(false);

                $ultimaFilaMad = count($filasRefM) + 1;
                $shMad->setCellValue("A{$ultimaFilaMad}",
                    ' Ahora podés editar Tipo de Madera, Formato, dimensiones y Precio Unitario de una fila '
                    . 'existente (se identifica por un Id interno oculto, no lo borres). El Tipo de Madera debe '
                    . 'coincidir con uno ya existente en el catálogo de tipos. Esta hoja no crea maderas nuevas.'
                );
                $shMad->getStyle("A{$ultimaFilaMad}")->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['argb' => 'FF5C3318'], 'size' => 9],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                               'startColor' => ['argb' => 'FFFFF3E0']],
                ]);
                $shMad->mergeCells("A{$ultimaFilaMad}:I{$ultimaFilaMad}");

                $shIns = $spreadsheet->createSheet();
                $shIns->setTitle('Ref Insumos');
                $this->_escribirHoja($shIns, $filasRefI, 'FF2A3A4A');
                $shIns->getColumnDimension('A')->setVisible(false);

                $ultimaFilaIns = count($filasRefI) + 1;
                $shIns->setCellValue("A{$ultimaFilaIns}",
                    ' Ahora podés editar el Nombre Material (Descripción), Tipo de Material, Tipo de Corte y '
                    . 'Precio Unitario de una fila existente (se identifica por un Id interno oculto, no lo '
                    . 'borres). Esta hoja no crea insumos nuevos.'
                );
                $shIns->getStyle("A{$ultimaFilaIns}")->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['argb' => 'FF1A2A3A'], 'size' => 9],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                               'startColor' => ['argb' => 'FFE3F2FD']],
                ]);
                $shIns->mergeCells("A{$ultimaFilaIns}:F{$ultimaFilaIns}");

                $spreadsheet->setActiveSheetIndex(0);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment; filename=\"{$nombreBase}.xlsx\"");
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                exit;

            } catch (\Exception $e) {
                error_log('StockController::exportarexcel - ' . $e->getMessage());
            }
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"{$nombreBase}.csv\"");
        header('Cache-Control: max-age=0');
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');

        fputcsv($output, ['=== STOCK (importable) ==='], ';');
        foreach ($filasStock as $fila) fputcsv($output, $fila, ';');

        fputcsv($output, [], ';');
        fputcsv($output, ['=== REFERENCIA MADERAS ==='], ';');
        foreach ($filasRefM as $fila) fputcsv($output, $fila, ';');

        fputcsv($output, [], ';');
        fputcsv($output, ['=== REFERENCIA INSUMOS ==='], ';');
        foreach ($filasRefI as $fila) fputcsv($output, $fila, ';');

        fclose($output);
        exit;
    }

    private function _escribirHoja(
        \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        array $filas,
        string $colorArgb = 'FF5C2D0A',
        array $anchos = []
    ): void {
        if (empty($filas)) return;

        foreach ($filas as $rowIdx => $fila) {
            foreach ($fila as $colIdx => $valor) {
                $sheet->setCellValueByColumnAndRow($colIdx + 1, $rowIdx + 1, $valor);
            }
        }

        $numCols   = count($filas[0]);
        $ultimaCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($numCols);
        $sheet->getStyle("A1:{$ultimaCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF' . ltrim($colorArgb, 'F')],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'       => ['argb' => 'FFFFFFFF'],
                ],
            ],
        ]);

        for ($r = 2; $r <= count($filas); $r++) {
            $bgColor = ($r % 2 === 0) ? 'FFFDF8F2' : 'FFFFFFFF';
            $sheet->getStyle("A{$r}:{$ultimaCol}{$r}")->applyFromArray([
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bgColor],
                ],
            ]);
        }

        foreach (range('A', $ultimaCol) as $col) {
            if (isset($anchos[$col])) {
                $sheet->getColumnDimension($col)->setWidth($anchos[$col]);
            } else {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $sheet->freezePane('A2');
    }

    public function maderas(): void {
        $model  = new StockModel();
        $buscar = trim($_GET['buscar'] ?? '');
        $this->render('maderas', [
            'maderas' => $model->listarMaderas($buscar),
            'tipos'   => $model->getTiposMadera(),
            'buscar'  => $buscar,
        ]);
    }

    public function guardarmaderas(): void {
        $model = new StockModel();
        $id    = (int)($_POST['Id'] ?? 0);
        $d = [
            'Alto'           => str_replace(',', '.', $_POST['Alto']   ?? '0'),
            'Largo'          => str_replace(',', '.', $_POST['Largo']  ?? '0'),
            'Ancho'          => str_replace(',', '.', $_POST['Ancho']  ?? '0'),
            'IdTipodeMadera' => (int)($_POST['IdTipodeMadera'] ?? 0),
            'PrecioUnitario' => str_replace(',', '.', $_POST['PrecioUnitario'] ?? '0'),
            'Formato'        => in_array($_POST['Formato'] ?? '', ['plancha', 'tablon'])
                                    ? $_POST['Formato'] : 'tablon',
        ];
        if ($d['IdTipodeMadera'] <= 0) {
            Toast::new('El tipo de madera es obligatorio', 'warning');
            Redirect::to('stock/maderas');
            exit;
        }
        if ((float)$d['PrecioUnitario'] < 0) {
            Toast::new('El precio no puede ser negativo', 'warning');
            Redirect::to('stock/maderas');
            exit;
        }
        if ($id > 0) {
            $model->editarMadera($id, $d);
            Toast::new('Madera actualizada', 'success');
        } else {
            $model->crearMadera($d);
            Toast::new('Madera creada en el catálogo', 'success');
        }

        $productoModel = new ProductoModel();
        $productoModel->recalcularTodos();

        Redirect::to('stock/maderas');
        exit;
    }

    public function eliminarmaderas($id = null): void {
        if (!puedeEliminar()) {
            Toast::new('No tenés permisos para eliminar registros.', 'danger');
            Redirect::to('stock/maderas');
            exit;
        }
        if (!$id) { Redirect::to('stock/maderas'); exit; }
        (new StockModel())->borrarMadera((int)$id);
        Toast::new('Madera eliminada del catálogo', 'success');
        Redirect::to('stock/maderas');
        exit;
    }

    public function insumos(): void {
        $model  = new StockModel();
        $buscar = trim($_GET['buscar'] ?? '');
        $this->render('insumos', [
            'insumos'       => $model->listarInsumos($buscar),
            'tiposMaterial' => $model->getTiposMaterial(),
            'tiposCorte'    => $model->getTiposCorte(),
            'buscar'        => $buscar,
        ]);
    }

    public function guardarinsumos(): void {
        $model = new StockModel();
        $id    = (int)($_POST['Id'] ?? 0);
        $d = [
            'Descripcion'      => trim($_POST['Descripcion']       ?? ''),
            'IdTipodeMaterial' => (int)($_POST['IdTipodeMaterial'] ?? 0),
            'IdTipodeCorte'    => (int)($_POST['IdTipodeCorte']    ?? 0),
            'PrecioUnitario'   => str_replace(',', '.', $_POST['PrecioUnitario'] ?? '0'),
        ];
        if (empty($d['Descripcion'])) {
            Toast::new('La descripción es obligatoria', 'warning');
            Redirect::to('stock/insumos');
            exit;
        }
        if ((float)$d['PrecioUnitario'] < 0) {
            Toast::new('El precio no puede ser negativo', 'warning');
            Redirect::to('stock/insumos');
            exit;
        }
        if ($id > 0) {
            $model->editarInsumo($id, $d);
            Toast::new('Insumo actualizado', 'success');
        } else {
            $model->crearInsumo($d);
            Toast::new('Insumo creado en el catálogo', 'success');
        }

        $productoModel = new ProductoModel();
        $productoModel->recalcularTodos();

        Redirect::to('stock/insumos');
        exit;
    }

    public function eliminarinsumos($id = null): void {
        if (!puedeEliminar()) {
            Toast::new('No tenés permisos para eliminar registros.', 'danger');
            Redirect::to('stock/insumos');
            exit;
        }
        if (!$id) { Redirect::to('stock/insumos'); exit; }
        (new StockModel())->borrarInsumo((int)$id);
        Toast::new('Insumo eliminado del catálogo', 'success');
        Redirect::to('stock/insumos');
        exit;
    }


    public function generarDiagnostico() {
        require_once TEMPLATES . 'includes' . DS . 'admin' . DS . 'auth_admin.php';

        $idUsuario = $_SESSION['usuario_id'] ?? null;

        $model     = new StockAnalisisModel();
        $resultado = $model->generarDiagnostico($idUsuario);

        if ($resultado['success']) {
            Toast::new('Diagnóstico generado correctamente.', 'success');
            Redirect::to('stock/verDiagnostico/' . $resultado['id']);
        } else {
            Toast::new('Error generando diagnóstico: ' . $resultado['error'], 'danger');
            Redirect::to('stock');
        }
    }

    public function verDiagnostico($id = null) {
        require_once TEMPLATES . 'includes' . DS . 'admin' . DS . 'auth_admin.php';

        if (!$id) {
            Redirect::to('stock/historialDiagnosticos');
        }

        $model       = new StockAnalisisModel();
        $diagnostico = $model->obtenerDiagnostico((int)$id);

        if (!$diagnostico) {
            Toast::new('Diagnóstico no encontrado.', 'warning');
            Redirect::to('stock');
        }

        $this->render('diagnostico', [
            'diagnostico' => $diagnostico,
            'title'       => 'Diagnóstico #' . $id,
        ]);
    }

    public function historialDiagnosticos() {
        require_once TEMPLATES . 'includes' . DS . 'admin' . DS . 'auth_admin.php';

        $model     = new StockAnalisisModel();
        $historial = $model->listarHistorial(30);

        $this->render('diagnosticos_historial', [
            'historial' => $historial,
            'title'     => 'Historial de Diagnósticos',
        ]);
    }



}