<table>
    @foreach ($list->getFields as $field)
		@if ($field->visible)
            <tr>
                <td><strong>{{ $field->label }}:</strong></td>
                <td>{{ $subscriber->getValueByField($field) }}</td>
            </tr>
        @endif
    @endforeach
</table>