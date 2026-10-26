@if ($sort == $field)
    @if ($direction == 'asc')
        <i class="fas fa-arrow-up ml-1 text-sm"></i>
    @elseif ($direction == 'desc')
        <i class="fas fa-arrow-down ml-1 text-sm"></i>
    @endif
@endif
