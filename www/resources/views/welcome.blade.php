<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Phytokiller</title>

    </head>

    <body>
        <p>Compte : {{ $pk->currentAccount() }}</p>
        <p>Settings : {{ $pk->settings }}</p>
        <p>Users : {{ $pk->currentAccount()->users }}</p>
        <p>Palettes : {{ $pk->currentAccount()->palettes }}</p>
    </body>

</html>
