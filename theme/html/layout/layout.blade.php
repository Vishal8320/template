
  <!-- ================== Mains ===================== -->
    
  <div class="container-fluid" style="padding-top: 56px;">
    <div class="row">
      <!-- ================= Sidebar (18%) ================= -->
      <div class="col-12 col-md-2 col-lg-2">
        <div class="position-sticky d-xxl-block" style="top: 56px;">
          <div style="overflow: hidden;position: fixed; margin-left:10px">
            <ul class="navbar-nav mt-4 me-3 d-flex flex-column pb-5 mb-5 bg-sidebar p-4 rounded sidebar-shadow" id="accordionSidebar">
              <!-- Sidebar content -->
              @foreach($module_data['section'] as $section)
                @php
                switch ($section['section_name']) {
                    case 'dashboard':
                        $sc_image = "https://cdn-icons-png.flaticon.com/512/3129/3129630.png";
                        break;
                    case 'master':
                        $sc_image = "https://cdn-icons-png.flaticon.com/512/3129/3129630.png";
                        break;
                    case 'transection':
                        $sc_image = "https://png.pngtree.com/png-vector/20190704/ourmid/pngtree-transaction-icon-in-trendy-style-isolated-background-png-image_1539302.jpg";
                        break;
                    case 'report':
                        $sc_image = "https://png.pngtree.com/png-clipart/20190619/original/pngtree-vector-report-icon-png-image_3991909.jpg";
                        break;
                    default:
                        $sc_image = "https://w0.peakpx.com/wallpaper/127/305/HD-wallpaper-white-flowers-branches-in-white-background-white-aesthetic.jpg";
                        break;
                }
                @endphp
  
                <li class="dropdown-item p-1 rounded">
                  <a href="#" class="d-flex align-items-center text-decoration-none text-dark position-relative" 
                     data-bs-toggle="collapse" data-bs-target="#{{ $section['section_name'] }}" aria-expanded="false" 
                     aria-controls="{{ $section['section_name'] }}">
                    <div class="p-2">
                      <img src="{{$sc_image}}" alt="{{$section['section_name']}} Icon" 
                           class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover" />
                    </div>
                    <div>
                      <p class="m-0">{{ $section['section_name'] }}</p>
                    </div>
                    <i class="fa fa-chevron-right ms-auto transition-transform" aria-expanded="false"></i>
                  </a>
  
                  <ul class="list-unstyled collapse bg-white ms-3" id="{{ $section['section_name'] }}" data-bs-parent="#accordionSidebar">
                    @foreach($section['section_list'] as $list => $actions)
                      @if(is_string($list) && !empty($list))
                        <li>
                          <a href="#" class="dropdown-item d-flex align-items-center bg-warning text-dark" 
                             data-bs-toggle="collapse" data-bs-target="#submenu-{{ $list }}" aria-expanded="false" 
                             aria-controls="submenu-{{ $list }}">
                            {{ $list }}
                            <i class="fa fa-chevron-right ms-auto transition-transform"></i>
                          </a>
                          <ul class="list-unstyled collapse bg-light ps-4" id="submenu-{{ $list }}" data-bs-parent="#{{ $section['section_name'] }}">
                            @foreach($actions as $action)
                              <li><a class="dropdown-item" href="{{permalink($CONF['url'].'/index.php?a=modules&module='.$module_data['module_url'].'&section='.$section['section_url'].'&action='.$action['action_url'])}}" rel="loadpage">{{ $action['action_name'] }}</a></li>
                            @endforeach
                          </ul>
                        </li>
                      @else
                        @foreach($actions as $action)
                          <li><a class="dropdown-item" href="{{permalink($CONF['url'].'/index.php?a=modules&module='.$module_data['module_url'].'&section='.$section['section_url'].'&action='.$action['action_url'])}}" rel="loadpage">{{ $action['action_name'] }}</a></li>
                        @endforeach
                      @endif
                    @endforeach
                  </ul>
                </li>
              @endforeach
  
              <!-- terms -->
              <div class="p-2 mt-5" style="margin-top: 15em !important">
                <p class="text-muted fs-7">
                  About SjFarm &#8226; Blog &#8226; Contact-Us <br>
                  SJfarms © 2024
                </p>
              </div>
            </ul>
          </div>
        </div>
      </div>
  
      <!-- ================= Container (82%) ================= -->
      <div class="col-12 col-md-9 col-lg-10 pb-5">
        
        {!!$container!!}

      </div>
    </div>
  </div>
  


@push('content_script')
<!-- Add a script to handle the rotation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
  
  toggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const icon = this.querySelector('.fa');
      const expanded = this.getAttribute('aria-expanded') === 'true';
      const parent = this.closest('ul'); // Find the closest parent <ul> element

      // Remove styling and collapse all other dropdowns in the same parent
      parent.querySelectorAll('[data-bs-toggle="collapse"]').forEach(otherToggle => {
        if (otherToggle !== this) {
          otherToggle.classList.remove('border-start', 'border-3', 'border-danger', 'dd-bg', 'mb-2');
          otherToggle.setAttribute('aria-expanded', 'false');
          document.querySelector(otherToggle.getAttribute('data-bs-target')).classList.remove('show');
          otherToggle.querySelector('.fa').classList.replace('fa-chevron-down', 'fa-chevron-right');
        }
      });

      // Toggle the current dropdown's styles and expanded state
      if (expanded) {
        icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
        this.classList.add('border-start', 'border-3', 'border-danger', 'dd-bg', 'mb-2');
      } else {
        icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
        this.classList.remove('border-start', 'border-3', 'border-danger', 'dd-bg', 'mb-2');
      }
    });
  });
});
</script>
@endpush
    