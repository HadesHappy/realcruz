@if (strpos($operator, 'tag') !== false)
    <div class="row">
        <div class="col-md-6 operator-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][operator]',
                'label' => '',
                'value' => (isset($operator) ? $operator : ''),
                'options' => Acelle\Model\Segment::tagOperators()
            ])
        </div>
        <div class="col-md-6 value-col">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'conditions['.$index.'][value]',
                'label' => '',
                'value' => (isset($value) ? $value : '')
            ])
        </div>
    </div>
@elseif (strpos($operator, 'last_open_email') !== false)
    <div class="row">
        <div class="col-md-6 operator-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][operator]',
                'label' => '',
                'value' => (isset($operator) ? $operator : ''),
                'options' => Acelle\Model\Segment::openMailOperators()
            ])
        </div>
        <div class="col-md-6 value-col">
            @include('helpers.form_control', [
                'type' => 'number',
                'name' => 'conditions['.$index.'][value]',
                'label' => '',
                'value' => (isset($value) ? $value : '')
            ])
        </div>
    </div>
@elseif (strpos($operator, 'last_link_click') !== false)
    <div class="row">
        <div class="col-md-6 operator-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][operator]',
                'label' => '',
                'value' => (isset($operator) ? $operator : ''),
                'options' => Acelle\Model\Segment::clickLinkOperators()
            ])
        </div>
        <div class="col-md-6 value-col">
            @include('helpers.form_control', [
                'type' => 'number',
                'name' => 'conditions['.$index.'][value]',
                'label' => '',
                'value' => (isset($value) ? $value : '')
            ])
        </div>
    </div>
@elseif (strpos($operator, 'verification') !== false)
    <div class="row">
        <div class="col-md-6 operator-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][operator]',
                'label' => '',
                'value' => (isset($operator) ? $operator : ''),
                'options' => Acelle\Model\Segment::verificationOperators()
            ])
        </div>
        <div class="col-md-6 value-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][value]',
                'label' => '',
                'value' => (isset($value) ? $value : ''),
                'options' => Acelle\Model\Subscriber::getVerificationStates()
            ])
        </div>
    </div>
@elseif (strpos($operator, 'created_date') !== false)
    @include('segments.operator.created_date')
@else
    <div class="row">
        <div class="col-md-6 operator-col">
            @include('helpers.form_control', [
                'type' => 'select',
                'name' => 'conditions['.$index.'][operator]',
                'label' => '',
                'value' => (isset($operator) ? $operator : ''),
                'options' => Acelle\Model\Segment::operators()
            ])
        </div>
        <div class="col-md-6 value-col">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'conditions['.$index.'][value]',
                'label' => '',
                'value' => (isset($value) ? $value : '')
            ])
        </div>
    </div>
@endif
