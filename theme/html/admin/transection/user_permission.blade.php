<h2 class="text-center">User Permissions</h2>
<div class="container-fluid bg-white shadow" style="width:70%;">

    @isset($output)

    @if($output['status'] == true)
    
    @php 
     $class = "alert-success";
     $icon = "far fa-check-circle me-2";
    @endphp

    @else
    
    @php 
    $class = "alert-danger";
    $icon = "fa-solid fa-circle-exclamation me-2";
   @endphp

    @endif
    @endisset

@if(!empty($output))
  <!-- Success Alert -->
  <div class="alert {{$class}} d-flex align-items-center " role="alert">
      <i class="{{$icon}}"></i>
      <div>

        @if($output['status'] == true)

            {{$output['message']}}
        @else

            @if(!is_array($output['error']))
            {{$output['error']}}
            @else
            {{$output['error'][0]}}
            @endif


        @endif
         

      </div>
  </div>
@endif



    <form method="post">
    {{-- Check if data is available --}}
    @notempty($data['all_permissions'])
        @foreach ($data['all_permissions'] as $module_key => $module)
            {{-- Module Header --}}
            <div class="border p-3 mb-4">
                <h4 class="text-center text-danger">Module: {{ $module['module_name'] }}</h4>

                @foreach ($module['sections'] as $section_key => $section)
                    {{-- Section Header --}}
                    <div class="border p-3 mb-4">
                        <h5 class="text-center">Section: {{ $section['section_name'] }} [{{ $module['module_name'] }}]</h5>
                    </div>

                    @foreach ($section['section_lists'] as $list_key => $actions)
                        @php
                            $list_name = htmlspecialchars($list_key);
                            $row_count = count($actions);
                            $first_row = true;
                        @endphp

                        {{-- Start Section List Table --}}
                        <table class="table table-bordered mb-4">
                            <thead class="table-light">
                                <tr>
                                    <th>Section List:</th>
                                    <th>Action</th>
                                    <th>Permission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actions as $index => $action)
                                    @php
                                        $action_name = htmlspecialchars($action['action_name']);
                                        $is_checked = '';

                                        // Check if permission is granted
                                        if (isset($data['user_permissions'][$module_key]['sections'][$section_key]['section_lists'][$list_key])) {
                                            foreach ($data['user_permissions'][$module_key]['sections'][$section_key]['section_lists'][$list_key] as $user_action) {
                                                if ($user_action['action_name'] === $action['action_name']) {
                                                    $is_checked = 'checked';  // Set checked if permission is granted
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if ($first_row)
                                        <tr>
                                            <td class="align-middle text-center font-weight-bold" rowspan="{{ $row_count }}">{!! ($list_name) ? $list_name : '<font color="red">NULL</font>' !!}</td>
                                            <td>{{ $action_name }}</td>
                                            <td class="text-center">
                                                @if($action_name)
                                                <input type="checkbox" name="permissions[{{ $module_key }}][section][{{ $section_key }}][action][{{ $action['action_url'] }}]checked[]" {{ $is_checked }}>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $first_row = false; @endphp
                                    @else
                                        <tr>
                                            <td>{{ $action_name }}</td>
                                            <td class="text-center">
                                                @if($action_name)
                                                <input type="checkbox" name="permissions[{{ $module_key }}][section][{{ $section_key }}][action][{{ $action['action_url'] }}]checked[]" {{ $is_checked }}>
                                                @endif
                                               
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table> {{-- End Section List Table --}}
                    @endforeach
                @endforeach
            </div> {{-- End Module Div --}}
        @endforeach
    @endnotempty

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-custom-green btn-lg">Submit</button>
    </div>
    </form>
    <br>
</div>
