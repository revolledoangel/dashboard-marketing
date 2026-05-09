// Main JavaScript - Marketing Dashboard

$(document).ready(function() {
    
    // Inicializar Dark Mode
    initDarkMode();
    
    // Cargar clientes al iniciar (esto tambiÃ©n cargarÃ¡ el cliente actual)
    cargarClientes();
    
    // Inicializar pÃ¡ginas especÃ­ficas
    inicializarPaginaActual();
    
    // Cambio de cliente en selector
    $('#clienteSelector').on('change', function() {
        const clienteId = $(this).val();
        
        if (clienteId) {
            localStorage.setItem('clienteActual', clienteId);
            
            // Detectar pÃ¡gina actual y actualizar segÃºn corresponda
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            
            if (page === 'embudos') {
                // Si estamos en embudos, actualizar lista de embudos
                verificarClienteSeleccionadoEmbudos();
            } else {
                // Para dashboard u otras pÃ¡ginas
                cargarDatosCliente(clienteId);
            }
        } else {
            localStorage.removeItem('clienteActual');
            mostrarMensajeNoCliente();
        }
    });
    
    // CONFIGURACIÃ“N DE CLIENTES
    
    // Submit crear cliente
    $('#formCrearCliente').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'api.php?action=crear',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente creado',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#formCrearCliente')[0].reset();
                    cargarListaClientes();
                    cargarClientes(); // Recargar selector (seleccionarÃ¡ automÃ¡ticamente si es el primero)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al crear el cliente'
                });
            }
        });
    });
    
    // Submit editar cliente
    $('#formEditarCliente').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'api.php?action=actualizar',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente actualizado',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#modalEditarCliente').modal('hide');
                    cargarListaClientes();
                    cargarClientes(); // Recargar selector
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            }
        });
    });
    
    // Cargar lista de clientes en configuraciÃ³n
    if ($('#listaClientes').length) {
        cargarListaClientes();
    }
    
    // CONFIGURACIÃ“N DE USUARIOS
    
    // Submit crear usuario
    $('#formCrearUsuario').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'api.php?action=usuario&sub=crear',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario creado',
                        text: 'Usuario creado exitosamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#formCrearUsuario')[0].reset();
                    cargarListaUsuarios();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al crear el usuario'
                });
            }
        });
    });
    
    // Submit editar usuario
    $('#formEditarUsuario').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'api.php?action=usuario&sub=actualizar',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario actualizado',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    $('#modalEditarUsuario').modal('hide');
                    cargarListaUsuarios();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            }
        });
    });
    
    // Cargar lista de usuarios
    if ($('#listaUsuarios').length) {
        cargarListaUsuarios();
    }
});

// FunciÃ³n para cargar clientes en el selector
function cargarClientes() {
    $.ajax({
        url: 'api.php?action=listar',
        method: 'GET',
        dataType: 'json',
        success: function(clientes) {
            const selector = $('#clienteSelector');
            let clienteActual = localStorage.getItem('clienteActual');
            
            selector.empty();
            selector.append('<option value="">Seleccionar cliente...</option>');
            
            if (clientes.length === 0) {
                mostrarMensajeNoCliente();
                return;
            }
            
            clientes.forEach(function(cliente) {
                selector.append(`<option value="${cliente.id}">${cliente.nombre}</option>`);
            });
            
            // Si no hay cliente guardado, seleccionar el primero automÃ¡ticamente
            if (!clienteActual && clientes.length > 0) {
                clienteActual = clientes[0].id;
                localStorage.setItem('clienteActual', clienteActual);
            }
            
            // Restaurar/establecer selecciÃ³n
            if (clienteActual) {
                selector.val(clienteActual);
                cargarDatosCliente(clienteActual);
            }
        }
    });
}

// FunciÃ³n para cargar datos del cliente
function cargarDatosCliente(clienteId) {
    $.ajax({
        url: 'api.php?action=obtener',
        method: 'GET',
        data: { id: clienteId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const cliente = response.data;
                
                // Actualizar nombre en navbar
                $('#clienteActualNombre').text(cliente.nombre);
            }
        }
    });
}

// FunciÃ³n para mostrar mensaje cuando no hay cliente
function mostrarMensajeNoCliente() {
    $('#clienteActualNombre').text('Sin cliente');
}

