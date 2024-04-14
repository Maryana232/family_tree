@include('parts/head')
@include('parts/nav')

<div class="container-fluid p-4 flex-grow-1 align-content-center">
    <h2 class="mb-4 w-75 mx-auto">Додати людину</h2>
    <form method="POST" action="/add_person" class="mb-4 w-75 mx-auto">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Введіть ім'я" required pattern="[A-Za-zА-Яа-яЁёІіЇїЄє]{2,}">
            <label for="first_name">Ім'я</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Введіть прізвище" required pattern="[A-Za-zА-Яа-яЁёІіЇїЄє]{2,}">
            <label for="last_name">Прізвище</label>
        </div>
        <!-- Вибір статі -->
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="gender" id="male" value="ч">
            <label class="form-check-label" for="male">Чоловік</label>
        </div>
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="radio" name="gender" id="female" value="ж">
            <label class="form-check-label" for="female">Жінка</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Введіть дату народження">
            <label for="birth_date">Дата народження</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="death_date" name="death_date" placeholder="Введіть дату смерті">
            <label for="death_date">Дата смерті</label>
        </div>
        <select class="form-select mb-3" aria-label="Default select example" name="mother_id" id="mother_id">
            <option selected value="0">
                Оберіть матір
            </option>
            @foreach ($females as $female)
                <option value="{{ $female->getId() }}">
                    Мати:
                    {{ $female->getFirstName() }} {{$female->getLastName()}} -
                    {{ $female->getBirthDate() }} -
                    {{ $female->getDeathDate() ?? "Жива"}}
                </option>
            @endforeach
        </select>
        <select class="form-select mb-3" aria-label="Default select example" name="father_id" id="father_id">
            <option selected value="0">
                Оберіть батька
            </option>
            @foreach ($males as $male)
                <option value="{{ $female->getId() }}">
                    Батько:
                    {{ $male->getFirstName() }} {{$male->getLastName()}} -
                    {{ $male->getBirthDate() }} -
                    {{ $male->getDeathDate() ?? "Живий"}}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Додати</button>
    </form>
 </div>

@include('parts/footer')
