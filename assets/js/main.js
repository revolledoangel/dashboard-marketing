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
    
    // Inicializar fechas por defecto (últimos 30 días)
    const hoy = new Date();
    const hace30Dias = new Date();
    hace30Dias.setDate(hoy.getDate() - 30);
    
    $('#fechaInicio').val(hace30Dias.toISOString().split('T')[0]);
    $('#fechaFin').val(hoy.toISOString().split('T')[0]);
    
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
            
            // Mostrar filtros de fecha
            $('#filtrosFecha').show();
            
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
            $('#filtrosFecha').hide();
        }
    });
    
    // Filtros rápidos de fecha
    $('.filtro-fecha-rapido').on('click', function() {
        $('.filtro-fecha-rapido').removeClass('active');
        $(this).addClass('active');
        
        const dias = parseInt($(this).data('dias'));
        const fechaFin = new Date();
        const fechaInicio = new Date();
        
        if (dias === 0) {
            // Hoy
            fechaInicio.setHours(0, 0, 0, 0);
        } else {
            fechaInicio.setDate(fechaFin.getDate() - dias);
        }
        
        $('#fechaInicio').val(fechaInicio.toISOString().split('T')[0]);
        $('#fechaFin').val(fechaFin.toISOString().split('T')[0]);
        
        // Ocultar y recargar
        $('#rangoPersonalizado').hide();
        aplicarFiltrosFecha();
    });
    
    // Botón personalizar
    $('#btnPersonalizarFecha').on('click', function() {
        $('.filtro-fecha-rapido').removeClass('active');
        $(this).addClass('active');
        $('#rangoPersonalizado').slideToggle();
    });
    
    // Botón aplicar personalizado
    $('#btnAplicarPersonalizado').on('click', function() {
        aplicarFiltrosFecha();
    });
    
    // Botón actualizar
    $('#btnAplicarFiltros').on('click', function() {
        aplicarFiltrosFecha();
    });
    
    // Si hay embudo preseleccionado, esperamos a que se carguen los embudos
    if (embudoPreseleccionado) {
        // Esperar un momento para que se cargue el selector
        setTimeout(function() {
            $('#selectorEmbudoMetricas').val(embudoPreseleccionado).trigger('change');
        }, 500);
    }
}

