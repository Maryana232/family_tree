<div class="container-fluid navbar navbar-expand-lg bg-body-tertiary sticky-top p-4 mx-auto bg-dark-subtle">
    <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarToggler"
        aria-controls="navbarTogglerDemo01"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand navbar-text" href="/">
        <span class="badge text-bg-dark">Family Tree</span>
    </a>
    <div class="collapse navbar-collapse" id="navbarToggler">
        <ul class="navbar-nav nav-underline me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link {{ $page === "home" ? "active" : "" }}" href="/">Головна</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $page === "table" ? "active" : "" }}" href="/table">Таблиця</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Дії
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/add_person">Додати</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
