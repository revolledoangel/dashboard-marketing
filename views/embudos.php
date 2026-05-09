<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-filter"></i> Gestión de Embudos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Embudos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alerta de cliente requerido -->
            <div id="alertaCliente" class="alert alert-warning" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i> Debes seleccionar un cliente para gestionar embudos.
            </div>

            <div id="contenidoEmbudos" style="display: none;">
                <div class="row">
                    <!-- Formulario para crear embudo -->
                    <div class="col-lg-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-plus"></i> Crear Nuevo Embudo</h3>
                            </div>
                            <form id="formCrearEmbudo">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nombreEmbudo">Nombre del Embudo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombreEmbudo" name="nombre" placeholder="Ej: Círculo de la Armonía Familiar" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcionEmbudo">Descripción</label>
                                        <textarea class="form-control" id="descripcionEmbudo" name="descripcion" rows="3" placeholder="Describe el objetivo de este embudo..."></textarea>
                                    </div>
                                    <input type="hidden" id="clienteIdEmbudo" name="cliente_id">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Crear Embudo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de embudos -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list"></i> Embudos del Cliente</h3>
                            </div>
                            <div class="card-body">
                                <div id="listaEmbudos">
                                    <p class="text-muted text-center">Cargando embudos...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal para editar embudo -->
<div class="modal fade" id="modalEditarEmbudo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Embudo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarEmbudo">
                <div class="modal-body">
                    <input type="hidden" id="editEmbudoId" name="id">
                    <div class="form-group">
                        <label for="editNombreEmbudo">Nombre del Embudo</label>
                        <input type="text" class="form-control" id="editNombreEmbudo" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescripcionEmbudo">Descripción</label>
                        <textarea class="form-control" id="editDescripcionEmbudo" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
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