// FunciÃ³n para cargar lista de clientes en configuraciÃ³n
function cargarListaClientes() {
    $.ajax({
        url: 'api.php?action=listar',
        method: 'GET',
        dataType: 'json',
        success: function(clientes) {
            const contenedor = $('#listaClientes');
            
            if (clientes.length === 0) {
                contenedor.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay clientes registrados. Crea uno usando el formulario.
                    </div>
                `);
                return;
            }
            
            let html = '<div class="table-responsive"><table class="table table-hover">';
            html += '<thead><tr><th>ID</th><th>Nombre</th><th>Fecha Creación</th><th>Acciones</th></tr></thead>';
            html += '<tbody>';
            
            clientes.forEach(function(cliente) {
                html += `
                    <tr>
                        <td><code>${cliente.id}</code></td>
                        <td><strong>${cliente.nombre}</strong></td>
                        <td>${cliente.fecha_creacion}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editarCliente('${cliente.id}', '${cliente.nombre}')">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarCliente('${cliente.id}', '${cliente.nombre}')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            contenedor.html(html);
        },
        error: function(xhr, status, error) {
            const contenedor = $('#listaClientes');
            contenedor.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error al cargar los clientes. ${error}
                </div>
            `);
        }
    });
}

// FunciÃ³n para editar cliente
function editarCliente(id, nombre) {
    $('#editClienteId').val(id);
    $('#editNombreCliente').val(nombre);
    $('#modalEditarCliente').modal('show');
}

// Función para eliminar cliente
function eliminarCliente(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el cliente "${nombre}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api.php?action=eliminar',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        cargarListaClientes();
                        cargarClientes(); // Recargar selector (seleccionarÃ¡ el primero si hay mÃ¡s)
                        
                        // Si era el cliente actual, se limpiarÃ¡ y se seleccionarÃ¡ otro automÃ¡ticamente
                        const clienteActualStorage = localStorage.getItem('clienteActual');
                        if (clienteActualStorage === id) {
                            localStorage.removeItem('clienteActual');
                            // cargarClientes() ya se encargarÃ¡ de seleccionar el primero disponible
                        }
                    }
                }
            });
        }
    });
}

// FunciÃ³n para cargar lista de usuarios
function cargarListaUsuarios() {
    $.ajax({
        url: 'api.php?action=usuario&sub=listar',
        method: 'GET',
        dataType: 'json',
        success: function(usuarios) {
            const contenedor = $('#listaUsuarios');
            
            if (usuarios.length === 0) {
                contenedor.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay usuarios registrados.
                    </div>
                `);
                return;
            }
            
            let html = '<div class="table-responsive"><table class="table table-hover">';
            html += '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha Creación</th><th>Acciones</th></tr></thead>';
            html += '<tbody>';
            
            usuarios.forEach(function(usuario) {
                html += `
                    <tr>
                        <td><code>${usuario.id}</code></td>
                        <td><strong>${usuario.nombre}</strong></td>
                        <td>${usuario.email}</td>
                        <td>${usuario.fecha_creacion}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editarUsuario('${usuario.id}', '${usuario.nombre}', '${usuario.email}')">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarUsuario('${usuario.id}', '${usuario.nombre}')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            contenedor.html(html);
        }
    });
}

// FunciÃ³n para editar usuario
function editarUsuario(id, nombre, email) {
    $('#editUsuarioId').val(id);
    $('#editNombreUsuario').val(nombre);
    $('#editEmailUsuario').val(email);
    $('#editPasswordUsuario').val('');
    $('#modalEditarUsuario').modal('show');
}

// Función para eliminar usuario
function eliminarUsuario(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el usuario "${nombre}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api.php?action=usuario&sub=eliminar',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        cargarListaUsuarios();
                    }
                }
            });
        }
    });
}

// ===========================================
// FUNCIONES DE EMBUDOS
// ===========================================

// Submit crear embudo
$('#formCrearEmbudo').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    
    $.ajax({
        url: 'api.php?action=embudo&sub=crear',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Embudo creado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                $('#formCrearEmbudo')[0].reset();
                const clienteId = localStorage.getItem('clienteActual');
                if (clienteId) {
                    $('#clienteIdEmbudo').val(clienteId);
                    cargarEmbudosCliente(clienteId);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
});

// Submit editar embudo
$('#formEditarEmbudo').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    
    $.ajax({
        url: 'api.php?action=embudo&sub=actualizar',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Embudo actualizado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                $('#modalEditarEmbudo').modal('hide');
                const clienteId = localStorage.getItem('clienteActual');
                if (clienteId) {
                    cargarEmbudosCliente(clienteId);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        }
    });
});

// Inicializar funcionalidad basada en la página actual
function inicializarPaginaActual() {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    
    if (page === 'embudos') {
        inicializarPaginaEmbudos();
    } else if (page === 'metricas') {
        inicializarPaginaMetricas();
    }
}

// Inicializar página de embudos
function inicializarPaginaEmbudos() {
    verificarClienteSeleccionadoEmbudos();
}

// Inicializar página de métricas
function inicializarPaginaMetricas() {
    cargarEmbudosEnSelector();
    
    // Detectar si viene un embudo preseleccionado en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const embudoPreseleccionado = urlParams.get('embudo');
    
    // Evento cuando cambia el embudo seleccionado
    $('#selectorEmbudoMetricas').on('change', function() {
        const embudoId = $(this).val();
        if (embudoId) {
            const nombreEmbudo = $('#selectorEmbudoMetricas option:selected').text();
            const tokenEmbudo = $('#selectorEmbudoMetricas option:selected').data('token');
            
            // Actualizar la URL para mantener el estado al recargar
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('embudo', embudoId);
            window.history.replaceState({}, '', newUrl);
            
            // Mostrar botón de código GTM y configurarlo
            $('#botonCodigoGTMContainer').show();
            $('#btnVerCodigoGTM').off('click').on('click', function() {
                verCodigoGTM(embudoId, tokenEmbudo, nombreEmbudo);
            });
            
            cargarMetricasEmbudo(embudoId, nombreEmbudo);
        } else {
            // Si deselecciona, quitar el parámetro de la URL
            const newUrl = new URL(window.location);
            newUrl.searchParams.delete('embudo');
            window.history.replaceState({}, '', newUrl);
            
            $('#contenedorMetricas').hide();
            $('#mensajeSinEmbudo').show();
            $('#breadcrumbEmbudo').hide();
            $('#botonCodigoGTMContainer').hide();
        }
    });
    
    // Si hay embudo preseleccionado, esperamos a que se carguen los embudos
    if (embudoPreseleccionado) {
        // Esperar un momento para que se cargue el selector
        setTimeout(function() {
            $('#selectorEmbudoMetricas').val(embudoPreseleccionado).trigger('change');
        }, 500);
    }
}

// Cargar embudos en el selector de métricas
function cargarEmbudosEnSelector() {
    const clienteId = localStorage.getItem('clienteActual');
    
    if (!clienteId) {
        return;
    }
    
    $.ajax({
        url: 'api.php?action=embudo&sub=listar',
        method: 'GET',
        data: { cliente_id: clienteId },
        dataType: 'json',
        success: function(embudos) {
            const selector = $('#selectorEmbudoMetricas');
            selector.find('option:not(:first)').remove();
            
            embudos.forEach(function(embudo) {
                selector.append(`<option value="${embudo.id}" data-token="${embudo.token}">${embudo.nombre}</option>`);
            });
        }
    });
}

// Cargar métricas de un embudo específico
function cargarMetricasEmbudo(embudoId, nombreEmbudo) {
    console.log('🔍 Cargando métricas para embudo:', embudoId, nombreEmbudo);
    
    // Actualizar breadcrumb
    $('#breadcrumbEmbudo').text(nombreEmbudo).show();
    
    // Mostrar contenedor de métricas
    $('#mensajeSinEmbudo').hide();
    $('#contenedorMetricas').show();
    
    // Cargar eventos del embudo
    $.ajax({
        url: 'api.php?action=evento&sub=listar',
        method: 'GET',
        data: { embudo_id: embudoId },
        dataType: 'json',
        success: function(eventos) {
            console.log('📊 Eventos recibidos:', eventos);
            console.log('📊 Cantidad de eventos:', Array.isArray(eventos) ? eventos.length : Object.keys(eventos).length);
            actualizarEstadisticas(eventos);
            mostrarTablaEventos(eventos);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando eventos:', status, error);
            console.error('❌ Respuesta:', xhr.responseText);
            // Si aún no existe el endpoint, mostrar datos vacíos
            $('#totalEventos').text('0');
            $('#totalVisitas').text('0');
            $('#totalAcciones').text('0');
            $('#conversion').text('0%');
            $('#tablaEventos').html('<p class="text-muted text-center">No hay eventos registrados aún</p>');
        }
    });
}

// Actualizar estadísticas
function actualizarEstadisticas(eventos) {
    const total = eventos.length;
    const visitas = eventos.filter(e => e.tipo === 'visita').length;
    const acciones = eventos.filter(e => e.tipo === 'evento').length;
    const conversion = visitas > 0 ? ((acciones / visitas) * 100).toFixed(1) : 0;
    
    $('#totalEventos').text(total);
    $('#totalVisitas').text(visitas);
    $('#totalAcciones').text(acciones);
    $('#conversion').text(conversion + '%');
}

// Mostrar tabla de eventos
function mostrarTablaEventos(eventos) {
    const contenedor = $('#tablaEventos');
    
    if (eventos.length === 0) {
        contenedor.html('<p class="text-muted text-center">No hay eventos registrados aún</p>');
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-hover table-sm">';
    html += '<thead><tr><th>Fecha/Hora</th><th>Tipo</th><th>Nombre</th><th>URL</th><th>UTM Campaign</th><th>IP</th></tr></thead>';
    html += '<tbody>';
    
    eventos.slice().reverse().forEach(function(evento) {
        const tipoBadge = evento.tipo === 'visita' ? 'badge-success' : 'badge-warning';
        const utmCampaign = evento.utm_campaign || '-';
        const urlCorta = evento.url ? evento.url.substring(0, 40) + '...' : '-';
        
        html += `
            <tr>
                <td><small>${evento.timestamp}</small></td>
                <td><span class="badge ${tipoBadge}">${evento.tipo}</span></td>
                <td><strong>${evento.nombre}</strong></td>
                <td><small title="${evento.url}">${urlCorta}</small></td>
                <td><code>${utmCampaign}</code></td>
                <td><small>${evento.ip}</small></td>
            </tr>
        `;
    });
    
    html += '</tbody></table></div>';
    contenedor.html(html);
}

function verificarClienteSeleccionadoEmbudos() {
    const clienteId = localStorage.getItem('clienteActual');
    
    if (!clienteId) {
        $('#alertaCliente').show();
        $('#contenidoEmbudos').hide();
    } else {
        $('#alertaCliente').hide();
        $('#contenidoEmbudos').show();
        $('#clienteIdEmbudo').val(clienteId);
        cargarEmbudosCliente(clienteId);
    }
}

function cargarEmbudosCliente(clienteId) {
    $.ajax({
        url: 'api.php?action=embudo&sub=listar',
        method: 'GET',
        data: { cliente_id: clienteId },
        dataType: 'json',
        success: function(embudos) {
            const contenedor = $('#listaEmbudos');
            
            if (embudos.length === 0) {
                contenedor.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay embudos creados para este cliente.
                    </div>
                `);
                return;
            }
            
            let html = '<div class="row">';
            
            embudos.forEach(function(embudo) {
                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-filter"></i> ${embudo.nombre}
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">${embudo.descripcion || 'Sin descripción'}</p>
                                <div class="mb-2">
                                    <small class="text-muted">Token:</small><br>
                                    <code style="font-size: 10px;">${embudo.token}</code>
                                </div>
                                <small class="text-muted">Creado: ${embudo.fecha_creacion}</small>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-success" onclick="verCodigoGTM('${embudo.id}', '${embudo.token}', '${embudo.nombre}')">
                                    <i class="fas fa-code"></i> Código GTM
                                </button>
                                <button class="btn btn-sm btn-primary" onclick="irAMetricas('${embudo.id}', '${embudo.nombre}')">
                                    <i class="fas fa-chart-line"></i> Métricas
                                </button>
                                <button class="btn btn-sm btn-info" onclick="editarEmbudo('${embudo.id}', '${embudo.nombre}', '${embudo.descripcion}')">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger mt-2" onclick="eliminarEmbudo('${embudo.id}', '${embudo.nombre}')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            contenedor.html(html);
        }
    });
}

// Editar embudo
function editarEmbudo(id, nombre, descripcion) {
    $('#editEmbudoId').val(id);
    $('#editNombreEmbudo').val(nombre);
    $('#editDescripcionEmbudo').val(descripcion || '');
    $('#modalEditarEmbudo').modal('show');
}

// Eliminar embudo
function eliminarEmbudo(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará el embudo "${nombre}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api.php?action=embudo&sub=eliminar',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        const clienteId = localStorage.getItem('clienteActual');
                        if (clienteId) {
                            cargarEmbudosCliente(clienteId);
                        }
                    }
                }
            });
        }
    });
}

