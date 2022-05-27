<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
 */

return [
    'accepted' => 'A(z) :attribute el kell legyen fogadva!',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'A(z) :attribute nem érvényes url!',
    'after' => 'A(z) :attribute :date utáni dátum kell, hogy legyen!',
    'after_or_equal' => 'A(z) :attribute nem lehet korábbi dátum, mint :date!',
    'alpha' => 'A(z) :attribute kizárólag betűket tartalmazhat!',
    'alpha_dash' => 'A(z) :attribute kizárólag betűket, számokat és kötőjeleket tartalmazhat!',
    'alpha_num' => 'A(z) :attribute kizárólag betűket és számokat tartalmazhat!',
    'array' => 'A(z) :attribute egy tömb kell, hogy legyen!',
    'before' => 'A(z) :attribute :date előtti dátum kell, hogy legyen!',
    'before_or_equal' => 'A(z) :attribute nem lehet későbbi dátum, mint :date!',
    'between' => [
        'array' => 'A(z) :attribute :min - :max közötti elemet kell, hogy tartalmazzon!',
        'file' => 'A(z) :attribute mérete :min és :max kilobájt között kell, hogy legyen!',
        'numeric' => 'A(z) :attribute :min és :max közötti szám kell, hogy legyen!',
        'string' => 'A(z) :attribute hossza :min és :max karakter között kell, hogy legyen!',
    ],
    'boolean' => 'A(z) :attribute mező csak true vagy false értéket kaphat!',
    'confirmed' => 'A(z) :attribute nem egyezik a megerősítéssel.',
    'current_password' => 'The password is incorrect.',
    'date' => 'A(z) :attribute nem érvényes dátum.',
    'date_equals' => ':attribute meg kell egyezzen a következővel: :date.',
    'date_format' => 'A(z) :attribute nem egyezik az alábbi dátum formátummal :format!',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'A(z) :attribute és :other értékei különbözőek kell, hogy legyenek!',
    'digits' => 'A(z) :attribute :digits számjegyű kell, hogy legyen!',
    'digits_between' => 'A(z) :attribute értéke :min és :max közötti számjegy lehet!',
    'dimensions' => 'A(z) :attribute felbontása nem megfelelő.',
    'distinct' => 'A(z) :attribute értékének egyedinek kell lennie!',
    'email' => 'A(z) :attribute nem érvényes email formátum.',
    'ends_with' => 'A(z) :attribute a következővel kell végződjön: :values',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'A kiválasztott :attribute érvénytelen.',
    'file' => 'A(z) :attribute fájl kell, hogy legyen!',
    'filled' => 'A(z) :attribute megadása kötelező!',
    'gt' => [
        'array' => 'A(z) :attribute több, mint :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nagyobb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nagyobb kell, hogy legyen, mint :value!',
        'string' => 'A(z) :attribute hosszabb kell, hogy legyen, mint :value karakter.',
    ],
    'gte' => [
        'array' => 'A(z) :attribute legalább :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet kevesebb, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute nagyobb vagy egyenlő kell, hogy legyen, mint :value!',
        'string' => 'A(z) :attribute hossza nem lehet kevesebb, mint :value karakter.',
    ],
    'image' => 'A(z) :attribute képfájl kell, hogy legyen!',
    'in' => 'A kiválasztott :attribute érvénytelen.',
    'in_array' => 'A(z) :attribute értéke nem található a(z) :other értékek között.',
    'integer' => 'A(z) :attribute értéke szám kell, hogy legyen!',
    'ip' => 'A(z) :attribute érvényes IP cím kell, hogy legyen!',
    'ipv4' => 'A(z) :attribute érvényes IPv4 cím kell, hogy legyen!',
    'ipv6' => 'A(z) :attribute érvényes IPv6 cím kell, hogy legyen!',
    'json' => 'A(z) :attribute érvényes JSON szöveg kell, hogy legyen!',
    'lt' => [
        'array' => 'A(z) :attribute kevesebb, mint :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete kisebb kell, hogy legyen, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute kisebb kell, hogy legyen, mint :value!',
        'string' => 'A(z) :attribute rövidebb kell, hogy legyen, mint :value karakter.',
    ],
    'lte' => [
        'array' => 'A(z) :attribute legfeljebb :value elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet több, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute kisebb vagy egyenlő kell, hogy legyen, mint :value!',
        'string' => 'A(z) :attribute hossza nem lehet több, mint :value karakter.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => 'A(z) :attribute legfeljebb :max elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet több, mint :max kilobájt.',
        'numeric' => 'A(z) :attribute értéke nem lehet nagyobb, mint :max!',
        'string' => 'A(z) :attribute hossza nem lehet több, mint :max karakter.',
    ],
    'mimes' => 'A(z) :attribute kizárólag az alábbi fájlformátumok egyike lehet: :values.',
    'mimetypes' => 'A(z) :attribute kizárólag az alábbi fájlformátumok egyike lehet: :values.',
    'min' => [
        'array' => 'A(z) :attribute legalább :min elemet kell, hogy tartalmazzon.',
        'file' => 'A(z) :attribute mérete nem lehet kevesebb, mint :min kilobájt.',
        'numeric' => 'A(z) :attribute értéke nem lehet kisebb, mint :min!',
        'string' => 'A(z) :attribute hossza nem lehet kevesebb, mint :min karakter.',
    ],
    'multiple_of' => 'A :attribute :value többszörösének kell lennie',
    'not_in' => 'A(z) :attribute értéke érvénytelen.',
    'not_regex' => 'A(z) :attribute formátuma érvénytelen.',
    'numeric' => 'A(z) :attribute szám kell, hogy legyen!',
    'password' => 'A(z) :attribute jelszónak kell, hogy legyen!',
    'present' => 'A(z) :attribute mező nem található!',
    'prohibited' => 'A :attribute mező tilos.',
    'prohibited_if' => 'A :attribute mező tilos, ha :other :value.',
    'prohibited_unless' => 'A :attribute mező tilos, kivéve, ha :other a :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'A(z) :attribute formátuma érvénytelen.',
    'required' => 'A(z) :attribute megadása kötelező!',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'A(z) :attribute megadása kötelező, ha a(z) :other értéke :value!',
    'required_unless' => 'A(z) :attribute megadása kötelező, ha a(z) :other értéke nem :values!',
    'required_with' => 'A(z) :attribute megadása kötelező, ha a(z) :values érték létezik.',
    'required_with_all' => 'A(z) :attribute megadása kötelező, ha a(z) :values értékek léteznek.',
    'required_without' => 'A(z) :attribute megadása kötelező, ha a(z) :values érték nem létezik.',
    'required_without_all' => 'A(z) :attribute megadása kötelező, ha egyik :values érték sem létezik.',
    'same' => 'A(z) :attribute és :other mezőknek egyezniük kell!',
    'size' => [
        'array' => 'A(z) :attribute :size elemet kell tartalmazzon!',
        'file' => 'A(z) :attribute mérete :size kilobájt kell, hogy legyen!',
        'numeric' => 'A(z) :attribute értéke :size kell, hogy legyen!',
        'string' => 'A(z) :attribute hossza :size karakter kell, hogy legyen!',
    ],
    'starts_with' => ':attribute a következővel kell kezdődjön: :values',
    'string' => 'A(z) :attribute szöveg kell, hogy legyen.',
    'timezone' => 'A(z) :attribute nem létező időzona.',
    'unique' => 'A(z) :attribute már foglalt.',
    'uploaded' => 'A(z) :attribute feltöltése sikertelen.',
    'url' => 'A(z) :attribute érvénytelen link.',
    'uuid' => ':attribute érvényes UUID-val kell rendelkezzen.',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        'email' => 'email cím',
        'gym_id' => 'edzőterem ID',
        'gymId' => 'edzőterem ID',
        'name' => 'név',
        'type' => 'típus',
        'description' => 'leírás',
        'quantity' => 'mennyiség',
        'price' => 'ár',
        'hidden' => 'elrejtve',
        'style' => 'stílus',
        'current' => 'kiválasztott edzőterem',
        'prefered' => 'alapértelmezett edzőterem',
        'password' => 'jelszó',
        'current_password' => 'jelenlegi jelszó',
        'address' => 'cím',
        'categories' => 'kategóriák',
        'enterance_code' => 'belépési kód',
        'keyGiven' => 'kulcsot odaadtam',
        'exit_code' => 'kilépési kód',
        'amount' => 'mennyiség',
        'money_recieved' => 'összeget megkaptam',
        'expiration' => 'lejárat',
        'bought' => 'vásárlás dátuma',
        'gender' => 'nem',
        'permission' => 'jogosultság',
        'credits' => 'kreditek',
        'exitcode' => 'kilépési kód',
        'gym' => 'edzőterem',
        'number' => 'szám',
    ],
];
