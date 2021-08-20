<!-- User Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขผู้ใช้งาน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                {{-- <form id="user-form" class="form-horizontal" method="POST" action=""> --}}
                @csrf
                <div class="card mb-0">
                    {{-- <div class="card-header">
                            <h2 class="m-0">Edit</h2>
                        </div> --}}
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-userName">Username</label>
                            <input type="text" name="userName" class="form-control" id="modal-input-edit-userName" required>
                        </div>
                        <!-- id -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-firstName">ชื่อ</label>
                            <input type="text" name="first_name" class="form-control" id="modal-input-edit-firstName" required>
                        </div>
                        <!-- /id -->
                        <!-- name -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-lastName">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" id="modal-input-edit-lastName" required>
                        </div>
                        <!-- /name -->
                        <!-- description -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-phone">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" id="modal-input-edit-phone" required>
                        </div>
                        {{-- department --}}
                        {{-- <div class="form-group">
                            <label class="col-form-label" for="modal-input-department">สังกัด</label>
                            <input type="text" name="department" class="form-control" id="modal-input-department"
                                required>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-description">ประเภท</label>
                            {{-- <input type="text" name="permission" class="form-control" id="modal-input-permission"
                                    required> --}}
                            <select class="form-control" id="modal-input-edit-permission" required>
                                <option value="1">Admin (เจ้าหน้าที่คลัง)</option>
                                <option value="2">Board (ผู้บริหาร)</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button id="btn_edit_user_submit" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<!-- /User Modal -->
