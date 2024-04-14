@include('parts/head')
@include('parts/nav')

<div class="container-fluid p-4 flex-grow-1">
    <h2 class="mb-4 w-75 mx-auto">{{ $title }}</h2>
    <div class="table-responsive-lg mb-4 w-75 mx-auto">
        <table class="table table-striped table-hover table-bordered">
            <caption>Загальний список людей</caption>
            <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">ID</th>
                <th scope="col" class="text-center">Ім'я</th>
                <th scope="col" class="text-center">Стать</th>
                <th scope="col" class="text-center">Вік</th>
                <th scope="col" class="text-center">Дата народження</th>
                <th scope="col" class="text-center">Дата смерті</th>
                <th scope="col" class="text-center">ID батька</th>
                <th scope="col" class="text-center">ID матері</th>
                <th scope="col" class="text-center">Дії</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($peopleOnPage as $personOnPage)
                <tr>
                    <th scope="row">{{ $personOnPage->getId() }}</th>
                    <td>
                        <a href="/person?id={{ $personOnPage->getId() }}"
                           class="text-decoration-none text-black">
                            {{ $personOnPage->getFirstName() }} {{ $personOnPage->getLastName() }}
                            <span class="badge bg-secondary-subtle rounded-pill">
                                ?
                            </span>
                        </a>
                    </td>
                    <td>
                        {{$personOnPage->getGender()}}
                    </td>
                    <td>
                        @if($personOnPage->getBirthDate() && $personOnPage->getDeathDate() === null)
                        {{
                            // Порахувати різницю між датою народження та поточною датою
                            \Carbon\Carbon::parse($personOnPage->getBirthDate())->diffInYears(\Carbon\Carbon::now())
                        }}
                        @elseif($personOnPage->getDeathDate())
                        {{
                            // Порахувати різницю між датами
                            \Carbon\Carbon::parse($personOnPage->getBirthDate())->diffInYears($personOnPage->getDeathDate())
                        }}
                        @else
                            <span class="badge bg-secondary-subtle rounded-pill">
                                null
                            </span>
                        @endif
                    <td>
                        @if($personOnPage->getBirthDate())
                            {{ $personOnPage->getBirthDate() }}
                        @else
                            <span class="badge bg-secondary-subtle rounded-pill">
                                null
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($personOnPage->getDeathDate())
                            {{ $personOnPage->getDeathDate() }}
                        @else
                            <span class="badge bg-secondary-subtle rounded-pill">
                                null
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($personOnPage->getFatherId())
                            <a href="/person?id={{ $personOnPage->getFatherId() }}"
                               class="text-decoration-none text-black">
                                {{ $personOnPage->getFatherId()  }}
                                <span class="badge bg-secondary-subtle rounded-pill">
                                    ?
                                </span>
                            </a>
                        @else
                            <span class="badge bg-secondary-subtle rounded-pill">
                                null
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($personOnPage->getMotherId())
                            <a href="/person?id={{ $personOnPage->getMotherId() }}"
                               class="text-decoration-none text-black">
                                {{ $personOnPage->getMotherId() }}
                                <span class="badge bg-secondary-subtle rounded-pill">
                                    ?
                                </span>
                            </a>
                        @else
                            <span class="badge bg-secondary-subtle rounded-pill">
                                null
                            </span>
                        @endif
                    </td>
                    <td class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="/edit_person?person_id={{ $personOnPage->getId() }}"
                           class="text-secondary text-decoration-none">
                            <span class="material-symbols-outlined">
                                edit
                            </span>
                        </a>
                        <a href="/delete_person?person_id={{ $personOnPage->getId() }}"
                           class="text-danger text-decoration-none">
                            <span class="material-symbols-outlined">
                                delete
                            </span>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mx-auto">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{-- Кнопка "Назад" --}}
                <li class="page-item {{$currentPage == 1 ? 'disabled' : ''}}">
                    <a class="page-link text-black" href="/table?page={{ max($currentPage - 1, 1) }}">&laquo;</a>
                </li>

                {{-- Номера сторінок --}}
                @for ($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item {{$currentPage == $i ? 'active' : ''}}">
                        <a href="/table?page={{ $i }}" class="page-link text-black">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Кнопка "Вперед" --}}
                <li class="page-item {{$currentPage == $totalPages ? 'disabled' : ''}}">
                    <a class="page-link text-black" href="/table?page={{ min($currentPage + 1, $totalPages) }}">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>
 </div>

@include('parts/footer')
