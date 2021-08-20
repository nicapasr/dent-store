{{-- edit member --}}
<div class="modal fade" id="edit-member-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขข้อมูลสมาชิก</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                <form id="edit-member-form" class="form-horizontal" method="POST" action="">
                    @csrf
                    <div class="card mb-0">
                        {{-- <div class="card-header">
                            <h2 class="m-0">Edit</h2>
                        </div> --}}
                        <div class="card-body">
                            {{-- <div class="form-group">
                                <label class="col-form-label" for="modal-id-warehouse">ID</label>
                                <input type="text" name="idWarehouse" class="form-control" id="modal-id-warehouse"
                                    required>
                            </div> --}}
                            <div class="form-group">
                                <label class="col-form-label" for="modal-id-member">รหัสสมาชิก</label>
                                <input type="text" name="idMember" class="form-control" id="modal-id-member"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="modal-fname-member">ชื่อ</label>
                                <input type="text" name="fNameMember" class="form-control" id="modal-fname-member"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="modal-lname-member">นามสกุล</label>
                                <input type="text" name="lNameMember" class="form-control" id="modal-lname-member"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editMember" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

{{-- add member --}}
<div class="modal fade" id="add-member-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">เพิ่มสมาชิก</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                <form id="add-member-form" class="form-horizontal" method="POST" action="">
                    @csrf
                    <div class="card mb-0">
                        {{-- <div class="card-header">
                            <h2 class="m-0">Edit</h2>
                        </div> --}}
                        <div class="card-body">
                            {{-- <div class="form-group">
                                <label class="col-form-label" for="modal-id-warehouse">ID</label>
                                <input type="text" name="idWarehouse" class="form-control" id="modal-id-warehouse"
                                    required>
                            </div> --}}
                            <div class="form-group">
                                <label class="col-form-label" for="add-fname-member">ชื่อ</label>
                                <input type="text" name="fNameMember" class="form-control" id="add-fname-member"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="modal-lname-member">นามสกุล</label>
                                <input type="text" name="lNameMember" class="form-control" id="add-lname-member"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="addMember" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

