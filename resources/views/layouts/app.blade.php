<!DOCTYPE html>
<html>

<head>
    <title>Contact Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.2s;
        }

        .btn-success i,
        .btn-primary i {
            margin-right: 4px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>