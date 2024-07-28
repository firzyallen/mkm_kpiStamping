<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MKM KPI Stamping</title>
    <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('assets/img/logo_kop2.gif') }}">
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-image: url("{{ asset('assets/img/Backround login.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Animation */
        @keyframes cardAnimation {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .card-animation {
            animation: cardAnimation 0.5s ease forwards;
        }

        /* Custom styles for the card */
        .custom-card {
            width: 80%; /* Adjust the width as needed */
            max-width: 400px; /* Maximum width for responsiveness */
            margin: auto; /* Center the card horizontally */
        }
    </style>
</head>

<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main style="margin-top: 150px">
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <!-- Basic login form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5 card-animation custom-card">
                                <div class="card-body">
                                    <!--alert success -->
                                    @if (session('statusLogin'))
                                    <div class="alert alert-warning" role="alert">
                                        <strong>{{ session('statusLogin') }}</strong>
                                    </div>
                                    @elseif(session('statusLogout'))
                                    <div class="alert alert-success" role="alert">
                                        <strong>{{ session('statusLogout') }}</strong>
                                    </div>
                                    @endif

                                    <!--alert success -->
                                    <h1 class="text-center font-weight-bold mb-4">MKM KPI Stamping</h1>

                                    <!-- Login form-->
                                    <form action="{{ url('auth/login') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputEmailAddress">Username</label>
                                            <input class="form-control" id="inputEmailAddress" type="text" placeholder="Enter email address" name="email" />
                                        </div>
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" name="password" />
                                        </div>
                                        <!-- Form Group (login box)-->
                                        <div class="text-center mb-3">
                                            <button type="submit" class="btn btn-dark">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center justify-content-center">
                                    <div class="col-12 small">Copyright UIC Program X PT Mitsubishi Krama Yudha Motors and Manufacturing&copy; 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/scripts.js')}}"></script>
</body>

</html>
