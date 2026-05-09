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
                            <label><i class="fas fa-code"></i> Código de Integración</label>
                            <div>
                                <button class="btn btn-success btn-block" id="btnVerCodigoGTM">
                                    <i class="fas fa-code"></i> Ver Código GTM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenedor de métricas -->
            <div id="contenedorMetricas" style="display:none;">
                
                <!-- Tarjetas de resumen -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="totalEventos">0</h3>
                                <p>Total Eventos</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-signal"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="totalVisitas">0</h3>
                                <p>Visitas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="totalAcciones">0</h3>
                                <p>Acciones/Eventos</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-mouse-pointer"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 id="conversion">0%</h3>
                                <p>Tasa de Conversión</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de eventos -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list"></i> Eventos Capturados</h3>
                            </div>
                            <div class="card-body">
                                <div id="tablaEventos"></div>
                            </div>
                        </div>
                    </div>
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
