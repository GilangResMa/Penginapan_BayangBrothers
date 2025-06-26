<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body>
    <p class="homepage">Homepage</p>
    <p class="test">This is the homepage of the PBB project.</p>
    <div x-data="{ open: false }">
        <button @click="open = ! open">Toggle</button>

        <div x-show="open" @click.outside="open = false">Contents...</div>
    </div>
</body>

</html>
