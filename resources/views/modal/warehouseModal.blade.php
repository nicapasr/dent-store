<!-- add warehouse Modal -->
<div class="modal fade" id="add-warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">เพิ่มคลังของการจัดซื้อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                {{-- <form id="warehouse-form" class="form-horizontal" method="POST" action=""> --}}
                    @csrf
                    <div class="card mb-0">
                        {{-- <div class="card-header">
                            <h2 class="m-0">Edit</h2>
                        </div> --}}
                        <div class="card-body">
                            <div class="form-group">
                                <label class="col-form-label">ชื่อของคลัง</label>
                                <input type="text" name="warehouseName" class="form-control" id="modal-input-warehouseName"
                                    required>
                            </div>
                        </div>
                    </div>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button id="addWarehouse" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit warehouse Modal -->
<div class="modal fade" id="edit-warehouse-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขข้อมูลคลัง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                <form id="edit-warehouse-form" class="form-horizontal">
                    <div class="card mb-0">
                        {{-- <div class="card-header">
                            <h2 class="m-0">Edit</h2>
                        </div> --}}
                        <div class="card-body">
                            <div class="form-group">
                                <label class="col-form-label" for="modal-id-warehouse">ID</label>
                                <input type="text" name="idWarehouse" class="form-control" id="modal-id-warehouse"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="modal-name-warehouse">ชื่อประเภท</label>
                                <input type="text" name="nameWarehouse" class="form-control" id="modal-name-warehouse"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editWarehouse" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