// Aplicar filtros de fecha y recargar métricas
function aplicarFiltrosFecha() {
    const embudoId = $('#selectorEmbudoMetricas').val();
    const nombreEmbudo = $('#selectorEmbudoMetricas option:selected').text();
    
    if (embudoId) {
        cargarMetricasEmbudo(embudoId, nombreEmbudo);
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
    
    // Obtener fechas de los filtros
    const fechaInicio = $('#fechaInicio').val();
    const fechaFin = $('#fechaFin').val();
    
    console.log('📅 Rango de fechas:', fechaInicio, 'a', fechaFin);
    
    // Actualizar breadcrumb
    $('#breadcrumbEmbudo').text(nombreEmbudo).show();
    
    // Mostrar contenedor de métricas
    $('#mensajeSinEmbudo').hide();
    $('#contenedorMetricas').show();
    
    // Cargar eventos del embudo con filtro de fechas
    $.ajax({
        url: 'api.php?action=evento&sub=listar',
        method: 'GET',
        data: { 
            embudo_id: embudoId,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        },
        dataType: 'json',
        success: function(eventos) {
            console.log('📊 Eventos recibidos:', eventos);
            console.log('📊 Cantidad de eventos:', Array.isArray(eventos) ? eventos.length : Object.keys(eventos).length);
            mostrarPaginasVisitadas(eventos);
            
            // Cargar productos (se mostrarán al final del funnel)
            cargarProductos(embudoId);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error cargando eventos:', status, error);
            console.error('❌ Respuesta:', xhr.responseText);
            $('#contenedorPaginasVisitadas').html('<div class="col-12"><p class="text-muted text-center">No hay eventos registrados aún</p></div>');
        }
    });
}

// Mostrar páginas visitadas agrupadas con eventos anidados
function mostrarPaginasVisitadas(eventos) {
    const contenedor = $('#contenedorPaginasVisitadas');
    
    // Separar visitas y eventos
    const visitas = eventos.filter(e => e.tipo === 'visita');
    const eventosAccion = eventos.filter(e => e.tipo === 'evento');
    
    if (visitas.length === 0 && eventosAccion.length === 0) {
        contenedor.html('<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> No hay datos registrados aún</div></div>');
        return;
    }
    
    // Agrupar visitas por nombre de página
    const paginasMap = {};
    visitas.forEach(function(visita) {
        const nombre = visita.nombre || 'Página sin nombre';
        if (!paginasMap[nombre]) {
            paginasMap[nombre] = {
                tipo: 'visita',
                nombre: nombre,
                cantidad: 0,
                ultimaVisita: visita.timestamp,
                eventos: []
            };
        }
        paginasMap[nombre].cantidad++;
        if (visita.timestamp > paginasMap[nombre].ultimaVisita) {
            paginasMap[nombre].ultimaVisita = visita.timestamp;
        }
    });
    
    // Agrupar eventos por nombre
    const eventosMap = {};
    eventosAccion.forEach(function(evento) {
        const nombre = evento.nombre || 'Evento sin nombre';
        if (!eventosMap[nombre]) {
            eventosMap[nombre] = {
                tipo: 'evento',
                nombre: nombre,
                cantidad: 0
            };
        }
        eventosMap[nombre].cantidad++;
    });
    
    let paginas = Object.values(paginasMap);
    let eventosLibres = Object.values(eventosMap);
    
    // Obtener orden guardado del servidor
    const embudoId = $('#selectorEmbudoMetricas').val();
    $.ajax({
        url: 'api.php?action=orden_funnel&sub=obtener',
        method: 'GET',
        data: { embudo_id: embudoId },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                console.log('📥 Estructura guardada recibida:', response.data);
                // Aplicar estructura guardada
                const estructura = aplicarEstructuraGuardada(paginas, eventosLibres, response.data);
                console.log('🎨 Visitas renderizadas en orden:', estructura.visitas.map(v => v.nombre));
                renderizarEstructuraFunnel(estructura.visitas, estructura.eventosLibres, contenedor);
            } else {
                console.log('⚠️ Sin orden guardado, usando orden por cantidad');
                // Sin orden guardado, ordenar por cantidad
                paginas.sort((a, b) => b.cantidad - a.cantidad);
                renderizarEstructuraFunnel(paginas, eventosLibres, contenedor);
            }
        },
        error: function() {
            // Si falla, ordenar por cantidad
            paginas.sort((a, b) => b.cantidad - a.cantidad);
            renderizarEstructuraFunnel(paginas, eventosLibres, contenedor);
        }
    });
}

// Aplicar estructura guardada (visitas con eventos anidados)
function aplicarEstructuraGuardada(paginas, eventos, estructura) {
    const paginasMap = {};
    const eventosMap = {};
    
    console.log('🔧 Aplicando estructura guardada...');
    console.log('   Páginas disponibles:', paginas.map(p => p.nombre));
    console.log('   Orden guardado:', estructura.map(e => e.nombre));
    
    // Crear mapas
    paginas.forEach(p => paginasMap[p.nombre] = p);
    eventos.forEach(e => eventosMap[e.nombre] = e);
    
    const visitasOrdenadas = [];
    const eventosLibres = [];
    
    // Procesar estructura guardada
    estructura.forEach((item, index) => {
        if (item.tipo === 'visita' && paginasMap[item.nombre]) {
            const pagina = paginasMap[item.nombre];
            pagina.eventos = [];
            
            console.log(`   ✅ Posición ${index + 1}: ${item.nombre}`);
            
            // Agregar eventos hijos
            if (item.eventos && item.eventos.length > 0) {
                item.eventos.forEach(ev => {
                    if (eventosMap[ev.nombre]) {
                        pagina.eventos.push(eventosMap[ev.nombre]);
                        delete eventosMap[ev.nombre]; // Ya asignado
                    }
                });
            }
            
            visitasOrdenadas.push(pagina);
            delete paginasMap[item.nombre]; // Ya procesado
        }
    });
    
    // Agregar páginas nuevas no guardadas
    Object.values(paginasMap).forEach(p => {
        p.eventos = [];
        console.log(`   ➕ Nueva página (no guardada): ${p.nombre}`);
        visitasOrdenadas.push(p);
    });
    
    // Eventos no asignados quedan libres
    Object.values(eventosMap).forEach(e => eventosLibres.push(e));
    
    console.log('🎯 Orden final:', visitasOrdenadas.map(v => v.nombre));
    
    return { visitas: visitasOrdenadas, eventosLibres: eventosLibres };
}

