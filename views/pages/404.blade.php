@include('parts/head')
@include('parts/nav')

<div class="container-fluid p-4 flex-grow-1 d-flex flex-column align-items-center justify-content-center">
    <h2>404</h2>
    <p>Page not found</p>
    <button
        class="btn btn-dark"
        onclick="window.history.back()">Go back
    </button>
</div>

@include('parts/footer')
