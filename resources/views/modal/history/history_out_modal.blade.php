<div class="modal fade" id="stock_out_list_modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">แก้ไขรายการเบิกวัสดุ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.history.out.update')}}" method="post">
                <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                    @csrf
                    <div class="form-group">
                        <label for="input_m_name">วัสดุ:</label>
                        <input type="text" id="input_m_name" name="m_name" class="form-control"
                            value="{{old('m_name')}}" readonly>
                        <input type="hidden" id="input_id_stock_out" name="id_stock_out" value="{{old('id_stock_out')}}">
                    </div>
                    <div class="form-group">
                        <label for="input_m_balance">คงเหลือ:</label>
                        <input type="text" id="input_m_balance" name="m_balance" class="form-control"
                            value="{{old('m_balance')}}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="input_m_name">ห้อง:</label>
                        <input type="number" id="input_room" name="room" class="form-control @error('room') is-invalid @enderror"
                            value="{{old('room')}}">

                        @error('room')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="input_m_name">จำนวน:</label>
                        <input type="number" id="input_width_draw_value" name="width_draw_value"
                            class="form-control @error('width_draw_value') is-invalid @enderror"
                            value="{{old('width_draw_value')}}">

                        @error('width_draw_value')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