// Renderizar estructura completa del funnel
function renderizarEstructuraFunnel(visitas, eventosLibres, contenedor) {
    const colores = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
    
    let html = '';
    
    // Eventos libres (sin asignar a ninguna página)
    if (eventosLibres.length > 0) {
        html += '<div class="col-12 mb-2"><h5 class="text-muted" style="font-size: 1rem; margin-bottom: 8px;"><i class="fas fa-bolt"></i> Eventos libres</h5></div>';
        html += '<div class="row sortable-eventos-libres" style="margin-left: 0; margin-right: 0; margin-bottom: 10px;">';
        eventosLibres.forEach(function(evento) {
            html += renderizarCardEvento(evento, null);
        });
        html += '</div>';
        html += '<div class="col-12 mb-2"><hr style="margin: 10px 0;"></div>';
    }
    
    // Visitas con sus eventos
    const primeraVisitaCantidad = visitas.length > 0 ? visitas[0].cantidad : 0;
    
    visitas.forEach(function(pagina, index) {
        const color = colores[index % colores.length];
        const esPrimera = index === 0;
        html += renderizarCardPagina(pagina, color, esPrimera, primeraVisitaCantidad);
    });
    
    contenedor.html(html);
    
    // Inicializar drag & drop
    inicializarDragDropAnidado();
}

// Renderizar card de página (visita)
function renderizarCardPagina(pagina, color, esPrimera, primeraVisitaCantidad) {
    // Calcular tasa de conversión respecto a la primera visita
    let conversionHTML = '';
    if (!esPrimera && primeraVisitaCantidad > 0) {
        const tasaConversion = ((pagina.cantidad / primeraVisitaCantidad) * 100).toFixed(1);
        conversionHTML = `<small style="font-size: 0.7rem; opacity: 0.85; display: block; margin-top: 3px;">
            <i class="fas fa-chart-line"></i> ${tasaConversion}% vs 1ra
        </small>`;
    }
    
    let html = `
        <div class="col-12 mb-2 sortable-visita" data-tipo="visita" data-nombre="${pagina.nombre}">
            <div class="small-box ${color}" style="cursor: move; position: relative; padding: 10px 15px; display: flex; align-items: center; min-height: auto;">
                <div class="inner" style="flex: 0 0 250px; margin: 0; padding-right: 15px;">
                    <h3 style="font-size: 2rem; margin: 0 0 5px 0;">${pagina.cantidad}</h3>
                    <p style="margin: 0; font-size: 0.9rem;"><i class="fas fa-grip-vertical"></i> ${pagina.nombre}</p>
                    <small style="font-size: 0.65rem; opacity: 0.75;">Última: ${pagina.ultimaVisita}</small>
                    ${conversionHTML}
                </div>
                
                <!-- Drop zone para eventos (vertical a la derecha) -->
                <div class="eventos-container sortable-eventos" data-pagina="${pagina.nombre}" style="flex: 1; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; padding-left: 15px; border-left: 2px dashed rgba(255,255,255,0.3); min-height: 60px;">
    `;
    
    // Eventos anidados
    if (pagina.eventos && pagina.eventos.length > 0) {
        pagina.eventos.forEach(evento => {
            html += renderizarCardEventoAnidado(evento, pagina.nombre, pagina.cantidad);
        });
    } else {
        html += `<small style="opacity: 0.5; font-size: 0.75rem;"><i class="fas fa-info-circle"></i> Arrastra eventos aquí</small>`;
    }
    
    html += `
                </div>
            </div>
        </div>
    `;
    
    return html;
}

// Renderizar card de evento libre (pequeño)
function renderizarCardEvento(evento, paginaPadre) {
    return `
        <div class="col-md-3 col-sm-4 mb-2 sortable-evento" data-tipo="evento" data-nombre="${evento.nombre}">
            <div class="card card-outline card-warning" style="cursor: grab; margin-bottom: 0;">
                <div class="card-body p-2" style="padding: 6px 8px !important;">
                    <i class="fas fa-bolt text-warning" style="font-size: 0.75rem;"></i>
                    <strong style="font-size: 0.8rem;">${evento.nombre}</strong>
                    <span class="badge badge-warning float-right" style="font-size: 0.7rem; padding: 2px 6px;">${evento.cantidad}</span>
                </div>
            </div>
        </div>
    `;
}

