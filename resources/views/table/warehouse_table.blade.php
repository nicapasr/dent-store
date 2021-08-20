<div class="table-responsive">
    <table id="search_table" class="table align-items-center table-flush">
        <thead class="thead-light text-center">
            <tr>
                <th scope="col">ชื่อคลัง</th>
                <th scope="col">แก้ไข/ลบ</th>
            </tr>
        </thead>
        @for ($i = 0; $i < $warehouse->count(); $i++)
            <tr class="text-center">
                <th scope="row">
                    {{$warehouse[$i]->warehouse_name}}
                </th>
                <th scope="row">
                    <a id="edit-warehouse" href="" data-toggle="modal" data-target="#edit-warehouse-modal"
                        data-warehouse="{{ $warehouse[$i] }}"><i class="fas fa-edit"></i></a>
                    &nbsp;
                    <a id="delete-warehouse" href="#" data-warehouse="{{ $warehouse[$i] }}"><i
                            class="fas fa-trash-alt text-danger"></i></a>
                </th>
            </tr>
            @endfor
    </table>
</div>

{{-- wearhouse card footer --}}
<div id="cardFooter" class="card-footer">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            @isset($wearhouses)
            {{ $wearhouses->links() }}
            @endisset
        </div>
    </div>
</div>
