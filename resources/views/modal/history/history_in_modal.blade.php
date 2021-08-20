<div class="modal fade" id="history_in_modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขรายการนำเข้าวัสดุ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.history.in.update')}}" method="post">
                <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                    @csrf
                    <div class="form-group">
                        <label for="input_m_code">รหัสวัสดุ:</label>
                        <input type="text" id="input_m_code" name="m_code" class="form-control"
                            value="{{old('m_code')}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="input_m_name">วัสดุ:</label>
                        <input type="text" id="input_m_name" name="m_name" class="form-control"
                            value="{{old('m_name')}}" readonly>
                        <input type="hidden" id="input_id_stock_in" name="id_stock_in" value="{{old('id_stock_in')}}">
                    </div>
                    <div class="form-group">
                        <label for="input_value">จำนวน:</label>
                        <input type="number" id="input_value" name="value"
                            class="form-control @error('value') is-invalid @enderror"
                            value="{{old('value')}}">

                        @error('value')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="input_m_unit">หน่วยวัสดุ:</label>
                        <input type="text" id="input_m_unit" name="m_unit" class="form-control"
                            value="{{old('m_unit')}}" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
