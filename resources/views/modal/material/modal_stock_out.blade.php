<div class="modal fade" id="material_add_modal" tabindex="-1" role="dialog" aria-labelledby="materail-add-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เบิกวัสดุ</h5>
                {{-- <h5 class="modal-title px-2" id="modal_material_name"></h5> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_stock_out" action="{{route('admin.material.stock.out.widthdraw')}}" method="post">
                @csrf
                <div class="modal-body pt-0 pb-0" id="attachment-body-content">
                    <div class="row">
                        <div class="col-12">
                            <div id="div_exp_date" class="form-group d-none">
                                <label for="input_m_exp_date">วันหมดอายุ:</label>
                                <input id="input_m_exp_date" name="exp_date" type="text" class="form-control" readonly
                                    value="{{ old('exp_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>รหัสวัสดุ:</label>
                                <input id="input_m_code" name="m_code" type="text" class="form-control"
                                    value="{{old('m_code')}}" readonly>

                                <input type="hidden" id="input_id" name="id" value="{{old('id')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="input_m_name">วัสดุ:</label>
                                <input id="input_m_name" name="m_name" type="text" class="form-control"
                                    value="{{old('m_name')}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="input_balance">จำนวนคงเหลือ:</label>
                                <input id="input_balance" name="m_balance" type="number" class="form-control"
                                    value="{{old('m_balance')}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="input_member">ผู้ขอเบิก:</label>
                                {{-- <select id="input_member" class="form-control select" name="member" placeholder="Type to search..." autocomplete="off">
                                </select> --}}
                                <input id="input_member" name="member_text" type="text" class="form-control select @error('member') is-invalid
                                @enderror" placeholder="พิมพ์เพื่อค้นหา" autocomplete="off"
                                    value="{{old('member_text')}}" onkeyup="clearErrors('input_member')">
                                <input type="hidden" name="member" value="{{old('member')}}">

                                @error('member')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('input_room', 'ห้องที่เบิก:') !!}
                                {!! Form::number('room', '', [
                                'id' => 'input_room',
                                'class' => 'form-control ' . ($errors->has('room') ? 'is-invalid' : null),
                                'min' => '0',
                                'onkeyup' => 'clearErrors("input_room")']) !!}

                                @error('room')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                {!! Form::label('input_width_draw_value', 'จำนวนที่เบิก:') !!}
                                {!! Form::number('width_draw_value', '', [
                                'id' => 'input_width_draw_value',
                                'class' => 'form-control ' . ($errors->has('width_draw_value') ? 'is-invalid' : null),
                                'min' => '1',
                                'onkeyup' => 'clearErrors("input_width_draw_value")']) !!}
                                {{-- <input id="input_width_draw_value" name="width_draw_value" type="number" class="form-control @error('width_draw_value') is-invalid
                                    @enderror" value="{{old('width_draw_value')}}"
                                onkeyup=""> --}}

                                @error('width_draw_value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="bt_submit" type="submit" class="btn btn-success">เบิก</button>
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script type="application/javascript" src="{{asset('assets/js/components/autocomplete/bootstrap-autocomplete.js')}}">
</script>
