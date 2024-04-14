@include('parts/head')
@include('parts/nav')

<div class="container-fluid p-4 flex-grow-1">
    <h2 class="mb-4 w-75 mx-auto">Редагувати {{ $person->getFirstName() }} {{ $person->getLastName() }}</h2>
    <form method="POST" action="/edit_person" class="mb-4 w-75 mx-auto">
        <input type="hidden" name="id" value={{ $person->getId() }}>
        <!-- Вибір статі -->
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="gender" id="male" {{ $person->getGender() === "ч" ? 'checked' : ''}} value={{ $person->getGender() ?? ""}}>
            <label class="form-check-label" for="male">Чоловік</label>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="gender" id="female" {{ $person->getGender() === "ж" ? 'checked' : ''}} value="{{ $person->getGender() ?? ""}}">
            <label class="form-check-label" for="female">Жінка</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Введіть ім'я" value={{ $person->getFirstName() ?? ""}}>
            <label for="name">Ім'я</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Введіть прізвище" value={{ $person->getLastName() ?? ""}}>
            <label for="last_name">Прізвище</label>
        </div>
        <div class="form-floating mb-3">
            <input
                    type="date"
                    class="form-control"
                    id="birth_date"
                    name="birth_date"
                    placeholder="Введіть дату народження"
                    value={{ $person->getBirthDate() ?? ""}}
            >
            <label for="birth_date">Дата народження</label>
        </div>
        <div class="form-floating mb-3">
            <input
                    type="date"
                    class="form-control"
                    id="death_date"
                    name="death_date"
                    placeholder="Введіть дату смерті"
                    value={{ $person->getDeathDate() ?? ""}}
            >
            <label for="death_date">Дата смерті</label>
        </div>
        <select class="form-select mb-3" aria-label="Default select example" name="mother_id" id="mother_id">
            @if($females)
                <option value="0" {{ $person->getMotherId() === 0 ? 'selected' : ''}}>
                    Оберіть матір
                </option>
                @foreach ($females as $female)
                    <option value="{{ $female->getId() }}" {{ $female->getId() === $person->getMotherId()  ? 'selected' : '' }}>
                        {{ $female->getFirstName() }} {{$female->getLastName()}} -
                        {{ $female->getBirthDate() }} -
                        {{ $female->getDeathDate() ?? "Жива"}}
                    </option>
                @endforeach
            @else
                <option value="0" selected>
                    Оберіть матір
                </option>
                @foreach ($females as $female)
                    <option value="{{ $female->getId() }}">
                        {{ $female->getFirstName() }} {{$female->getLastName()}} -
                        {{ $female->getBirthDate() }} -
                        {{ $female->getDeathDate() ?? "Жива"}}
                    </option>
                @endforeach
            @endif
        </select>
        <select class="form-select mb-3" aria-label="Default select example" name="father_id" id="father_id">
            @if($males)
                <option value="0" {{ $person->getFatherId() ? '' : 'selected'}}>
                    Оберіть батька
                </option>
                @foreach ($males as $male)
                    <option value="{{ $male->getId() }}" {{ $male->getId() === $person->getFatherId() ? 'selected' : '' }}>
                        {{ $male->getFirstName() }} {{$male->getLastName()}} -
                        {{ $male->getBirthDate() }} -
                        {{ $male->getDeathDate() ?? "Живий"}}
                    </option>
                @endforeach
            @else
                <option value="0" selected>
                    Оберіть батька
                </option>
                @foreach ($males as $male)
                    <option value="{{ $personList->id }}">
                        {{ $male->getFirstName() }} {{$male->getLastName()}} -
                        {{ $male->getBirthDate() }} -
                        {{ $male->getDeathDate() ?? "Живий"}}
                    </option>
                @endforeach
            @endif
        </select>
        <button type="submit" class="btn btn-primary">Редагувати</button>
    </form>
</div>

@include('parts/footer')
