<!-- add unit Modal -->
<div class="modal fade" id="add-unit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">เพิ่มหน่วยวัสดุ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                {{-- <form id="unit-form" class="form-horizontal" method="POST" action=""> --}}
                @csrf
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-form-label">ชื่อของหน่วยวัสดุ</label>
                            <input type="text" name="unitName" class="form-control" id="modal-input-unitName" required>
                        </div>
                    </div>
                </div>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button id="addUnit" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit unit Modal -->
<div class="modal fade" id="edit-unit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขหน่วยวัสดุ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                <form id="edit-unit-form" class="form-horizontal" method="POST" action="">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="col-form-label" for="modal-id-unit">ID</label>
                                <input type="text" name="idUnit" class="form-control" id="modal-id-unit" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="modal-name-unit">ชื่อประเภท</label>
                                <input type="text" name="unit_name" class="form-control" id="modal-name-unit" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editUnit" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