// Renderizar card de evento anidado (dentro de página)
function renderizarCardEventoAnidado(evento, paginaNombre, totalVisitas) {
    const tasaConversion = ((evento.cantidad / totalVisitas) * 100).toFixed(1);
    
    return `
        <div class="sortable-evento-anidado" data-tipo="evento" data-nombre="${evento.nombre}" data-pagina="${paginaNombre}" data-cantidad="${evento.cantidad}" style="cursor: grab; flex: 0 0 auto;">
            <div class="card card-warning" style="margin-bottom: 0; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,193,7,0.5); width: 160px;">
                <div class="card-body p-2" style="color: #fff; padding: 6px 8px !important;">
                    <div style="margin-bottom: 3px;">
                        <i class="fas fa-bolt" style="font-size: 0.75rem;"></i>
                        <strong style="font-size: 0.8rem;">${evento.nombre}</strong>
                    </div>
                    <div>
                        <span class="badge badge-warning" style="font-size: 0.7rem; padding: 2px 6px;">${evento.cantidad}</span>
                        <small style="font-size: 0.65rem; opacity: 0.85; margin-left: 4px;">${tasaConversion}%</small>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Actualizar estadísticas

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
            // Actualizar diagrama de flujo si está visible
            if (typeof actualizarDiagramaFlujo === 'function') {
                actualizarDiagramaFlujo();
            }
        }
    }
}

// =============================================
// DRAG & DROP PARA ORDENAR FUNNEL
// =============================================

// Inicializar drag & drop anidado para funnel
function inicializarDragDropAnidado() {
    const contenedor = document.getElementById('contenedorPaginasVisitadas');
    
    if (!contenedor) {
        console.log('⚠️ Contenedor de páginas no encontrado');
        return;
    }
    
    // Sortable para visitas (páginas principales)
    Sortable.create(contenedor, {
        group: {
            name: 'visitas',
            pull: false,
            put: false
        },
        animation: 150,
        filter: '.sortable-eventos-libres',
        handle: '.small-box',
        draggable: '.sortable-visita',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onMove: function(evt) {
            // Prevenir que las visitas caigan en drop zones de eventos
            return !evt.to.classList.contains('sortable-eventos');
        },
        onEnd: function(evt) {
            console.log('🔄 Visita movida de posición', evt.oldIndex, '→', evt.newIndex);
            guardarEstructuraFunnel();
        }
    });
    
    // Sortable para contenedor de eventos libres
    const contenedorEventosLibres = document.querySelector('.sortable-eventos-libres');
    if (contenedorEventosLibres) {
        Sortable.create(contenedorEventosLibres, {
            group: {
                name: 'eventos',
                pull: true,  // Permitir mover (no clonar)
                put: true    // Permitir que eventos regresen aquí
            },
            animation: 150,
            draggable: '.sortable-evento, .sortable-evento-anidado',
            ghostClass: 'sortable-ghost-evento',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            sort: false,
            onAdd: function(evt) {
                // Cuando un evento anidado regresa a la zona libre
                const eventoElement = evt.item;
                if (eventoElement.classList.contains('sortable-evento-anidado')) {
                    convertirEventoAnidadoALibre(eventoElement);
                    guardarEstructuraFunnel();
                }
            }
        });
    }
    
    // Sortable para drop zones de eventos dentro de páginas
    const dropZones = document.querySelectorAll('.sortable-eventos');
    dropZones.forEach(zona => {
        Sortable.create(zona, {
            group: {
                name: 'eventos',
                pull: true,
                put: function(to, from, dragEl) {
                    // Solo aceptar eventos, NO visitas
                    return dragEl.classList.contains('sortable-evento') || 
                           dragEl.classList.contains('sortable-evento-anidado');
                }
            },
            animation: 150,
            draggable: '.sortable-evento-anidado,.sortable-evento',
            ghostClass: 'sortable-ghost-evento',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onMove: function(evt) {
                // Solo permitir mover si es un evento
                return evt.dragged.classList.contains('sortable-evento') || 
                       evt.dragged.classList.contains('sortable-evento-anidado');
            },
            onAdd: function(evt) {
                // Cuando se agrega un evento a una página
                const eventoElement = evt.item;
                const paginaNombre = zona.getAttribute('data-pagina');
                
                console.log('🎯 onAdd disparado:', eventoElement.getAttribute('data-nombre'), '→', paginaNombre);
                
                // Validar que sea un evento
                if (!eventoElement.classList.contains('sortable-evento') && 
                    !eventoElement.classList.contains('sortable-evento-anidado')) {
                    console.error('❌ Solo se pueden arrastrar eventos aquí');
                    evt.item.remove();
                    return;
                }
                
                // Si es un evento libre que se convierte en anidado
                if (eventoElement.classList.contains('sortable-evento')) {
                    convertirEventoLibreAAnidado(eventoElement, paginaNombre, zona);
                } else if (eventoElement.classList.contains('sortable-evento-anidado')) {
                    // Es un evento que ya estaba anidado y se movió a otra visita
                    recalcularConversion(eventoElement, paginaNombre);
                }
                
                guardarEstructuraFunnel();
            },
            onRemove: function(evt) {
                // Cuando se remueve un evento de esta zona
                const zona = evt.from;
                
                // Si no quedan eventos, mostrar el mensaje placeholder
                const eventosRestantes = zona.querySelectorAll('.sortable-evento-anidado');
                if (eventosRestantes.length === 0 && !zona.querySelector('small')) {
                    zona.innerHTML = '<small style="opacity: 0.5; font-size: 0.75rem;"><i class="fas fa-info-circle"></i> Arrastra eventos aquí</small>';
                }
                
                guardarEstructuraFunnel();
            },
            onUpdate: function(evt) {
                guardarEstructuraFunnel();
            }
        });
    });
    
    console.log('✅ Drag & Drop anidado inicializado');
}

// Convertir evento libre en evento anidado con tasa de conversión
function convertirEventoLibreAAnidado(eventoElement, paginaNombre, zona) {
    const nombreEvento = eventoElement.getAttribute('data-nombre');
    const cantidadElement = eventoElement.querySelector('.badge');
    const cantidad = cantidadElement ? parseInt(cantidadElement.textContent) : 0;
    
    console.log('🔄 Convirtiendo evento libre:', nombreEvento, 'a', paginaNombre);
    
    // Obtener total de visitas de la página
    const paginaCard = zona.closest('.sortable-visita');
    const totalVisitasElement = paginaCard ? paginaCard.querySelector('h3') : null;
    const totalVisitas = totalVisitasElement ? parseInt(totalVisitasElement.textContent) : 1;
    
    console.log('   Total visitas de', paginaNombre, ':', totalVisitas);
    console.log('   Eventos:', cantidad);
    
    // Calcular tasa de conversión
    const tasaConversion = ((cantidad / totalVisitas) * 100).toFixed(1);
    
    console.log('   Tasa de conversión:', tasaConversion + '%');
    
    // Reemplazar HTML del elemento con formato horizontal compacto
    eventoElement.className = 'sortable-evento-anidado';
    eventoElement.setAttribute('data-tipo', 'evento');
    eventoElement.setAttribute('data-nombre', nombreEvento);
    eventoElement.setAttribute('data-pagina', paginaNombre);
    eventoElement.setAttribute('data-cantidad', cantidad);
    eventoElement.setAttribute('style', 'cursor: grab; flex: 0 0 auto;');
    eventoElement.innerHTML = `
        <div class="card card-warning" style="margin-bottom: 0; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,193,7,0.5); width: 160px;">
            <div class="card-body p-2" style="color: #fff; padding: 6px 8px !important;">
                <div style="margin-bottom: 3px;">
                    <i class="fas fa-bolt" style="font-size: 0.75rem;"></i>
                    <strong style="font-size: 0.8rem;">${nombreEvento}</strong>
                </div>
                <div>
                    <span class="badge badge-warning" style="font-size: 0.7rem; padding: 2px 6px;">${cantidad}</span>
                    <small style="font-size: 0.65rem; opacity: 0.85; margin-left: 4px;">${tasaConversion}%</small>
                </div>
            </div>
        </div>
    `;
    
    // Eliminar el mensaje de "Arrastra eventos aquí" si existe
    const mensajeVacio = zona.querySelector('small');
    if (mensajeVacio && mensajeVacio.textContent.includes('Arrastra')) {
        mensajeVacio.remove();
    }
    
    console.log('✅ Evento convertido a anidado');
}

// Recalcular tasa de conversión de un evento anidado
function recalcularConversion(eventoElement, nuevaPaginaNombre) {
    const cantidad = parseInt(eventoElement.getAttribute('data-cantidad')) || 0;
    
    console.log('📊 Recalculando conversión para', eventoElement.getAttribute('data-nombre'), 'en', nuevaPaginaNombre);
    
    // Obtener total de visitas de la nueva página
    const zona = eventoElement.closest('.sortable-eventos');
    const paginaCard = zona ? zona.closest('.sortable-visita') : null;
    const totalVisitasElement = paginaCard ? paginaCard.querySelector('h3') : null;
    const totalVisitas = totalVisitasElement ? parseInt(totalVisitasElement.textContent) : 1;
    
    // Calcular nueva tasa de conversión
    const tasaConversion = ((cantidad / totalVisitas) * 100).toFixed(1);
    
    console.log('   Nueva tasa de conversión:', tasaConversion + '%');
    
    // Actualizar el HTML solo del small de conversión (nuevo formato compacto)
    const smallElement = eventoElement.querySelector('small');
    if (smallElement) {
        smallElement.textContent = tasaConversion + '%';
    }
    
    // Actualizar atributo data-pagina
    eventoElement.setAttribute('data-pagina', nuevaPaginaNombre);
}

// Convertir evento anidado de vuelta a evento libre
function convertirEventoAnidadoALibre(eventoElement) {
    const nombreEvento = eventoElement.getAttribute('data-nombre');
    const cantidad = parseInt(eventoElement.getAttribute('data-cantidad')) || 0;
    
    console.log('🔙 Convirtiendo evento anidado a libre:', nombreEvento);
    
    // Cambiar clases y estructura a formato libre
    eventoElement.className = 'col-md-3 col-sm-4 mb-2 sortable-evento';
    eventoElement.removeAttribute('data-pagina');
    eventoElement.removeAttribute('data-cantidad');
    eventoElement.setAttribute('data-tipo', 'evento');
    eventoElement.setAttribute('style', '');
    
    eventoElement.innerHTML = `
        <div class="card card-outline card-warning" style="cursor: grab; margin-bottom: 0;">
            <div class="card-body p-2" style="padding: 6px 8px !important;">
                <i class="fas fa-bolt text-warning" style="font-size: 0.75rem;"></i>
                <strong style="font-size: 0.8rem;">${nombreEvento}</strong>
                <span class="badge badge-warning float-right" style="font-size: 0.7rem; padding: 2px 6px;">${cantidad}</span>
            </div>
        </div>
    `;
    
    console.log('✅ Evento convertido a libre');
}

// Nueva función para guardar estructura anidada
function guardarEstructuraFunnel() {
    const embudoId = $('#selectorEmbudoMetricas').val();
    
    if (!embudoId) {
        console.error('❌ No hay embudo seleccionado');
        return;
    }
    
    // Construir estructura con visitas y eventos anidados
    const estructura = [];
    const contenedorPrincipal = document.getElementById('contenedorPaginasVisitadas');
    const visitas = contenedorPrincipal.querySelectorAll('.sortable-visita');
    
    console.log('🔍 Total visitas encontradas:', visitas.length);
    
    visitas.forEach((visita, index) => {
        const nombrePagina = visita.getAttribute('data-nombre');
        const dropZone = visita.querySelector('.sortable-eventos');
        
        console.log(`📍 Visita #${index + 1}: ${nombrePagina}`);
        
        const item = {
            tipo: 'visita',
            nombre: nombrePagina,
            eventos: []
        };
        
        // Obtener eventos anidados
        if (dropZone) {
            const eventosAnidados = dropZone.querySelectorAll('.sortable-evento-anidado');
            eventosAnidados.forEach(evento => {
                const nombreEvento = evento.getAttribute('data-nombre');
                console.log(`  └─ Evento: ${nombreEvento}`);
                item.eventos.push({
                    tipo: 'evento',
                    nombre: nombreEvento
                });
            });
        }
        
        estructura.push(item);
    });
    
    console.log('💾 Guardando estructura:', estructura);
    
    // Enviar al servidor
    $.ajax({
        url: 'api.php?action=orden_funnel&sub=guardar',
        method: 'POST',
        data: {
            embudo_id: embudoId,
            estructura: JSON.stringify(estructura)
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log('✅ Estructura guardada correctamente');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Orden guardado',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                console.error('❌ Error guardando orden:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error AJAX:', error);
        }
    });
}