// Ir a página de métricas con embudo preseleccionado
function irAMetricas(embudoId, nombreEmbudo) {
    window.location.href = `index.php?page=metricas&embudo=${embudoId}`;
}

// ============================================
// CÓDIGO GTM
// ============================================

// Mostrar código GTM para un embudo
function verCodigoGTM(embudoId, token, nombreEmbudo) {
    const trackUrl = window.APP_CONFIG.baseUrl + '/track.php';
    
    const codigo = `<script>
// ============================================
// TRACKING - Embudo: ${nombreEmbudo}
// ============================================

// VISITA - Trackea cuánta gente llega a esta página
// Borra este bloque completo si no necesitas trackear visitas
fetch('${trackUrl}', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    token: '${token}',
    tipo: 'visita',
    nombre: 'nombre_pagina', // 👈 CAMBIA ESTO: home, producto, checkout, gracias, etc.
    url: window.location.href
  })
})
.then(function(response) { return response.json(); })
.then(function(data) { console.log('✅ Visita registrada:', data); })
.catch(function(error) { console.error('❌ Error tracking visita:', error); });

// EVENTO - Trackea acciones específicas del usuario
// Borra este bloque completo si no necesitas trackear eventos
fetch('${trackUrl}', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    token: '${token}',
    tipo: 'evento',
    nombre: 'nombre_evento', // 👈 CAMBIA ESTO: click_boton, submit_form, add_cart, compra, etc.
    url: window.location.href
  })
})
.then(function(response) { return response.json(); })
.then(function(data) { console.log('✅ Evento registrado:', data); })
.catch(function(error) { console.error('❌ Error tracking evento:', error); });
<\/script>`;
    
    // Mostrar en modal
    $('#gtmEmbudoNombre').text(nombreEmbudo);
    $('#codigoGTM').text(codigo);
    $('#modalCodigoGTM').modal('show');
}

