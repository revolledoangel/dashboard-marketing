    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #1a1d24 0%, #111827 100%);">
        <!-- Selector de Cliente en lugar del Brand -->
        <div class="p-3" style="position: relative;">
            <select id="clienteSelector" class="form-control" style="padding-right: 30px;">
                <option value="">Seleccionar cliente...</option>
            </select>
            <i class="fas fa-chevron-down" style="position: absolute; right: 25px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #6c757d;"></i>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="index.php?page=dashboard" class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=embudos" class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'embudos') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-filter"></i>
                            <p>Embudos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=metricas" class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'metricas') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Métricas</p>
                        </a>
                    </li>
                    
                    <!-- Sección de Configuración -->
                    <li class="nav-header">CONFIGURACIÓN</li>
                    <li class="nav-item">
                        <a href="index.php?page=configuracion" class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'configuracion') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=usuarios" class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'usuarios') ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="api.php?action=usuario&sub=logout" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Cerrar Sesión</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
