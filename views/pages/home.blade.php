@include('parts/head')
@include('parts/nav')

{{-- Логотип --}}
<div class="container-fluid d-flex flex-column justify-content-center p-4">
   <img src="/ded.webp" alt="Family tree" class="img-fluid mx-auto" width="200" height="200">
</div>


<div class="container-fluid p-4">
    <h2 class="mb-4 w-75 mx-auto">Додати запис</h2>
    <form method="POST" action="/add_post" class="mb-4 w-75 mx-auto p-0">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="title" name="title" placeholder="Введіть заголовок" required>
            <label for="title">Заголовок</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" id="content" name="content" placeholder="Введіть текст" required></textarea>
            <label for="content">Текст</label>
        </div>
        <button type="submit" class="btn btn-primary">Додати</button>
    </form>
</div>
<div class="container-fluid p-4 flex-grow-1">
    <h2 class="mb-4 w-75 mx-auto">Всі записи</h2>
    <div class="d-grid w-75 mx-auto gap-2 d-md-flex justify-content-md-start">
        {{-- Виводимо всі пости --}}
        @if ($posts)
            @foreach ($posts as $post)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title
                            @if ($post->getTitle() === "Hello, world!")
                                text-primary
                            @endif
                            ">
                            {{ $post->getTitle() }}
                        </h5>
                        <p class="card-text">{{ $post->getContent() }}</p>
                        <a href="/delete_post?post_id={{ $post->getId() }}" class="text-danger text-decoration-none">
                            <span class="material-symbols-outlined">
                                delete
                            </span>
                        </a>
                    </div>
                </div>
            @endforeach
        @else
            <p>Немає записів</p>
        @endif
    </div>
</div>


@include('parts/footer')
