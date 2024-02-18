### Currencies API
This is a simple application the shows api functionality as a currencies' resource.

## To run the local dev environment:
- Navigate to `project` folder
- Run `make install`
    - It will install composer dependencies, run migration, seeders, build frontend
    - It will also parse all the data about banks and banks branches, currency rates.
- Visit page: `http://localhost` and continue to registration/login process.

## API endpoints:
1. Отримання списку банків, а також інформації про них (назва, логотип, рейтинг, номер телефону, електронна пошта)
   (Obtaining a list of banks, as well as information about them (name, logo, rating, phone number, e-mail))

    http://localhost/api/banks

    During the installation process it will parse all the banks and banks branches. Set up all the banks that are used for test in `allowed_banks` section in `config/banking.php` file.

2. Отримання всієї інформації про конкретний банк з поточними курсами валют та списком відділень
   (Obtaining all information about a specific bank with current exchange rates and a list of branches)

   http://localhost/api/bank/{bank_slug}

3. Отримання найближчих відділень банків в залежності від місцезнаходження користувача
   (Obtaining the nearest bank branches depending on the user's location) 
   Need to provide latitude and longitude in the request.

   Example: http://localhost/api/closest-branches?latitude=48.4621809&longitude=34.8355501


4. Отримання списку валют (Getting a list of currencies)

   http://localhost/api/currencies

5. Отримання актуального списку курсів валют з можливістю фільтрації даних за конкретними банками та валютами
   (Obtaining an up-to-date list of exchange rates with the ability to filter data by specific banks and currencies)

    http://localhost/api/currency-rates?bank=privatbank&currency=EUR

6. Отримання актуального курсу валют НБУ та середнього курсу по всіх банках 
   (Obtaining the current exchange rate of the NBU and the average exchange rate for all banks)

    http://localhost/api/average-rate?currency=USD

**Розширений функціонал (Additional Functionality)**

1. Обліковий запис (Account)

- Реєстрація та аутентифікація користувачів (User registration and authentication)
- Можливість редагування даних облікового запису (Ability to edit account data)

2. Історія змін курсів валют (History of exchange rate changes)

- Реалізувати механізм збору історії про суттєві зміни в курсах валют (наприклад, 5%)
  (Implement a mechanism for collecting history of significant changes in exchange rates (for example, 5%))

    All the logic in the `app/Console/Commands/UpdateCurrencyRates.php`
    There is a method `checkCurrencyRateChanges()` that checks saves the significant change to `currency_rates_changes` table if change exceeds 5%

- Додати можливість отримувати історію суттєвих змін протягом визначеного періоду
  (Add the ability to receive a history of significant changes during a specified period)

    To see Currency Changes probably need to run `php artisan currency:update` a couple of times or `make currency-update` to get more changes.
  On `http://localhost/dashboard` you can switch between currencies and dates from, to get changes in rate.


- Main files are:
  - `Makefile`
  - `app/Http/Controllers/BankBranchController.php`
  - `app/Http/Controllers/BankController.php`
  - `app/Http/Controllers/CurrencyController.php`
  - `app/Http/Controllers/CurrencyHistoryController.php`

  - `app/Console/Commands/UpdateCurrencyRates.php` is set up to run every hour in `app/Console/Kernel.php` to get new currency rates.
  - `app/Console/Commands/UpdateBankBranches.php` is set up to run every day in `app/Console/Kernel.php` to update bank branches.

  -  `app/Services/BankingAPIService.php` it's a service to make http requests to pull all the data into database about banks, currencies and banks branches.
  - Repository files are in the `app/Repositories` that have all the business logic.
