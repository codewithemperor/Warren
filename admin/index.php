
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta tag -->
        <meta charset="UTF-8">
        <meta name="author" content="" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="" />
        <meta name="description" content="" />

        <!-- favicon -->
        <link rel="shortcut icon" href="assets/img/favicons/favicon.png" />
        <link rel="apple-touch-icon" href="assets/img/favicons/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-touch-icon-114x114.png" />

        <!-- Title -->
        <title>Warren & Co - Riders</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" integrity="sha256-SCRy3fXoOamBaidKByHs9iJVLYJ65R/v6ycZNN4JhmE=" crossorigin="anonymous">

        <!-- Custom CSS -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/responsive.css" rel="stylesheet">

    </head>
    <body class="login">

        <div class="d-lg-flex align-items-center gap-5 container">
            <div class="col-9 col-md-7 mx-auto mb-3 mb-lg-0">
                <img src="assets/img/services(1).png" alt="" width="85%" class="mx-auto">
            </div>
            <div class="col">
                <div class="rounded-col">
                    <div class="text-center">
                        <p class="headline-large fw-medium">Admin Sign In</p>
                        <p class="title-small mb-5">Login to your manage Account</p>
                    </div>
    
                    <form id="loginForm" class="vstack gap-4" method="post">
                        <input id="email" class="form-control" type="email" placeholder="Email Address" required>
                        <input id="password" class="form-control" type="password" placeholder="Password" required>
                        <button type="submit" class="btn-p btn d-flex align-items-center justify-content-center" id="submit-btn">
                            <span id="btn-text">Sign In</span>
                            <div id="spinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display: none;"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js" integrity="sha256-lCHT/LfuZjRp+PdpWns/vKrnSn367D/g1E6Ju18wiH0=" crossorigin="anonymous"></script>

        <!-- Custom JS -->
        <script src="assets/js/login.js"></script>
    </body>
</html>
