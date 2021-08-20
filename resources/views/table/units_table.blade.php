<div class="table-responsive">
    <table id="" class="table align-items-center table-flush">
        <thead class="thead-light text-center">
            <tr>
                <th scope="col">ชื่อหน่วยวัสดุ</th>
                <th scope="col">แก้ไข/ลบ</th>
            </tr>
        </thead>
        @foreach ($units as $item)
        <tr class="text-center">
            <th scope="row">
                {{$item->unit_name}}
            </th>
            <th scope="row">
                <a id="edit-unit" href="#" data-toggle="modal" data-target="#edit-unit-modal" data-unit="{{ $item }}"><i
                        class="fas fa-edit"></i></a>
                &nbsp;
                <a id="delete-unit" href="#" data-unit="{{ $item }}"><i class="fas fa-trash-alt text-danger"></i></a>
            </th>
        </tr>
        @endforeach
    </table>
</div>

{{-- units card footer --}}
<div id="cardFooter" class="card-footer">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            {{ $units->links() }}
        </div>
    </div>
</div>