// =============================================
// CONFIGURACIÓN - TIMEZONE
// =============================================

// Al cargar la página de configuración
$(document).ready(function() {
    if (window.location.href.includes('page=configuracion')) {
        cargarConfiguracionTimezone();
        iniciarRelojTiempoReal();
    }
});

// Cargar configuración de timezone
function cargarConfiguracionTimezone() {
    // Cargar timezone actual
    $.ajax({
        url: 'api.php?action=configuracion&sub=obtener&clave=timezone',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const timezoneActual = response.data.valor;
                $('#timezoneActual').text(timezoneActual);
                cargarListaTimezones(timezoneActual);
            }
        },
        error: function() {
            console.error('Error cargando timezone');
            cargarListaTimezones('Europe/Madrid');
        }
    });
}

// Cargar lista de timezones disponibles
function cargarListaTimezones(timezoneSeleccionado) {
    const timezones = {
        'Europe/Madrid': 'Europa/Madrid (UTC+1/+2)',
        'America/New_York': 'América/Nueva York (UTC-5/-4)',
        'America/Los_Angeles': 'América/Los Ángeles (UTC-8/-7)',
        'America/Chicago': 'América/Chicago (UTC-6/-5)',
        'America/Denver': 'América/Denver (UTC-7/-6)',
        'America/Mexico_City': 'América/Ciudad de México (UTC-6/-5)',
        'America/Bogota': 'América/Bogotá (UTC-5)',
        'America/Lima': 'América/Lima (UTC-5)',
        'America/Santiago': 'América/Santiago (UTC-3/-4)',
        'America/Argentina/Buenos_Aires': 'América/Buenos Aires (UTC-3)',
        'Europe/London': 'Europa/Londres (UTC+0/+1)',
        'Europe/Paris': 'Europa/París (UTC+1/+2)',
        'Europe/Berlin': 'Europa/Berlín (UTC+1/+2)',
        'Europe/Rome': 'Europa/Roma (UTC+1/+2)',
        'Asia/Dubai': 'Asia/Dubái (UTC+4)',
        'Asia/Tokyo': 'Asia/Tokio (UTC+9)',
        'Asia/Shanghai': 'Asia/Shanghái (UTC+8)',
        'Australia/Sydney': 'Oceanía/Sídney (UTC+10/+11)',
        'UTC': 'UTC (Tiempo Universal Coordinado)'
    };
    
    let html = '';
    for (let tz in timezones) {
        const selected = tz === timezoneSeleccionado ? 'selected' : '';
        html += '<option value="' + tz + '" ' + selected + '>' + timezones[tz] + '</option>';
    }
    
    $('#timezoneSelector').html(html);
}

