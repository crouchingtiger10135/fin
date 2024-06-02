<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wealth Check</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
            height: 100vh;
        }
        .sidebar h2 {
            font-size: 24px;
        }
        .sidebar .nav-link {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .main-content {
            padding: 20px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .card {
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
        }
        .card-body {
            padding: 2rem;
        }
        .table th, .table td {
            border-top: none;
        }
        .status-dot {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h2>Wealth Check</h2>
                <nav class="nav flex-column">
                    <a class="nav-link" href="/">All Clients</a>
                    <a class="nav-link" href="#">Identity Verification</a>
                    <a class="nav-link" href="#">708 Status</a>
                </nav>
            </div>
            <div class="col-md-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Add your Chart.js code here
    </script>
</body>
</html>
