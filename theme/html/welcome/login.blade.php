<div class="p-5">

    <div class="container">

        <div class="row pt-5">

            <div class="col-xl-7 col-lg-6 col-md-12">

                <div class="text-center pb-3 ">
                    <h1 class="text-primary facebook">SJ FARM</h1>
                    <h1 class="text-description"></h1>
                </div>

            </div>

            <div class="col-xl-4 col-lg-6 col-md-12">

                <div class="card shadow border-0 card-body ">
                    {{$message}}
                    <form method="POST">
                             <div class="mb-3">

                            <input type="text" name="username" class="form-control form-control-lg"
                                placeholder="Email or phone number" required>
                        </div>

                        <div class="mb-3">

                            <input type="text" name="password" class="form-control form-control-lg holder" placeholder="Password"
                                required>
                        </div>

                        <div class="d-grid gap-2 col-12 mx-auto ">
                            <button name="submit" class="btn btn-primary text-light fw-bold btn-lg border-0 btn-h-primary"
                                type="submit">Log
                                In</button>

                            <div class="text-center">
                                <a href="#" class="text-decoration-none">Forgot password?</a>
                            </div>

                            <hr>

                            <div class="text-center pb-2">
                                <button name="create_acc" class="btn btn-success text-light fw-bold btn-lg border-0 btn-h-success"
                                    type="submit">Create
                                    new
                                    account</button>
                            </div>

                    </form>


                </div>

            </div>


            <div class="text-center pt-3">
               Sj Farm Dairy Farms E-Entery!
            </div>

        </div>

    </div>

</div>

</div>

<br>


    <!-- Footer -->
    <footer class="bg-white p-4 text-muted">
        <div class="container">
          <!-- language -->
          <div class="d-flex flex-wrap">
            <p class="mx-2 fs-7 mb-0">English (US)</p>
            <p class="mx-2 fs-7 mb-0">Tiếng Việt</p>
            <p class="mx-2 fs-7 mb-0">中文(台灣)</p>
            <p class="mx-2 fs-7 mb-0">한국어</p>
            <p class="mx-2 fs-7 mb-0">日本語</p>
          </div>
          <hr />
          <!-- actions -->
          <div class="d-flex flex-wrap">
            <p class="mx-2 fs-7 mb-0">Login</p>
            <p class="mx-2 fs-7 mb-0">Blogs</p>
            <p class="mx-2 fs-7 mb-0">About Us</p>
            <p class="mx-2 fs-7 mb-0">Contact Us</p>
            <p class="mx-2 fs-7 mb-0">HELP</p>
          </div>
          <!-- copy -->
          <div class="mt-4 mx-2">
            <p class="fs-7">SJ FARMS &copy; 2024</p>
          </div>
        </div>
      </footer>







@push('script')
<script>

 console.info('This page from login and we used push');
 
 console.log('Base URL:', baseURL);
 console.log('Title:', title);
 
  
    </script>
@endpush