// Guardar timezone
$('#btnGuardarTimezone').on('click', function() {
    const timezone = $('#timezoneSelector').val();
    
    if (!timezone) {
        Swal.fire({
            icon: 'warning',
            title: 'Zona horaria requerida',
            text: 'Selecciona una zona horaria'
        });
        return;
    }
    
    $.ajax({
        url: 'api.php?action=configuracion&sub=guardar',
        method: 'POST',
        data: {
            clave: 'timezone',
            valor: timezone,
            descripcion: 'Zona horaria para mostrar fechas en el panel'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#timezoneActual').text(timezone);
                Swal.fire({
                    icon: 'success',
                    title: 'Configuración guardada',
                    text: 'La zona horaria se ha actualizado correctamente. Recarga la página para ver los cambios.',
                    confirmButtonText: 'Recargar ahora'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'No se pudo guardar la configuración'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al comunicarse con el servidor'
            });
        }
    });
});

// Iniciar reloj en tiempo real
function iniciarRelojTiempoReal() {
    actualizarRelojes();
    setInterval(actualizarRelojes, 1000);
}

function actualizarRelojes() {
    const ahora = new Date();
    
    // Hora UTC
    const horaUTC = ahora.toISOString().substring(11, 19);
    $('#horaUTC').text(horaUTC + ' UTC');
    
    // Hora local del navegador (aprox. a la zona configurada)
    const horaLocal = ahora.toLocaleTimeString('es-ES', { hour12: false });
    $('#horaActual').text(horaLocal);
}

