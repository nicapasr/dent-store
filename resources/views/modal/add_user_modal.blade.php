<!-- User Modal -->
<div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="add-user-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มผู้ใช้งาน</h5>
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
                            <input type="text" name="username" class="form-control" id="modal-input-userName" required>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-password">Password</label>
                            <input type="password" name="password" class="form-control" id="modal-input-password" required>
                        </div>
                        <!-- id -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-firstName">ชื่อ</label>
                            <input type="text" name="firstName" class="form-control" id="modal-input-firstName" required>
                        </div>
                        <!-- /id -->
                        <!-- name -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-lastName">นามสกุล</label>
                            <input type="text" name="lastName" class="form-control" id="modal-input-lastName" required>
                        </div>
                        <!-- /name -->
                        <!-- description -->
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-phone">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" id="modal-input-phone" required>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="modal-input-permission">ประเภท</label>
                            <select class="form-control" id="modal-input-permission" name="permission" required>
                                <option value="1">Admin (เจ้าหน้าที่คลัง)</option>
                                <option value="2">Board (ผู้บริหาร)</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button id="btn_add_user_submit" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<!-- /User Modal -->
