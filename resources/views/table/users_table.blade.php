<div class="table-responsive">
    <!-- Projects table -->
    <table id="usersTable" class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">รหัสผู้ใช้</th>
                <th scope="col">ชื่อ</th>
                <th scope="col">นามสกุล</th>
                <th scope="col">เบอร์โทร</th>
                {{-- <th scope="col">สังกัด</th> --}}
                <th scope="col">ประเภท</th>
                <th scope="col">แก้ไข</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            @foreach ($users as $user)

            <tr>
                <th scope="row">
                    {{$user->username}}
                </th>
                <th scope="row">
                    {{$user->first_name}}
                </th>
                <th scope="row">
                    {{$user->last_name}}
                </th>
                <th scope="row">
                    {{$user->phone}}
                </th>
                {{-- <th scope="row">
                    {{$user->getDepartment->dep_name}}
                </th> --}}
                <th scope="row">
                    {{$user->getPermission->permission_name}}
                </th>
                <th scope="row">
                    <a id="edit-user" href="" data-toggle="modal" data-target="#edit-user-modal"
                    data-user-name="{{$user}}"><i class="fas fa-edit"></i></a>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{-- users card footer --}}
<div id="cardFooter" class="card-footer">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            @isset($users)
            {{ $users->links() }}
            @endisset
        </div>
    </div>
</div>
