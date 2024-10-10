
   @if(!empty($user) && $_GET['a'] == 'modules')
    <!-- ================= Appbar ================= -->
    <div
      class="bg-white d-flex align-items-center fixed-top shadow"
      style="min-height: 56px; z-index: 5">
      <div class="container-fluid">
        <div class="row align-items-center">
          <!-- search -->
          <div class="col d-flex align-items-center">
            <!-- logo -->
            <image src="{{$url}}/sj.png" width="50rem" height="50rem">
            <!-- search bar -->
            <div class="input-group ms-2" type="button">
              <!-- mobile -->
              <span class="input-group-prepend d-lg-none" id="searchMenu" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                <div
                  class="input-group-text bg-gray border-0 rounded-circle"style="min-height: 40px">
                  <i class="fas fa-search text-muted"></i>
                </div>
              </span>
              <!-- desktop -->
              <span class="input-group-prepend d-none d-lg-block" id="searchMenu" data-bs-toggle="dropdown" aria-expanded="false"
                data-bs-auto-close="outside">

                <div class="input-group-text bg-gray border-0 rounded-pill" style="min-height: 40px; min-width: 230px">
                  <i class="fas fa-search me-2 text-muted"></i>
                  <p class="m-0 fs-7 text-muted">Search Farmhouse</p>
                </div>
              </span>
              <!-- search menu -->
              <ul class="dropdown-menu overflow-auto border-0 shadow p-3" aria-labelledby="searchMenu"  style="width: 20em; max-height: 600px">
                <!-- search input -->
                <li>
                  <input type="text" class="rounded-pill border-0 bg-gray dropdown-item" placeholder="Search SjFarm..." autofocus/>
                </li>
                <!-- search 1 -->
                <li class="my-4">
                  <div
                    class="alert fade show  dropdown-item p-1  m-0 d-flex align-items-center justify-content-between " role="alert">
                    <div class="d-flex align-items-center">
                      <img src="https://source.unsplash.com/random/1"  alt="avatar" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover"/>
                      <p class="m-0">Lorem ipsum</p>
                    </div>
                    <button type="button" class="btn-close p-0 m-0" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </li>
                <!-- search 2 -->
                <li class="my-4">
                  <div
                    class="alert fade  show dropdown-item  p-1 m-0  d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center">
                      <img  src="https://source.unsplash.com/random/2"alt="avatar" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover"/>
                      <p class="m-0">Lorem ipsum</p>
                    </div>
                    <button type="button" class="btn-close p-0 m-0" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </li>
                <!-- search 3 -->
                <li class="my-4">
                  <div
                    class=" alert fade show dropdown-item p-1  m-0 d-flex align-items-center justify-content-between"
                    role="alert">
                    <div class="d-flex align-items-center">
                      <img  src="https://source.unsplash.com/random/3" alt="avatar" class="rounded-circle me-2"
                        style="width: 35px; height: 35px; object-fit: cover"/>
                      <p class="m-0">Lorem ipsum</p>
                    </div>
                    <button type="button" class="btn-close p-0 m-0" data-bs-dismiss="alert" aria-label="Close"
                    ></button>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <!-- nav -->
          <div class="col d-none d-xl-flex justify-content-center">
            <!-- home -->
            <div class="mx-4 nav__btn nav__btn-active">
              <button type="button" class="btn px-4">
                <i class="fas fa-home text-muted fs-4"></i>
              </button>
            </div>
             <!-- Stocks -->
             <div class="mx-4 nav__btn">
              <button type="button" class="btn px-4">
                
                <i type="button"class="position-relative fa-solid fa-warehouse text-muted fs-4">
                  <span class=" position-absolute  top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.5rem">1
                    <span class="visually-hidden"></span>
                  </span>
                </i>
              </button>
            </div>
            <!-- market -->
            <div class="mx-4 nav__btn">
              <button type="button" class="btn px-4">
                {{-- <i class="fas fa-store text-muted fs-4"></i> --}}
                <i class="fa-solid fa-file-pen text-muted fs-4"></i>
              </button>
            </div>
           
            <!-- Report -->
            <div class="mx-4 nav__btn">
              <button type="button" class="btn px-4">
                <i class="fas fa-file-alt text-muted fs-4"></i>
                
              </button>
            </div>
          </div>
          <!-- menus -->
          <div class="col d-flex align-items-center justify-content-end">
            <!-- avatar -->
            <div class="align-items-center justify-content-center d-none d-xl-flex">
              <img src="{{$profile_pic}}"
                class="rounded-circle me-2"
                alt="avatar"
                style="width: 38px; height: 38px; object-fit: cover"
              />
              <p class="m-0">{{$username}}</p>
            </div>
            <!-- main menu -->
            <div class="rounded-circle p-1 bg-gray d-flex align-items-center justify-content-center mx-2"
              style="width: 38px; height: 38px" type="button" id="mainMenu" data-bs-toggle="dropdown" aria-expanded="false"
              data-bs-auto-close="outside">
            <i class="fa-solid fa-grip"></i>
            </div>
            <!-- main menu dd -->
            <ul class="dropdown-menu border-0 shadow p-3 overflow-auto" aria-labelledby="mainMenu" style="width: 23em; max-height: 600px">
              <!-- menu -->
              <div>
                <!-- header -->
                <li class="p-1 mx-2">
                  <h2>Shortcuts</h2>
                </li>
                <!-- search -->
                <li class="p-1">
                  <div class="input-group-text bg-gray border-0 rounded-pill"style="min-height: 40px; min-width: 230px">
                    <i class="fas fa-search me-2 text-muted"></i>
                    <input type="text" class="form-control rounded-pill border-0 bg-gray" placeholder="Search Menu"/>
                  </div>
                </li>
                <!-- social items -->
                <h4 class="m-2">Create</h4>

                  <!-- s1 -->
                  <li class="my-2 p-1">
                    <a
                      href="#"
                      class="
                        text-decoration-none text-dark
                        d-flex
                        align-items-center
                        justify-content-between
                      ">
                      <div class="p-2">
                        <img
                          src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png"
                          alt="icon from fb"
                          class="rounded-circle"
                          style="width: 48px; height: 48px; object-fit: cover"
                        />
                      </div>
                      <div>
                        <p class="m-0">New User</p>
                        <span class="fs-7 text-muted"
                          >create a User to Deliver Milk him.</span
                        >
                      </div>
                    </a>
                  </li>

                <!-- s2 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img
                        src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png"
                        alt="icon from fb"
                        class="rounded-circle"
                        style="width: 48px; height: 48px; object-fit: cover"
                      />
                    </div>
                    <div>
                      <p class="m-0">Milk Entery</p>
                      <span class="fs-7 text-muted"
                        >Create a entery for a User. Deliver them Milk</span
                      >
                    </div>
                  </a>
                </li>
              
                <!-- s3 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class="text-decoration-none text-dark
                      d-flex
                      align-items-center
                      justify-content-between
                    "
                  >
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png" alt="icon from fb" class="rounded-circle"
                        style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Dahi Entery</p>
                      <span class="fs-7 text-muted"
                        >Create a entery for a User. Deliver them Dahi.</span
                      >
                    </div>
                  </a>
                </li>
                <!-- s4 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img  src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png" alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Ghee Entery</p>
                      <span class="fs-7 text-muted"
                        >Create a entery for a User. Deliver them Ghee.</span>
                    </div>
                  </a>
                </li>
                <!-- ent items -->
                <h4 class="m-2">Stocks</h4>
                <!-- s5 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class=" text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png" alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Upload Chara</p>
                      <span class="fs-7 text-muted">Add Chara Stock for animal.</span>
                    </div>
                  </a>
                </li>
                <!-- s6 -->
                <li class="my-2 p-1">
                  <a href="#" class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png"
                        alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Consupt Chara</p>
                      <span class="fs-7 text-muted"> Consupt dana & chara entery from stock.</span>
                    </div>
                  </a>
                </li>
                <hr />
                <!-- ent items -->
                <h4 class="m-2">Expenses</h4>
                <!-- e1 -->
                <li class="my-2 p-1">
                  <a href="#" class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png"
                        alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Staff</p>
                      <span class="fs-7 text-muted">All staff and Labour sallery Expense.</span>
                    </div>
                  </a>
                </li>
                <!-- e2 -->
                <li class="my-2 p-1">
                  <a href="#" class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png"
                        alt="icon from fb"
                        class="rounded-circle"style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">elecricity</p>
                      <span class="fs-7 text-muted">Electricy Expenses</span>
                    </div>
                  </a>
                </li>
                <!-- e3 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class=" text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png" alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Doctor</p>
                      <span class="fs-7 text-muted">If doctor Expenses.</span>
                    </div>
                  </a>
                </li>
                <!-- e3 -->
                <li class="my-2 p-1">
                  <a
                    href="#"
                    class=" text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      <img src="https://cdn-icons-png.flaticon.com/256/9153/9153973.png" alt="icon from fb" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover"/>
                    </div>
                    <div>
                      <p class="m-0">Transport</p>
                      <span class="fs-7 text-muted">Transport & diesal expenses.</span>
                    </div>
                  </a>
                </li>
              </div>
              <hr/>
            
             
            </ul>
            <!-- chat -->
            <div
              class=" rounded-circle p-1 bg-gray d-flex align-items-center justify-content-center mx-2
              "style="width: 38px; height: 38px" type="button" id="chatMenu" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            <i class="fa-brands fa-facebook-messenger"></i>
            </div>
            <!-- chat  dd -->
            <ul
              class="dropdown-menu border-0 shadow p-3 overflow-auto" aria-labelledby="chatMenu" style="width: 23em; max-height: 600px" >
              <!-- header -->
              <li class="p-1">
                <div class="d-flex justify-content-between">
                  <h2>Message</h2>
                  <div>
                    <!-- setting -->
                    
                   
                    
                    
                  </div>
                </div>
              </li>
              <!-- search -->
              <li class="p-1">
                <div class="input-group-text bg-gray border-0 rounded-pill" style="min-height: 40px; min-width: 230px">
                  <i class="fas fa-search me-2 text-muted"></i>
                  <input type="text" class="form-control rounded-pill border-0 bg-gray" placeholder="Search Messenger"/>
                </div>
              </li>
              <!-- message 1 -->
              <li>
                No single Message is Presenting Right Now
              </li>
              <hr class="m-0" />
              <a href="#" class="text-decoration-none">
                <p class="fw-bold text-center pt-3 m-0">See All in Messenger</p>
              </a>
            </ul>
            
            <!-- notifications -->
            <div
              class=" rounded-circle p-1 bg-gray d-flex align-items-center justify-content-center mx-2"
              style="width: 38px; height: 38px" type="button" id="notMenu"data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" >
              <i class="fas fa-bell"></i>
            </div>
            <!-- notifications dd -->
            <ul
              class="dropdown-menu border-0 shadow p-3"
              aria-labelledby="notMenu"
              style="width: 23em; max-height: 600px; overflow-y: auto">
              <!-- header -->
              <li class="p-1">
                <div class="d-flex justify-content-between">
                  <h2>Notifications</h2>
                  <div>
                    <i class=" fas fa-ellipsis-h pointer p-2 rounded-circle bg-gray" type="button"
                      data-bs-toggle="dropdown"></i>
                  </div>
                </div>
                
              </li>


              <!-- news -->
              <div class="d-flex justify-content-between mx-2">
                <h5>New</h5>
                <a href="#" class="text-decoration-none">See All</a>
              </div>
              <!-- news 1 -->
              <li class="my-2 p-1">
                <a href="#"class=" d-flex align-items-center justify-content-evenly text-decoration-none text-dark">
                  <div class="d-flex align-items-center justify-content-evenly">
                    <div class="p-2">
                      <img
                        src="https://source.unsplash.com/random/1"
                        alt="avatar"
                        class="rounded-circle"
                        style="width: 58px; height: 58px; object-fit: cover"
                      />
                    </div>
                    <div>
                      <p class="fs-7 m-0">
                       New Updates Updated check What you got.
                      </p>
                      <span class="fs-7 text-primary">about an hour ago</span>
                    </div>
                  </div>
                  <i class="fas fa-circle fs-7 text-primary"></i>
                </a>
              </li>
             
            </ul>

            <!-- secondary menu -->
            <div class=" rounded-circle p-1 bg-gray d-flex align-items-center justify-content-center mx-2" style="width: 38px; height: 38px" type="button"  id="secondMenu"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                data-bs-auto-close="outside">
              <i class="fas fa-caret-down"></i>
            </div>
            <!-- secondary menu dd -->
            <ul class="dropdown-menu border-0 shadow p-3" aria-labelledby="secondMenu"
              style="width: 23em">
              <!-- avatar -->
              <li class="dropdown-item p-1 rounded d-flex" type="button">
                <img  src="{{$profile_pic}}"
                  alt="avatar"
                  class="rounded-circle me-2"
                  style="width: 45px; height: 45px; object-fit: cover"/>
                <div>
                    @if(!empty($permissions))
                    <div class="mb-3">
                      <label for="exampleSelect" class="form-label">Choose Module</label>
 
                      <select class="form-select" onchange="window.location.href='{{$module_link}}'+this.value+ '&section=dashboard'">
                       
                        @foreach($permissions as $key => $module)
                        @php
                        $select = ($_GET['module'] == $module['module_name']) ? 'selected' : '';
                        @endphp

                        <option value="{{$module['module_name']}}" {{$select}}> {{$module['module_name']}}</option>
                       
                        @endforeach
                      </select>
                    </div>
                    @endif
                  
                   
                 
  
                </div>
              </li>
              <hr />
              <!-- feedback -->
              <li
                class="dropdown-item p-1 rounded d-flex align-items-center"
                type="button">
                <i class="fas fa-exclamation-circle bg-gray p-2 rounded-circle"></i>
                <div class="ms-3">
                  <p class="m-0">Give Feedback</p>
                  <p class="m-0 text-muted fs-7">
                    Help us improve the new Flexbook.
                  </p>
                </div>
              </li>
              <hr />
              <!-- options -->
              
              <!-- 1 -->
              <li class="dropdown-item p-1 my-3 rounded" type="button">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a href="{{$logout_url}}" class="d-flex text-decoration-none text-dark">
                      <i class="fas fa-cog bg-gray p-2 rounded-circle"></i>
                      <div
                        class="ms-3 d-flex justify-content-between align-items-center w-100">
                        <p class="m-0">Log Out</p>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
            <!-- end -->
          </div>
        </div>
      </div>
    </div>
  @push('content_script')
  <script>
  const urlParams = new URLSearchParams(window.location.search);
  const moduleValue = urlParams.get('module');

  </script>
  @endpush

@endif
 
 