// Copiar código GTM al portapapeles
function copiarCodigoGTM() {
    const codigo = $('#codigoGTM').text();
    
    // Usar API moderna del Clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(codigo)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copiado',
                    text: 'Código copiado al portapapeles',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(err => {
                console.error('Error al copiar:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo copiar el código. Inténtalo manualmente.',
                    timer: 2000
                });
            });
    } else {
        // Fallback para navegadores antiguos
        const temp = $('<textarea>');
        $('body').append(temp);
        temp.val(codigo).select();
        try {
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Copiado',
                text: 'Código copiado al portapapeles',
                timer: 1500,
                showConfirmButton: false
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo copiar el código',
                timer: 2000
            });
        }
        temp.remove();
    }
}


// ============================================
// DARK MODE
// ============================================

function initDarkMode() {
    // Recuperar preferencia de localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    
    // Aplicar modo oscuro si estÃ¡ activado
    if (isDarkMode) {
        enableDarkMode();
    }
    
    // Event listener para el toggle
    const toggle = document.getElementById('darkModeToggle');
    if (toggle) {
        toggle.addEventListener('click', toggleDarkMode);
    }
}

function toggleDarkMode() {
    const body = document.body;
    const isDarkMode = body.classList.contains('dark-mode');
    
    if (isDarkMode) {
        disableDarkMode();
    } else {
        enableDarkMode();
    }
}

