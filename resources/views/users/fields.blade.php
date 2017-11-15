<!--  Name -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('name', 'Name:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('name', null, [  'placeholder'=>'Enter Partner\'s name', 'class' => 'form-control']) !!}
    </div>
</div>
<!--  username -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('username', 'Username:') !!}</label>
    <div class="col-sm-10">
        {!! Form::text('username', null, [  'class' => 'form-control']) !!}
    </div>
</div>
<!--  Email -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('email', 'email:') !!}</label>
    <div class="col-sm-10">
        {!! Form::email('email', null, [  'class' => 'form-control']) !!}
    </div>
</div>
<!--  password -->
<div class="form-group row">
    <label for="default-input" class="col-sm-2 form-control-label">{!! Form::label('password', 'Password:') !!}</label>
    <div class="col-sm-10">
        {!! Form::password('password',[  'class' => 'form-control']) !!}
    </div>
</div>

<!--  Status -->
<div class="form-group row">
    <label for="default-input"
           class="col-sm-2 form-control-label">{!! Form::label('user_group_id', 'User Group:') !!}</label>
    <div class="col-sm-10">
        @if(Auth::user()->isAdmin())
            {!! Form::select('user_group_id', \App\UserGroup::all()->pluck('group_name','id'), null, [  'class' => 'form-control']) !!}
        @else
            {!! Form::select('user_group_id', \App\UserGroup::where('partner_id',Auth::user()->partner_id)->pluck('group_name','id'), null, [  'class' => 'form-control']) !!}

        @endif
    </div>
</div>
@if(Auth::user()->isAdmin())
    <!--  Status -->
    <div class="form-group row">
        <label for="default-input"
               class="col-sm-2 form-control-label">{!! Form::label('partner_id', 'Partner:') !!}</label>
        <div class="col-sm-10">
            {!! Form::select('user_group_id', \App\Partner::all()->pluck('name','id'), null, [  'class' => 'form-control']) !!}

        </div>
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-sm-8 col-sm-offset-2" id='submit'>

    {!! Form::submit('Save', ['class' => 'btn btn-danger']) !!}
    <a href="{!! route('users.index') !!} " class="btn btn-default"> Cancel</a>
</div>