// ========================================
// FUNCIONES DE PRODUCTOS
// ========================================

// Abrir modal de nuevo producto
$(document).on('click', '#btnNuevoProducto', function() {
    const embudoId = $('#selectorEmbudoMetricas').val();
    if (!embudoId) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona un embudo',
            text: 'Primero debes seleccionar un embudo'
        });
        return;
    }
    
    // Limpiar form y abrir modal
    $('#formNuevoProducto')[0].reset();
    $('#modalNuevoProducto').modal('show');
});

// Guardar nuevo producto
$(document).on('click', '#btnGuardarProducto', function() {
    const embudoId = $('#selectorEmbudoMetricas').val();
    const nombre = $('#nombreProducto').val().trim();
    
    if (!nombre) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo requerido',
            text: 'Ingresa el nombre del producto'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Creando producto...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: 'api.php?action=producto&sub=crear',
        method: 'POST',
        data: {
            embudo_id: embudoId,
            nombre: nombre
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalNuevoProducto').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Producto creado',
                    text: 'El producto se creó correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Recargar productos
                cargarProductos(embudoId);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error || 'No se pudo crear el producto'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al comunicarse con el servidor'
            });
        }
    });
});

// Cargar productos de un embudo
function cargarProductos(embudoId) {
    const fechaInicio = $('#fechaInicio').val();
    const fechaFin = $('#fechaFin').val();
    
    $.ajax({
        url: 'api.php?action=producto&sub=listar',
        method: 'GET',
        data: { 
            embudo_id: embudoId,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarProductos(response.data);
            }
        },
        error: function() {
            console.error('Error cargando productos');
        }
    });
}

