<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') | AI Business Assistant</title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fa-solid fa-robot text-primary me-2"></i> AI Assistant</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Main Menu</p>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
                </li>
                
                <p>Social</p>
                <li>
                    <a href="#socialSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('inbox*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fa-solid fa-inbox"></i> Social Inbox
                    </a>
                    <ul class="collapse {{ request()->is('inbox*') ? 'show' : '' }} list-unstyled" id="socialSubmenu">
                        <li class="{{ request()->routeIs('inbox.messenger') ? 'active' : '' }}"><a href="{{ route('inbox.messenger') }}">Messenger</a></li>
                        <li class="{{ request()->routeIs('inbox.comments') ? 'active' : '' }}"><a href="{{ route('inbox.comments') }}">Comments</a></li>
                        <li class="{{ request()->routeIs('inbox.conversations') ? 'active' : '' }}"><a href="{{ route('inbox.conversations') }}">Conversations</a></li>
                    </ul>
                </li>
                
                <p>Automation</p>
                <li>
                    <a href="#autoSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('automation*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fa-solid fa-bolt"></i> Automation
                    </a>
                    <ul class="collapse {{ request()->is('automation*') ? 'show' : '' }} list-unstyled" id="autoSubmenu">
                        <li class="{{ request()->routeIs('automation.auto-reply') ? 'active' : '' }}"><a href="{{ route('automation.auto-reply') }}">Auto Reply</a></li>
                        <li class="{{ request()->routeIs('automation.auto-comment') ? 'active' : '' }}"><a href="{{ route('automation.auto-comment') }}">Auto Comment</a></li>
                        <li class="{{ request()->routeIs('automation.keyword-rules') ? 'active' : '' }}"><a href="{{ route('automation.keyword-rules') }}">Keyword Rules</a></li>
                        <li class="{{ request()->routeIs('automation.ai-rules') ? 'active' : '' }}"><a href="{{ route('automation.ai-rules') }}">AI Rules</a></li>
                        <li class="{{ request()->routeIs('automation.spam') ? 'active' : '' }}"><a href="{{ route('automation.spam') }}">Spam Protection</a></li>
                    </ul>
                </li>
                
                <p>AI Engine</p>
                <li>
                    <a href="#aiSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('ai*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fa-solid fa-brain"></i> AI Assistant
                    </a>
                    <ul class="collapse {{ request()->is('ai*') ? 'show' : '' }} list-unstyled" id="aiSubmenu">
                        <li class="{{ request()->routeIs('ai.chat') ? 'active' : '' }}"><a href="{{ route('ai.chat') }}">AI Chat</a></li>
                        <li class="{{ request()->routeIs('ai.knowledge') ? 'active' : '' }}"><a href="{{ route('ai.knowledge') }}">Business Knowledge</a></li>
                        <li class="{{ request()->routeIs('ai.faq') ? 'active' : '' }}"><a href="{{ route('ai.faq') }}">FAQ</a></li>
                        <li class="{{ request()->routeIs('ai.products') ? 'active' : '' }}"><a href="{{ route('ai.products') }}">Products & Services</a></li>
                        <li class="{{ request()->routeIs('ai.settings') ? 'active' : '' }}"><a href="{{ route('ai.settings') }}">AI Settings</a></li>
                    </ul>
                </li>
                
                <p>CRM</p>
                <li>
                    <a href="#leadsSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('leads*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fa-solid fa-users"></i> Leads
                    </a>
                    <ul class="collapse {{ request()->is('leads*') ? 'show' : '' }} list-unstyled" id="leadsSubmenu">
                        <li class="{{ request()->routeIs('leads.all') ? 'active' : '' }}"><a href="{{ route('leads.all') }}">All Leads</a></li>
                        <li class="{{ request()->routeIs('leads.new') ? 'active' : '' }}"><a href="{{ route('leads.new') }}">New Leads</a></li>
                        <li class="{{ request()->routeIs('leads.hot') ? 'active' : '' }}"><a href="{{ route('leads.hot') }}">Hot Leads</a></li>
                        <li class="{{ request()->routeIs('leads.follow-up') ? 'active' : '' }}"><a href="{{ route('leads.follow-up') }}">Follow Up</a></li>
                        <li class="{{ request()->routeIs('leads.pipeline') ? 'active' : '' }}"><a href="{{ route('leads.pipeline') }}">Lead Pipeline</a></li>
                    </ul>
                </li>
                
                <p>System</p>
                <li>
                    <a href="#settingsSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('settings*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fa-solid fa-gear"></i> Settings
                    </a>
                    <ul class="collapse {{ request()->is('settings*') ? 'show' : '' }} list-unstyled" id="settingsSubmenu">
                        <li class="{{ request()->routeIs('settings.business') ? 'active' : '' }}"><a href="{{ route('settings.business') }}">Business Profile</a></li>
                        <li class="{{ request()->routeIs('settings.facebook') ? 'active' : '' }}"><a href="{{ route('settings.facebook') }}">Facebook</a></li>
                        <li class="{{ request()->routeIs('settings.instagram') ? 'active' : '' }}"><a href="{{ route('settings.instagram') }}">Instagram</a></li>
                        <li class="{{ request()->routeIs('settings.general') ? 'active' : '' }}"><a href="{{ route('settings.general') }}">General Settings</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Content Wrapper -->
        <div id="content">
            
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="navbar-left">
                    <button type="button" id="sidebarCollapse" class="sidebar-collapse-btn">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="page-title">@yield('title', 'Dashboard')</h4>
                </div>
                
                <div class="navbar-right">
                    <div class="search-box d-none d-md-block">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" placeholder="Search anything...">
                    </div>
                    
                    <a href="#" class="nav-icon">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge bg-danger">3</span>
                    </a>
                    
                    <div class="ai-status">
                        <div class="status-dot"></div>
                        <span>AI Online</span>
                    </div>
                    
                    <div class="dropdown">
                        <div class="profile-dropdown dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" alt="Admin">
                            <span class="d-none d-md-inline fw-medium text-dark">Admin</span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Page Content -->
            <div class="main-container">
                @yield('content')
            </div>
            
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            
            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
