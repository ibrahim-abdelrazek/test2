<div>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li class="text-danger"><b>{{$error}}</b></li>
                @endforeach

            </ul>
        </div>
    @endif
</div>

<!--  Name -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('name', 'Name:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('name', null, [  'placeholder'=>'Enter Partner\'s name', 'class' => 'form-control']) !!}
    </div>
</div>
<!-- Logo -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">
        {!! Form::label('logo', 'Logo:') !!}
    </label>

    <div class="col-sm-10">
        {!! Form::file('logo',null,  [  'class' => 'form-control']) !!}
    </div>

</div>

<!--  location -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('location', 'Location:') !!}</label>
    <div class="col-sm-10">
        {!! Form::select('location', ['Abu Dhabi' , 'Dubai' , 'Sharjah' , 'Ajman' ,'Umm Al Quwain','Ras Al Khaimah' ,'Fujairah' ] , null, [  'class' => 'form-control' , 'required']) !!}
    </div>
</div>

<!--  partner type -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('partner_type_id', 'Partner Type:') !!}</label>
    <div class="col-sm-10">
        @if(\App\PartnerType::count() > 0)
            {!! Form::select('partner_type_id',  App\PartnerType::pluck('name', 'id') , null, ['class' => 'form-control' , 'required']) !!}
        @else
            <p>You don't have added Partner Types yet, Please <a href="{{route('partnertypes.index')}}"><b class="label-danger">Add
                        new Partner Types</b></a></p>
        @endif
    </div>
</div>

<!--  username -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('username', 'Username:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('username', null, [  'class' => 'form-control' , 'required']) !!}
    </div>
</div>


<!-- Phone -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('phone', 'Phone:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('phone', null, [  'class' => 'form-control' , 'required']) !!}
    </div>
</div>
<!-- FAX -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('fax', 'FAX:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('fax', null, [  'class' => 'form-control' , 'required']) !!}
    </div>
</div>

<!-- Commission -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('commission', 'commission Percent:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('commission', null, [  'placeholder'=>'5', 'class' => 'form-control' , 'required']) !!}
    </div>
</div>

<!--  email -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('email', 'Email:') !!}</label>
    <div class="col-sm-10">
        {!! Form::email('email', null, [  'class' => 'form-control' , 'required']) !!}
    </div>
</div>
<!--  password -->

<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('password', 'Password:') !!}</label>
    <div class="col-sm-10">
        @if(!isset($partner))
        {!! Form::password('password', ['class' => 'form-control' , 'required']) !!}
        <br>
        <b class="text-warning"> Your Password must contain at least 6 characters as (Uppercase and Lowercase characters and Numbers and Special characters). </b>
        @else
            {!! Form::password('password', ['class' => 'form-control'] ) !!}
            <br>
            <b class="text-warning"> Your Password must contain at least 6 characters as (Uppercase and Lowercase characters and Numbers and Special characters). </b>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('password_confirmation', 'Confirm Password:') !!}</label>
    <div class="col-sm-10">
        {!! Form::password('password_confirmation', ['class' => 'form-control'] ) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-8 col-sm-offset-2" id='submit'>

    {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
    <a href="{!! route('partners.index') !!}" class="btn btn-default">Cancel</a>

</div>








