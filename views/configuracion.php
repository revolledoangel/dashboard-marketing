<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-cog"></i> Configuración</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Configuración</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Tabs de configuración -->
            <ul class="nav nav-tabs mb-3" id="tabsConfiguracion" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-sistema" data-toggle="tab" href="#contenidoSistema" role="tab">
                        <i class="fas fa-server"></i> Sistema
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-clientes" data-toggle="tab" href="#contenidoClientes" role="tab">
                        <i class="fas fa-building"></i> Clientes
                    </a>
                </li>
            </ul>
            
            <!-- Contenido de tabs -->
            <div class="tab-content" id="contenidoTabs">
                
                <!-- TAB: Configuración del Sistema -->
                <div class="tab-pane fade show active" id="contenidoSistema" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-clock"></i> Zona Horaria</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        La zona horaria determina cómo se muestran las fechas y horas en todo el panel.
                                        Las fechas se guardan en UTC pero se muestran en la zona configurada.
                                    </p>
                                    
                                    <div class="form-group">
                                        <label for="timezoneSelector">
                                            <i class="fas fa-globe"></i> Seleccionar Zona Horaria
                                        </label>
                                        <select class="form-control" id="timezoneSelector">
                                            <option value="">Cargando...</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            Zona actual: <strong id="timezoneActual">Cargando...</strong>
                                        </small>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-calendar-alt"></i> 
                                        <strong>Hora actual del sistema:</strong><br>
                                        <span id="horaActual" style="font-size: 1.2rem; font-family: monospace;">--:--:--</span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-info" id="btnGuardarTimezone">
                                        <i class="fas fa-save"></i> Guardar Configuración
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información UTC</h3>
                                </div>
                                <div class="card-body">
                                    <p>
                                        <strong>¿Por qué UTC?</strong><br>
                                        UTC (Tiempo Universal Coordinado) es el estándar mundial para medir el tiempo.
                                        Todas las fechas se guardan en UTC en la base de datos y luego se convierten
                                        a tu zona horaria al mostrarse.
                                    </p>
                                    
                                    <p class="mb-0">
                                        <strong>Ventajas:</strong>
                                    </p>
                                    <ul>
                                        <li>Consistencia entre diferentes usuarios</li>
                                        <li>No hay problemas con cambios de horario de verano</li>
                                        <li>Facilita la comparación de eventos globales</li>
                                        <li>Múltiples usuarios en diferentes zonas ven sus datos correctamente</li>
                                    </ul>
                                    
                                    <div class="alert alert-secondary mb-0">
                                        <i class="fas fa-clock"></i> 
                                        <strong>Hora UTC actual:</strong><br>
                                        <span id="horaUTC" style="font-size: 1.1rem; font-family: monospace;">--:--:--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- TAB: Clientes -->
                <div class="tab-pane fade" id="contenidoClientes" role="tabpanel">
            
            <div class="row">
                <!-- Formulario para crear cliente -->
                <div class="col-lg-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus"></i> Crear Nuevo Cliente</h3>
                        </div>
                        <form id="formCrearCliente">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombreCliente">Nombre de la Empresa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombreCliente" name="nombre" placeholder="Ej: Empresa ABC" required>
                                    <small class="form-text text-muted">El ID se generará automáticamente</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Crear Cliente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de clientes -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list"></i> Clientes Registrados</h3>
                        </div>
                        <div class="card-body">
                            <div id="listaClientes">
                                <p class="text-muted text-center">Cargando clientes...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div><!-- Cierre tab-content -->

        </div>
    </section>
</div>

<!-- Modal para editar cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarCliente">
                <div class="modal-body">
                    <input type="hidden" id="editClienteId" name="id">
                    <div class="form-group">
                        <label for="editNombreCliente">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="editNombreCliente" name="nombre" required>
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