// Mostrar productos como cards
function mostrarProductos(productos) {
    const contenedor = $('#contenedorPaginasVisitadas');
    
    if (!productos || productos.length === 0) {
        return; // No hay productos, no mostrar nada
    }
    
    // Agregar productos al final del funnel
    productos.forEach(function(producto) {
        const webhookUrl = window.location.origin + '/api.php?action=producto&sub=webhook&token=' + producto.webhook_token;
        const stats = producto.stats || { total_ventas: 0, conversiones_por_pagina: [] };
        
        // Generar HTML de conversiones por página
        let conversionesHtml = '';
        if (stats.conversiones_por_pagina && stats.conversiones_por_pagina.length > 0) {
            stats.conversiones_por_pagina.forEach(conv => {
                conversionesHtml += `
                    <div style="margin-right: 20px; font-size: 12px;">
                        <strong style="color: #155724;">${conv.porcentaje}%</strong> 
                        <span style="color: #155724;">de ${conv.pagina}</span>
                    </div>
                `;
            });
        } else {
            conversionesHtml = '<div style="font-size: 12px; color: #155724;">Sin conversiones aún</div>';
        }
        
        const card = `
            <div class="col-12 mb-2 producto-card" data-producto-id="${producto.id}">
                <div class="card border-success" style="border-width: 2px;">
                    <div class="card-body p-3" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px;">
                            <!-- Icono y nombre del producto -->
                            <div style="display: flex; align-items: center; gap: 15px; flex: 0 0 250px;">
                                <div style="font-size: 36px; color: #28a745;">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1" style="color: #155724; font-weight: 600;">
                                        ${producto.nombre}
                                    </h5>
                                    <small style="color: #155724;">
                                        <i class="fas fa-box-open"></i> Producto / Compra
                                    </small>
                                    <div style="margin-top: 8px;">
                                        <span style="background: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-dollar-sign"></i> ${stats.total_ventas} compras
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Conversiones por página -->
                            <div style="flex: 1; min-width: 0;">
                                <label style="font-size: 11px; color: #155724; margin-bottom: 5px; display: block;">
                                    <i class="fas fa-percentage"></i> Conversiones:
                                </label>
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    ${conversionesHtml}
                                </div>
                            </div>
                            
                            <!-- Webhook URL -->
                            <div style="flex: 1; min-width: 250px;">
                                <label style="font-size: 11px; color: #155724; margin-bottom: 3px;">
                                    <i class="fas fa-link"></i> Webhook URL (Configurar en Hotmart):
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" value="${webhookUrl}" 
                                           readonly style="font-family: monospace; font-size: 11px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-success btn-sm" onclick="copiarWebhook('${webhookUrl}', '${producto.nombre}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        contenedor.append(card);
    });
}

// Copiar webhook al portapapeles
window.copiarWebhook = function(url, nombreProducto) {
    navigator.clipboard.writeText(url).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'URL copiada',
            text: 'Webhook de "' + nombreProducto + '" copiado al portapapeles',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }).catch(function() {
        // Fallback para navegadores antiguos
        const input = document.createElement('input');
        input.value = url;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        
        Swal.fire({
            icon: 'success',
            title: 'URL copiada',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
};