function enableDarkMode() {
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const navbar = document.querySelector('.main-header');
    const sidebar = document.querySelector('.main-sidebar');
    
    // AÃ±adir clase dark-mode
    body.classList.add('dark-mode');
    
    // Cambiar navbar a dark
    if (navbar) {
        navbar.classList.remove('navbar-white', 'navbar-light');
        navbar.classList.add('navbar-dark');
    }
    
    // Cambiar sidebar
    if (sidebar) {
        sidebar.classList.remove('sidebar-dark-primary');
        sidebar.classList.add('sidebar-dark-primary');
    }
    
    // Actualizar toggle
    if (toggle) {
        toggle.classList.add('active');
    }
    
    // Cambiar icono
    if (icon) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'true');
    
    // Actualizar grÃ¡ficos de Chart.js si existen
    updateChartsDarkMode(true);
    
    // Re-inicializar Mermaid con tema dark
    reinitMermaid(true);

}

function disableDarkMode() {
    const body = document.body;
    const toggle = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const navbar = document.querySelector('.main-header');
    const sidebar = document.querySelector('.main-sidebar');
    
    // Remover clase dark-mode
    body.classList.remove('dark-mode');
    
    // Cambiar navbar a light
    if (navbar) {
        navbar.classList.remove('navbar-dark');
        navbar.classList.add('navbar-white', 'navbar-light');
    }
    
    // Sidebar mantener dark
    if (sidebar) {
        sidebar.classList.remove('sidebar-light-primary');
        sidebar.classList.add('sidebar-dark-primary');
    }
    
    // Actualizar toggle
    if (toggle) {
        toggle.classList.remove('active');
    }
    
    // Cambiar icono
    if (icon) {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'false');
    
    // Actualizar grÃ¡ficos de Chart.js si existen
    updateChartsDarkMode(false);
    
    // Re-inicializar Mermaid con tema light
    reinitMermaid(false);

}

