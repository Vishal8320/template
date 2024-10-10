<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg p-4 rounded-lg">
                <h2 class="text-center text-dark mb-4">We found {{ count($all_module) ?? 'NULL' }} modules to Update</h2>
                @if(!empty($all_module))
                <table class="table mb-4">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-uppercase text-muted">Sr.</th>
                            <th scope="col" class="text-uppercase text-muted">Module Name</th>
                            <th scope="col" class="text-uppercase text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php$counter = 1; @endphp
                        @foreach($all_module as $key => $value)
                         
                        <tr>
                            <td>{{$counter}}</td>
                            <td>{{$value['module_name']}}</td>
                            <td class="text-primary"><a href="{{$update_map_link}}&mode=update&module_name={{$value['module_url']}}" rel="loadpage"> Update</td>
                            @php$counter++;@endphp
                        </tr>

                        @endforeach

                    </tbody>
                </table>
                <p class="text-muted mb-4">
                    When you would update <strong>module</strong> you can additionally update and create new section or action but it affected all user permission of this module even they lose their permissions.
                </p>
                @endif
                <a href="{{$update_map_link}}&mode=create" rel="loadpage" class="btn btn-primary">Create a New Module &rarr;</a>
            </div>
        </div>
    </div>
</div>