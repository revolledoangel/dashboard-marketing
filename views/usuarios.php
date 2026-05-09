<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestión de Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <div class="row">
                <!-- Formulario para crear usuario -->
                <div class="col-lg-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</h3>
                        </div>
                        <form id="formCrearUsuario">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nombreUsuario">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombre" placeholder="Ej: Juan Pérez" required>
                                </div>
                                <div class="form-group">
                                    <label for="emailUsuario">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="emailUsuario" name="email" placeholder="usuario@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="passwordUsuario">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="passwordUsuario" name="password" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Crear Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lista de usuarios -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users"></i> Usuarios Registrados</h3>
                        </div>
                        <div class="card-body">
                            <div id="listaUsuarios">
                                <p class="text-muted text-center">Cargando usuarios...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><i class="fas fa-user-edit"></i> Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarUsuario">
                <div class="modal-body">
                    <input type="hidden" id="editUsuarioId" name="id">
                    <div class="form-group">
                        <label for="editNombreUsuario">Nombre</label>
                        <input type="text" class="form-control" id="editNombreUsuario" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmailUsuario">Email</label>
                        <input type="email" class="form-control" id="editEmailUsuario" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editPasswordUsuario">Nueva Contraseña (dejar vacío para mantener la actual)</label>
                        <input type="password" class="form-control" id="editPasswordUsuario" name="password" placeholder="••••••••">
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
