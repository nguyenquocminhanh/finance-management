<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ url('/dashboard') }}" class="waves-effect">
                        <i class="ri-home-2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Manage Members</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('member.all') }}">All Member</a></li>
                    </ul>
                </li>

                <li class="menu-title">Income - Outcome</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-money-dollar-circle-fill"></i>
                        <span>Manage Incomes</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('income.all') }}">All Income</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-bank-card-fill"></i>
                        <span>Manage Outcomes</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('outcome.all') }}">All Outcome</a></li>
                    </ul>
                </li>

                <li class="menu-title">Financial Data</li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-file-paper-2-fill"></i>
                        <span>Monthly Report</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('report.all') }}">All Report</a></li>
                    </ul>
                </li>

                <li class="menu-title">Report</li> 

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-printer-fill"></i>
                        <span>Print Reports</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('monthly.report') }}">Monthly Report</a></li>
                        <li><a href="{{ route('period.report.list') }}">All Period Report</a></li>
                        <li><a href="{{ route('member.wise.report') }}">Member Wise Report</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>