<div class="pb-3 sidebar pe-4">
    <nav class="navbar bg-light navbar-light">
        @php
            $competitionOpen = Route::is('branch.getCompetitions') || Route::is('branch.competitions.videos') || Route::is('branch.competitions.appeals');
        @endphp
        <a href="{{ url('/') }}" class="mx-4 mb-3 navbar-brand">
            <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
        </a>
        <div class="mb-4 d-flex align-items-center ms-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ Auth::user()->image }}" alt=""
                    style="width: 40px; height: 40px;">
                <div
                    class="bottom-0 p-1 border border-2 border-white bg-success rounded-circle position-absolute end-0">
                </div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ Auth::user()->name }}</h6>
                <span>Admin</span>
            </div>
        </div>
        <div class="navbar-nav w-100 sidebar-small">
            <a href="{{ url('/') }}" class="nav-item nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>

            {{-- <a href="{{ route('users.index') }}" class="nav-item nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                <i class="fas fa-user-friends me-2"></i>Users
            </a>

            <a href="{{ route('coaches.index') }}" class="nav-item nav-link">
                <i class="fas fa-code-branch me-2"></i>Branches
            </a> --}}

            <a href="{{ route('branch.getCoaches') }}"
                class="nav-item nav-link {{ Route::is('coaches.*') ? 'active' : '' }}">
                <i class="fas fa-running me-2"></i>Coaches
            </a>

            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ Route::is('organisation-types.*') || Route::is('organisations.*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                    <i class="fas fa-sitemap me-2"></i>Organisations
                </a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('organisation-types.index') }}" class="nav-item nav-link {{ Route::is('organisation-types.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group me-2"></i>Org. Types
                    </a>
                    <a href="{{ route('organisations.index') }}" class="nav-item nav-link {{ Route::is('organisations.*') ? 'active' : '' }}">
                        <i class="fas fa-building me-2"></i>Organisations
                    </a>
                </div>
            </div> --}}

            {{-- <a href="{{ route('medical-assessment-questions.index') }}" class="nav-item nav-link {{ Route::is('medical-assessment-questions.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical me-2"></i>Medical Assessment
            </a>

            <a href="{{ route('plan-questions.index') }}" class="nav-item nav-link {{ Route::is('plan-questions.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle me-2"></i>Plan Questions
            </a> --}}

            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ Route::is('exercises.*') || Route::is('exercise-categories.*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                    <i class="fas fa-dumbbell me-2"></i>Exercise
                </a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('exercises.index') }}" class="nav-item nav-link {{ Route::is('exercises.*') ? 'active' : '' }}">
                        <i class="fas fa-dumbbell me-2"></i>Exercises
                    </a>
                    <a href="{{ route('exercise-categories.index') }}" class="nav-item nav-link {{ Route::is('exercise-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags me-2"></i>Categories
                    </a>
                </div>
            </div> --}}

            <div class="sidebar-section">
                <button type="button"
                    class="nav-item nav-link sidebar-parent-link {{ $competitionOpen ? 'active' : '' }}"
                    data-bs-toggle="collapse"
                    data-bs-target="#branchCompetitions"
                    aria-expanded="{{ $competitionOpen ? 'true' : 'false' }}"
                    aria-controls="branchCompetitions">
                    <span class="sidebar-parent-label">
                        <i class="fas fa-trophy me-2"></i>Competitions
                    </span>
                    <i class="fas fa-chevron-down sidebar-parent-chevron"></i>
                </button>
                <div id="branchCompetitions" class="sidebar-submenu collapse {{ $competitionOpen ? 'show' : '' }}">
                    <a href="{{ route('branch.getCompetitions') }}"
                        class="nav-item nav-link sidebar-subnav-link {{ Route::is('branch.getCompetitions') || Route::is('branch.competitions.create') || Route::is('branch.competitions.edit') || Route::is('competitions.show') ? 'active' : '' }}">
                        <i class="fas fa-trophy me-2"></i>
                        <span>Competitions</span>
                    </a>
                    <a href="{{ route('branch.competitions.videos') }}"
                        class="nav-item nav-link sidebar-subnav-link {{ Route::is('branch.competitions.videos') ? 'active' : '' }}">
                        <i class="fas fa-video me-2"></i>
                        <span>Videos</span>
                    </a>
                    <a href="{{ route('branch.competitions.appeals') }}"
                        class="nav-item nav-link sidebar-subnav-link {{ Route::is('branch.competitions.appeals') ? 'active' : '' }}">
                        <i class="fas fa-gavel me-2"></i>
                        <span>Appeals</span>
                    </a>
                </div>
            </div>

            {{-- <a href="{{ route('plans.index') }}" class="nav-item nav-link {{ Route::is('plans.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i>Plans
            </a>

            <a href="{{ route('rulesof-counting.index') }}" class="nav-item nav-link {{ Route::is('rulesof-counting.*') ? 'active' : '' }}">
                <i class="fas fa-calculator me-2"></i>Rules of Counting
            </a> --}}
        </div>
    </nav>
</div>
