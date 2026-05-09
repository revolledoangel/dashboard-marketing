<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Configuración - Clientes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
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
