<div class="table-responsive">
    <table id="usersTable" class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">รหัสวัสดุ</th>
                <th scope="col">ชื่อวัสดุ</th>
                <th scope="col">หน่วยวัสดุ</th>
                <th class="text-center" scope="col">วันหมดอายุ</th>
                <th class="text-center" scope="col">แก้ไขข้อมูล</th>
            </tr>
        </thead>
        <tbody id="materialsBody">
            @foreach ($materials as $item)
            <tr>
                <th scope="row">
                    {{$item->m_code}}
                </th>
                <th scope="row">
                    {{$item->m_name}}
                </th>
                <th scope="row">
                    {{$item->materialUnit->unit_name}}
                </th>

                @if ($item->m_exp_date == null)
                <th class="text-center" scope="row" style="color: orange">ไม่มีวันหมดอายุ</th>
                @else
                <th class="text-center" scope="row" style="color: red">
                    มีวันหมดอายุ
                </th>
                @endif
                <th class="text-center" scope="row">
                    <a id="edit-material" href="" data-toggle="modal" data-target="#edit-material-modal"
                        data-material="{{ $item }}"><i class="fas fa-edit"></i></a>
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
            {{ $materials->links() }}
        </div>
    </div>
</div>
