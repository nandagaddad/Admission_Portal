<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Admission Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">    
    <!--<link rel="stylesheet" href="../assets/css/style.css">-->
    <style>
    .form-group.required .control-label:after,
    .form-group.required .form-label::after {
        content: "*";
        color: red;
        margin-left: 0.25rem;
    }
    .sidebar,
    .content {
            box-sizing: border-box;
        }
    .sidebar {
            position: fixed;
            top: 60px;      /* below header */
            left: 0;
            width: 250px;
            bottom: 50px;   /* above footer */
            background: #f8f9fa;
            padding: 20px;
            overflow-y: auto;
            z-index: 1050;
        }
    .sidebar.show {
            display: block;
        }
    .content {
            margin-top: 60px;    /* header height */
            margin-left: 250px;  /* sidebar width */
            margin-bottom: 50px; /* footer height */
            padding: 20px;
            width: calc(100% - 250px);
        }
    @media (max-width: 767.98px) {
        .sidebar {
            position: static;
            width: 100%;
            bottom: auto;
            top: auto;
            margin-top: 60px;
            z-index: auto;
        }
        .sidebar.show {
            display: block;
        }
        .content {
            margin-left: 0;
            width: 100%;
        }
    }

    .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #0d6efd;
            color: white;
            z-index: 2000;
            padding: 15px;
        }
    </style>
</head>
<body>
