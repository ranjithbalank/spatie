@props(['status'])

<td class="px-4 py-2 text-m font-semibold whitespace-nowrap">
    @if ($status === 'pending')
        <span class="text-yellow-500">{{ ucfirst($status) }}</span>
    @elseif ($status === 'approved')
        <span class="text-green-600">{{ ucfirst($status) }}</span>
    @elseif ($status === 'rejected')
        <span class="text-red-600">{{ ucfirst($status) }}</span>
    @else
        <span class="text-gray-500">N/A</span>
    @endif
</td>
