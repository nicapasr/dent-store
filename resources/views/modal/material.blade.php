<!-- User Modal -->
<div class="modal fade" id="edit-material-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขวัสดุ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                {{-- <form id="edit_material_form" class="form-horizontal" method="POST" action="{{ route('material.edit') }}">
                @csrf --}}
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="col-form-label">รหัสวัสดุ</label>
                            <input type="text" name="m_code" class="form-control" id="modal-input-materialCode" readonly>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">ชื่อวัสดุ</label>
                            <input type="text" name="m_name" class="form-control" id="modal-input-materialName"
                                required>
                        </div>
                        {{-- <div class="form-group">
                                <label class="col-form-label">ประเภทวัสดุ</label>
                                <select class="form-control" id="modal-input-materialType" disabled>
                                    <option value="1">วัสดุสิ้นเปลือง</option>
                                    <option value="2">วัสดุมีวันหมดอายุ</option>
                                    <option value="3">เครื่องมือ</option>
                                </select>
                            </div> --}}
                        <div class="form-group">
                            <label class="col-form-label">หน่วยวัสดุ</label>
                            <select class="form-control" id="modal-input-materialUnit" name="m_unit">
                                @for ($i = 0; $i < $unitAll->count(); $i++)
                                    <option value="{{$unitAll[$i]->id_unit}}">{{$unitAll[$i]->unit_name}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                {{-- </form> --}}
            </div>
            <div class="modal-footer">
                <button id="editMaterial" type="submit" class="btn btn-primary">บันทึก</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>
<!-- /User Modal -->
