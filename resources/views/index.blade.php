<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="preload" href="/js/app.js" as="script">
  <link rel="stylesheet" href="/css/app.css">
  <script>
    window['csrf-token'] = "{{ Session::token() }}"
  </script>
</head>
<body>
  <h1 class="titleBar">
    Todo's
  </h1>
  <div class="root">
    @yield('pageData')
  </div>
  <script src="/js/app.js"></script>
</body>
</html>