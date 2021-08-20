<div class="table-responsive">
    <table id="search_table" class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">ชื่อ</th>
                <th scope="col">นามสกุล</th>
                <th scope="col">แก้ไขข้อมูล</th>
            </tr>
        </thead>
        @for ($i = 0; $i < $members->count(); $i++)
            <tr>
                <th scope="row">
                    {{$members[$i]->fname}}
                </th>
                <th scope="row">
                    {{$members[$i]->lname}}
                </th>
                <th scope="row">
                    <a id="edit-member" href="" data-toggle="modal" data-target="#edit-member-modal"
                        data-member="{{ $members[$i] }}"><i class="fas fa-edit"></i></a>
                    &nbsp;
                    <a id="delete-member" href="#" data-member="{{ $members[$i] }}"><i
                            class="fas fa-trash-alt text-danger"></i></a>
                </th>
            </tr>
            @endfor
    </table>
</div>

{{-- members card footer --}}
<div id="cardFooter" class="card-footer">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            @isset($members)
            {{ $members->links() }}
            @endisset
        </div>
    </div>
</div>
