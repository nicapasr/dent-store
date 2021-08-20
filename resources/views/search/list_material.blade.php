@isset($materials)
    <ul class="list-group" style="display: block; position: absolute; z-index: 1">
        @foreach ($materials as $row)
            <li class="list-group-item">{{ $row->m_code }} {{ $row->m_name }}</li>
            <input type="hidden" name="material_id" value="{{$row->m_code}}">
        @endforeach
    </ul>
@endisset
