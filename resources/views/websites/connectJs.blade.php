@include('forms.frontend.popupJs')

@foreach($website->connectedForms()->published()->get() as $form)
    @include('forms.frontend.popup', [
        'form' => $form,
    ])
@endforeach