<div class="row">
    @foreach ($materials as $item)
    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
        <div class="card">
            <div class="card-body" style="height: 150px">
                <div class="row">
                    <div class="col-6">
                        @php
                        $list = $item->m_code.'|materialstore@kku';
                        @endphp
                        {!! QrCode::size(100)->generate($list) !!}
                    </div>
                    <div class="col-6">
                        <h4>{{ $item->m_code }}</h4>
                        <div class="my-2"></div>
                        <p class="text-gray"
                            style="display: -webkit-box; overflow : hidden; text-overflow: ellipsis; -webkit-line-clamp: 3; -webkit-box-orient: vertical; ">
                            {{ $item->m_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="card-footer py-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            {!! $materials->links() !!}
        </div>
        <div class="col"></div>
    </div>
</div>
