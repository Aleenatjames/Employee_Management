<!-- resources/views/components/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Application Title</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div>
        <header>
            <!-- Your header content -->
        </header>
        <main>
            {{ $slot }} <!-- This is where the Livewire component will render -->
        </main>
        <footer>
            <!-- Your footer content -->
        </footer>
    </div>

    @livewireScripts
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
