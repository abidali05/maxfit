<div class="pb-3 sidebar pe-4">
    <nav class="navbar bg-light navbar-light">
        @php
            $orgOpen = Route::is('organisation-types.*') || Route::is('organisations.*');
            $exerciseOpen = Route::is('exercises.*') || Route::is('exercise-categories.*');
            $competitionOpen = Route::is('competitions.*') || Route::is('competition-users.*') || Route::is('competition-details.*') || Route::is('results.*');
        @endphp
        <a href="{{ url('/') }}" class="mx-4 mb-3 navbar-brand">
            <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
        </a>
        <div class="mb-4 d-flex align-items-center ms-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ Auth::user()->image }}" alt=""
                    style="width: 40px; height: 40px;">
                <div class="bottom-0 p-1 border border-2 border-white bg-success rounded-circle position-absolute end-0">
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

            <a href="{{ route('users.index') }}" class="nav-item nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                <i class="fas fa-user-friends me-2"></i>Users
            </a>

            <a href="{{ route('branches.index') }}" class="nav-item nav-link">
                <i class="fas fa-code-branch me-2"></i>Branches
            </a>

            <a href="{{ route('coaches.index') }}" class="nav-item nav-link {{ Route::is('coaches.*') ? 'active' : '' }}">
                <i class="fas fa-running me-2"></i>Coaches
            </a>

            <a href="{{ route('venues.index') }}" class="nav-item nav-link {{ Route::is('venues.*') ? 'active' : '' }}">
                <i class="fas fa-running me-2"></i>Venues
            </a>

            <div class="sidebar-section">
                <button type="button"
                    class="nav-item nav-link sidebar-parent-link {{ $orgOpen ? 'active' : '' }}"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarOrganisations"
                    aria-expanded="{{ $orgOpen ? 'true' : 'false' }}"
                    aria-controls="sidebarOrganisations">
                    <span class="sidebar-parent-label">
                        <i class="fas fa-sitemap me-2"></i>Organisations
                    </span>
                    <i class="fas fa-chevron-down sidebar-parent-chevron"></i>
                </button>
                <div id="sidebarOrganisations" class="sidebar-submenu collapse {{ $orgOpen ? 'show' : '' }}">
                    <a href="{{ route('organisation-types.index') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('organisation-types.*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group me-2"></i>
                        <span>Org. Types</span>
                    </a>
                    <a href="{{ route('organisations.index') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('organisations.*') ? 'active' : '' }}">
                        <i class="fas fa-building me-2"></i>
                        <span>Organisations</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section">
                <button type="button"
                    class="nav-item nav-link sidebar-parent-link {{ $exerciseOpen ? 'active' : '' }}"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarExercises"
                    aria-expanded="{{ $exerciseOpen ? 'true' : 'false' }}"
                    aria-controls="sidebarExercises">
                    <span class="sidebar-parent-label">
                        <i class="fas fa-dumbbell me-2"></i>Exercise
                    </span>
                    <i class="fas fa-chevron-down sidebar-parent-chevron"></i>
                </button>
                <div id="sidebarExercises" class="sidebar-submenu collapse {{ $exerciseOpen ? 'show' : '' }}">
                    <a href="{{ route('exercises.index') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('exercises.*') ? 'active' : '' }}">
                        <i class="fas fa-dumbbell me-2"></i>
                        <span>Exercises</span>
                    </a>
                    <a href="{{ route('exercise-categories.index') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('exercise-categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags me-2"></i>
                        <span>Categories</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-section">
                <button type="button"
                    class="nav-item nav-link sidebar-parent-link {{ $competitionOpen ? 'active' : '' }}"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarCompetitions"
                    aria-expanded="{{ $competitionOpen ? 'true' : 'false' }}"
                    aria-controls="sidebarCompetitions">
                    <span class="sidebar-parent-label">
                        <i class="fas fa-trophy me-2"></i>Competitions
                    </span>
                    <i class="fas fa-chevron-down sidebar-parent-chevron"></i>
                </button>
                <div id="sidebarCompetitions" class="sidebar-submenu collapse {{ $competitionOpen ? 'show' : '' }}">
                    <a href="{{ route('competitions.index') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('competitions.index') || Route::is('competitions.create') || Route::is('competitions.edit') || Route::is('competitions.show') ? 'active' : '' }}">
                        <i class="fas fa-trophy me-2"></i>
                        <span>Competitions</span>
                    </a>
                    <a href="{{ route('competitions.videos') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('competitions.videos') ? 'active' : '' }}">
                        <i class="fas fa-video me-2"></i>
                        <span>Videos</span>
                    </a>
                    <a href="{{ route('competitions.appeals') }}" class="nav-item nav-link sidebar-subnav-link {{ Route::is('competitions.appeals') ? 'active' : '' }}">
                        <i class="fas fa-gavel me-2"></i>
                        <span>Appeals</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('sets.index') }}" class="nav-item nav-link {{ Route::is('sets.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i>Sets
            </a>

            <a href="{{ route('challenge.index') }}" class="nav-item nav-link {{ Route::is('challenge.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i>Challenges
            </a>

            <a href="{{ route('plans.index') }}" class="nav-item nav-link {{ Route::is('plans.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i>Plans
            </a>

            <a href="{{ route('exercise-assessment.index') }}" class="nav-item nav-link {{ Route::is('exercise-assessment.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical me-2"></i>Exercise Assessment
            </a>

            <a href="{{ route('user-assessments.index') }}" class="nav-item nav-link {{ Route::is('user-assessments.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-2"></i>User Assessments
            </a>

            <a href="{{ route('medical-assessment-questions.index') }}" class="nav-item nav-link {{ Route::is('medical-assessment-questions.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical me-2"></i>Medical Assessment
            </a>

            <a href="{{ route('plan-questions.index') }}" class="nav-item nav-link {{ Route::is('plan-questions.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle me-2"></i>Plan Questions
            </a>

            <a href="{{ route('app-version.edit') }}" class="nav-item nav-link {{ Route::is('app-version.*') ? 'active' : '' }}">
                <i class="fas fa-mobile-alt me-2"></i>App Version
            </a>

            {{-- <a href="{{ route('rulesof-counting.index') }}" class="nav-item nav-link {{ Route::is('rulesof-counting.*') ? 'active' : '' }}">
                <i class="fas fa-calculator me-2"></i>Rules of Counting
            </a> --}}
        </div>
    </nav>
</div>
