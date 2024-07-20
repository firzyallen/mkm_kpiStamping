<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Core</div>
                <!-- Sidenav Link (Home)-->
                <a class="nav-link" href="{{url('/home')}}">
                    <div style="margin-left: -2px" class="nav-link-icon"><i class="fas fa-home"></i></div>
                    Home
                </a>
                <!-- Daily Report Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDailyReport" aria-expanded="false" aria-controls="collapseDailyReport">
                    <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Daily Report 
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDailyReport" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/daily-report/press') }}">Press</a>
                    </nav>
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/daily-report/welding') }}">Welding</a>
                    </nav>
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/daily-report/factory') }}">Factory B</a>
                    </nav>
                </div>
                <!-- Downtime Report Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDowntimeReport" aria-expanded="false" aria-controls="collapseDowntimeReport">
                    <div class="nav-link-icon"><i class="fas fa-clock"></i></div>
                    Downtime Report 
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDowntimeReport" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/downtime-report/press') }}">Press</a>
                    </nav>
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/downtime-report/welding') }}">Welding</a>
                    </nav>
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/downtime-report/factory') }}">Factory B</a>
                    </nav>
                </div>

                <!-- Sidenav Menu Heading (View)-->
                <div class="sidenav-menu-heading">View</div>
                <!-- Sidenav Accordion (View)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseView" aria-expanded="false" aria-controls="collapseView">
                    <div class="nav-link-icon"><i class="fas fa-table"></i></div>
                    View
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseView" data-bs-parent="#accordionSidenav">
                   <nav class="sidenav-menu-nested nav">
                       <a class="nav-link" href="{{url('/view/production-summary')}}">Production Summary</a>
                    </nav>
                    <nav class="sidenav-menu-nested nav">
                       <a class="nav-link" href="{{url('/view/downtime-summary')}}">Downtime Summary</a>
                    </nav>
                </div>
                <!-- Sidenav Menu Heading (Master)-->
                <div class="sidenav-menu-heading">Master</div>
                <!-- Press Master Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePressMaster" aria-expanded="false" aria-controls="collapsePressMaster">
                    <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Press
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePressMaster" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/press/shop') }}">Shop</a>
                        <a class="nav-link" href="{{ url('/press/model') }}">Model</a>
                    </nav>
                </div>
                <!-- Welding Master Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseWeldingMaster" aria-expanded="false" aria-controls="collapseWeldingMaster">
                    <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Welding
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseWeldingMaster" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/welding/shop') }}">Shop</a>
                        <a class="nav-link" href="{{ url('/welding/station') }}">Station</a>
                        <a class="nav-link" href="{{ url('/welding/model') }}">Model</a>
                    </nav>
                </div>
                <!-- Factory B Master Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFactoryBMaster" aria-expanded="false" aria-controls="collapseFactoryBMaster">
                    <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Factory B
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseFactoryBMaster" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/factoryb/shop') }}">Shop</a>
                        <a class="nav-link" href="{{ url('/factoryb/model') }}">Model</a>
                    </nav>
                </div>
                <!-- Downtime Master Section -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDowntimeMaster" aria-expanded="false" aria-controls="collapseDowntimeMaster">
                    <div class="nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                    Downtime
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDowntimeMaster" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ url('/downtime-master/press') }}">Press</a>
                        <a class="nav-link" href="{{ url('/downtime-master/welding') }}">Welding</a>
                        <a class="nav-link" href="{{ url('/downtime-master/factoryb') }}">Factory B</a>
                    </nav>
                </div>
                @if(\Auth::user()->role === 'IT')
                <!-- Sidenav Menu Heading (Configuration)-->
                <div class="sidenav-menu-heading">Configuration</div>
                <!-- Sidenav Accordion (Utilities)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseUtilities" aria-expanded="false" aria-controls="collapseUtilities">
                    <div class="nav-link-icon"><i data-feather="tool"></i></div>
                    Master Configuration
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUtilities" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="{{url('/dropdown')}}">Dropdown</a>
                        <a class="nav-link" href="{{url('/rule')}}">Rules</a>
                        <a class="nav-link" href="{{url('/user')}}">User</a>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </nav>
</div>
