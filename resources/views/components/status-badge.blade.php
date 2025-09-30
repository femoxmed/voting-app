@props(['status'])

@php
$badgeClass = match (strtolower($status)) {
    'active' => 'bg-success',
    'upcoming' => 'bg-info',
    'pending' => 'bg-warning text-dark',
    'completed' => 'bg-secondary',
    'cancelled' => 'bg-danger',
    default => 'bg-light text-dark',
};
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $badgeClass]) }}>
    {{ ucfirst($status) }}
</span>
