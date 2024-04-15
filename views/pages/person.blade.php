@include('parts/head')
@include('parts/nav')

<div class="container-fluid p-4 flex-grow-1">
    <h2 class="mb-4 w-75 mx-auto">Cімейне дерево</h2>
    {{-- Онуки --}}
    <div class="row mb-4 w-75 mx-auto d-flex justify-content-center gap-4">
        @if(count($tree->getGrandchildren()) === 0)
            <div class="card col-4">
                <div class="card-title text-center">
                    <h2>Онуки</h2>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Інформація про онуків відсутня
                    </p>
                </div>
            </div>
        @else
            @foreach ($tree->getGrandchildren() as $grandChild)
                <div class="card col-4">
                    <div class="card-title text-center">
                        <h2>{{ $grandChild->getGender() === "ч" ? "Онук" : "Онука" }}</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ім'я: {{ $grandChild->getFirstName() }}</p>
                        <p class="card-text">Прізвище: {{ $grandChild->getLastName() }}</p>
                        <p class="card-text">Дата народження: {{ $grandChild->getBirthDate() }}</p>
                        <p class="card-text">Дата смерті: {{ $grandChild->getDeathDate() ?? "Живий" }}</p>
                        {{-- Посилання на онука --}}
                        <p class="card-text">
                            <a href="/person?id={{ $grandChild->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                                Подивитись онука
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>



    {{-- Діти --}}
    <div class="row mb-4 w-75 mx-auto d-flex justify-content-center gap-4">
        @php
            $children = $tree->getChildren();  // Отримання дітей
        @endphp

        @if(count($children) === 0)
            <div class="card col-4">
                <div class="card-title">
                    <h2 class="text-center">Діти</h2>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Інформація про дітей відсутня
                    </p>
                </div>
            </div>
        @else
            @foreach ($children as $child)
                <div class="card col-4">
                    <div class="card-title">
                        <h2 class="text-center">{{ $child->getGender() === "ч" ? "Син" : "Донька" }} </h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ім'я: {{ $child->getFirstName() }}</p>
                        <p class="card-text">Прізвище: {{ $child->getLastName() }}</p>
                        <p class="card-text">Дата народження: {{ $child->getBirthDate() }}</p>
                        <p class="card-text">Дата смерті: {{ $child->getDeathDate ?? "Живий" }}</p>
                        {{-- Дивитись інформацію про сина чи доньку --}}
                        <p class="card-text">
                            <a href="/person?id={{ $child->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                                Дивитись дитину
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Сама людина --}}
    <div class="row mb-4 w-75 mx-auto">
        <div class="card col-4 mx-auto bg-warning-subtle">
            <div class="card-body">
                <p class="card-text">Ім'я: {{ $person->getFirstName() }}</p>
                <p class="card-text">Прізвище: {{ $person->getLastName() }}</p>
                <p class="card-text">Стать: {{ $person->getGender() === "ч" ? "Чоловік" : "Жінка" }}</p>
                <p class="card-text">Дата народження: {{ $person->getBirthDate() }}</p>
                <p class="card-text">Дата смерті: {{ $person->getDeathDate() ?? "Живий" }}</p>
            </div>
        </div>
    </div>

    {{-- Брати та сестри --}}
    <div class="row mb-4 w-75 mx-auto d-flex justify-content-center gap-4">
        @php
            $siblings = $tree->getSiblings();  // Отримання братів та сестер
        @endphp

        @if(count($siblings) === 0)
            <div class="card col-4">
                <div class="card-title text-center">
                    <h2>Брати та сестри</h2>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Інформація про братів та сестер відсутня
                    </p>
                </div>
            </div>
        @else
            @foreach ($siblings as $sibling)
                <div class="card col-4">
                    <div class="card-title text-center">
                        <h2>{{ $sibling->getGender() === "ч" ? "Брат" : "Сестра" }}</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ім'я: {{ $sibling->getFirstName() }}</p>
                        <p class="card-text">Прізвище: {{ $sibling->getLastName() }}</p>
                        <p class="card-text">Дата народження: {{ $sibling->getBirthDate() }}</p>
                        <p class="card-text">Дата смерті: {{ $sibling->getDeathDate() ?? "Живий" }}</p>
                        {{-- Дивитись інформацію про брата чи сестру --}}
                        <p class="card-text">
                            <a href="/person?id={{ $sibling->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                                Дивитись {{ $sibling->getGender() === "ч" ? "брата" : "сестру" }}
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Батько та мати --}}
    {{-- Батьки --}}
    <div class="row mb-4 w-75 mx-auto d-flex justify-content-center gap-4">
        @php
            $father = $person->getFather();
            $mother = $person->getMother();
        @endphp

        {{-- Батько --}}
        <div class="card col-4">
            <div class="card-title text-center">
                <h2>Батько</h2>
            </div>
            <div class="card-body">
                @if($father)
                    <p class="card-text">Ім'я: {{ $father->getFirstName() }}</p>
                    <p class="card-text">Прізвище: {{ $father->getLastName() }}</p>
                    <p class="card-text">Дата народження: {{ $father->getBirthDate() }}</p>
                    <p class="card-text">Дата смерті: {{ $father->getDeathDate() ?? "Живий" }}</p>
                    <p class="card-text">
                        <a href="/person?id={{ $father->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                            Дивитись батька
                        </a>
                    </p>
                @else
                    <p class="card-text">Немає інформації</p>
                    <p class="card-text">
                        <a href="/edit_person?person_id={{ $person->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                            Додати батька
                        </a>
                    </p>
                @endif
            </div>
        </div>

        {{-- Мати --}}
        <div class="card col-4">
            <div class="card-title text-center">
                <h2>Мати</h2>
            </div>
            <div class="card-body">
                @if($mother)
                    <p class="card-text">Ім'я: {{ $mother->getFirstName() }}</p>
                    <p class="card-text">Прізвище: {{ $mother->getLastName() }}</p>
                    <p class="card-text">Дата народження: {{ $mother->getBirthDate() }}</p>
                    <p class="card-text">Дата смерті: {{ $mother->getDeathDate() ?? "Жива" }}</p>
                    <p class="card-text">
                        <a href="/person?id={{ $mother->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                            Дивитись матір
                        </a>
                    </p>
                @else
                    <p class="card-text">Немає інформації</p>
                    <p class="card-text">
                        <a href="/edit_person?person_id={{ $person->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                            Додати матір
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>


    {{-- Дідусі та бабусі --}}
    {{-- Дідусі та бабусі --}}
    <div class="row mb-4 w-75 mx-auto d-flex justify-content-center gap-4">
        @php
            $grandParents = $person->getGrandParents(); // Отримання дідусів та бабусь
        @endphp

        @if(count($grandParents) === 0)
            <div class="card col-4">
                <div class="card-title text-center">
                    <h2>Дідусі та бабусі</h2>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Інформація про дідусів та бабусь відсутня
                    </p>
                </div>
            </div>
        @else
            @foreach ($grandParents as $grandParent)
                <div class="card col-4">
                    <div class="card-title text-center">
                        <h2>{{ $grandParent->getGender() === "ч" ? "Дідусь" : "Бабуся" }}</h2>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ім'я: {{ $grandParent->getFirstName() }}</p>
                        <p class="card-text">Прізвище: {{ $grandParent->getLastName() }}</p>
                        <p class="card-text">Дата народження: {{ $grandParent->getBirthDate() }}</p>
                        <p class="card-text">Дата смерті: {{ $grandParent->getDeathDate() ?? "Живий/Жива" }}</p>
                        <p class="card-text">
                            <a href="/person?id={{ $grandParent->getId() }}" class="text-decoration-none btn btn-outline-secondary text-dark">
                                Дивитись {{ $grandParent->getGender() === "ч" ? "дідуся" : "бабусю" }}
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@include('parts/footer')
