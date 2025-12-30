<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>

    <!-- GLOBAL SYSTEM STYLES -->
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background: #eef2f3;
            padding-top: 70px; /* fixed navbar spacing */
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 180px;
            left: 0;
            width: 100%;
            height: 80vh;
            background-image: url('https://cdn.pixabay.com/photo/2016/11/29/05/08/books-1868073_1280.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.35;
            filter: brightness(0.85);
            z-index: -1;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }

        /* ACTION BUTTON FIX */
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            justify-content: center;
        }

        .action-buttons .btn {
            min-width: 55px;
            padding: 4px 8px;
            font-size: 12px;
        }

        /* MOBILE FIX */
        @media (max-width: 768px) {
            .action-buttons .btn {
                font-size: 11px;
                padding: 4px 6px;
            }
        }

        /* FOOTER PUSH */
        .footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