function updateChartsDarkMode(isDark) {
    // Actualizar colores de Chart.js segÃºn el modo
    if (typeof Chart !== 'undefined') {
        const textColor = isDark ? '#e5e7eb' : '#666';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;
        
        // Re-renderizar grÃ¡ficos existentes
        if (typeof chartComparativo !== 'undefined' && chartComparativo) {
            chartComparativo.options.plugins.legend.labels.color = textColor;
            chartComparativo.options.scales.x.ticks.color = textColor;
            chartComparativo.options.scales.y.ticks.color = textColor;
            chartComparativo.options.scales.x.grid.color = gridColor;
            chartComparativo.options.scales.y.grid.color = gridColor;
            chartComparativo.update();
        }
        
        if (typeof chartEmbudo !== 'undefined' && chartEmbudo) {
            chartEmbudo.options.plugins.legend.labels.color = textColor;
            chartEmbudo.options.scales.x.ticks.color = textColor;
            chartEmbudo.options.scales.x.grid.color = gridColor;
            chartEmbudo.update();
        }
        
        if (typeof chartEventosPorDia !== 'undefined' && chartEventosPorDia) {
            chartEventosPorDia.options.scales.x.ticks.color = textColor;
            chartEventosPorDia.options.scales.y.ticks.color = textColor;
            chartEventosPorDia.options.scales.x.grid.color = gridColor;
            chartEventosPorDia.options.scales.y.grid.color = gridColor;
            chartEventosPorDia.update();
        }
    }
}

function reinitMermaid(isDark) {
    // Re-inicializar Mermaid cuando cambie el tema
    if (typeof mermaid !== 'undefined') {
        mermaid.initialize({ 
            startOnLoad: false, 
            theme: isDark ? 'dark' : 'default',
            flowchart: { 
                curve: 'basis',
                padding: 20
            },
            themeVariables: isDark ? {
                primaryColor: '#4f46e5',
                primaryTextColor: '#e5e7eb',
                lineColor: '#6366f1',
                textColor: '#e5e7eb'
            } : {}
        });
        
        // Re-renderizar diagrama si existe
        const diagramaFlujo = document.getElementById('diagramaFlujo');
        if (diagramaFlujo && diagramaFlujo.innerHTML.includes('mermaid')) {
            // Actualizar diagrama de flujo si estÃ¡ visible
            if (typeof actualizarDiagramaFlujo === 'function') {
                actualizarDiagramaFlujo();
            }
        }
    }
}
