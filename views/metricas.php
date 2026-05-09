<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-chart-bar"></i> Métricas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Métricas</li>
                        <li class="breadcrumb-item active" id="breadcrumbEmbudo" style="display:none;"></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Selector de Embudo -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label for="selectorEmbudoMetricas"><i class="fas fa-filter"></i> Seleccionar Embudo</label>
                            <select id="selectorEmbudoMetricas" class="form-control">
                                <option value="">Selecciona un embudo...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" id="botonCodigoGTMContainer" style="display:none;">
                        <div class="card-body">
                            <label><i class="fas fa-code"></i> Acciones Rápidas</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-success btn-block btn-sm" id="btnVerCodigoGTM">
                                        <i class="fas fa-code"></i> Ver Código GTM
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-block btn-sm" id="btnNuevoProducto">
                                        <i class="fas fa-plus"></i> Producto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros de Fecha -->
            <div class="row mb-3" id="filtrosFecha" style="display:none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <label><i class="fas fa-calendar-alt"></i> Período</label>
                                    <div class="btn-group btn-group-sm d-block" role="group">
                                        <button type="button" class="btn btn-outline-primary filtro-fecha-rapido" data-dias="0">Hoy</button>
                                        <button type="button" class="btn btn-outline-primary filtro-fecha-rapido" data-dias="7">Últimos 7 días</button>
                                        <button type="button" class="btn btn-outline-primary filtro-fecha-rapido active" data-dias="30">Últimos 30 días</button>
                                        <button type="button" class="btn btn-outline-primary filtro-fecha-rapido" data-dias="90">Últimos 90 días</button>
                                        <button type="button" class="btn btn-outline-primary" id="btnPersonalizarFecha">
                                            <i class="fas fa-calendar"></i> Personalizar
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="btnAplicarFiltros">
                                            <i class="fas fa-sync-alt"></i> Actualizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Rango personalizado (oculto por defecto) -->
                            <div class="row mt-3" id="rangoPersonalizado" style="display:none;">
                                <div class="col-md-5">
                                    <label for="fechaInicio">Fecha Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaInicio">
                                </div>
                                <div class="col-md-5">
                                    <label for="fechaFin">Fecha Fin</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaFin">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-sm btn-block" id="btnAplicarPersonalizado">
                                        <i class="fas fa-check"></i> OK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenedor de métricas -->
            <div id="contenedorMetricas" style="display:none;">
                
                <!-- Cards de páginas visitadas -->
                <div class="row" id="contenedorPaginasVisitadas">
                    <!-- Cards se generarán dinámicamente aquí -->
                </div>

            </div>

            <!-- Mensaje cuando no hay embudo seleccionado -->
            <div id="mensajeSinEmbudo">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-filter fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Selecciona un embudo</h4>
                                <p class="text-muted">Para ver las métricas, selecciona un embudo del listado superior</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal para ver código GTM -->
<div class="modal fade" id="modalCodigoGTM" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-code"></i> Código para Google Tag Manager</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Instrucciones:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Copia el código completo de abajo</li>
                        <li>En GTM, crea un nuevo Tag tipo "HTML Personalizado"</li>
                        <li>Pega el código</li>
                        <li>Personaliza el <code>nombre</code> del evento según tu necesidad:
                            <ul>
                                <li><strong>Visitas:</strong> Para trackear tráfico (ej: "home", "producto", "checkout")</li>
                                <li><strong>Eventos:</strong> Para trackear acciones (ej: "click_boton", "submit_form", "add_cart")</li>
                            </ul>
                        </li>
                        <li>Borra la línea del tipo que no necesites (visita o evento)</li>
                    </ol>
                </div>
                
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Embudo:</strong> <span id="gtmEmbudoNombre" class="badge badge-primary"></span>
                    </div>
                    <button class="btn btn-sm btn-success" onclick="copiarCodigoGTM()">
                        <i class="fas fa-copy"></i> Copiar Código
                    </button>
                </div>
                
                <pre class="bg-dark text-light p-3" style="border-radius: 5px; max-height: 400px; overflow-y: auto; overflow-x: auto; white-space: pre;"><code id="codigoGTM"></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Crear Producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart"></i> Crear Producto
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>¿Qué es un producto?</strong>
                    <p class="mb-0 mt-2">
                        Un producto representa el final del embudo: la compra. Al crearlo se generará un webhook 
                        único que configurarás en Hotmart para recibir notificaciones de compras aprobadas.
                    </p>
                </div>
                
                <form id="formNuevoProducto">
                    <div class="form-group">
                        <label for="nombreProducto">
                            <i class="fas fa-tag"></i> Nombre del Producto <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nombreProducto" name="nombre" 
                               placeholder="Ej: Curso de Marketing Digital" required>
                        <small class="form-text text-muted">
                            Este nombre te ayudará a identificar el producto en el funnel.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarProducto">
                    <i class="fas fa-save"></i> Crear Producto
                </button>
            </div>
        </div>
    </div>
</div>
