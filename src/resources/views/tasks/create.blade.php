@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Task Create</h1>
        <hr style="height: 1px; background-color:black;" />

        <form action="{{route('tasks.store')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}

            <h4>Options</h4>
            <div class="form-group">
                <label for="recurring_task">
                    <input type="checkbox" id="recurring_task" name="recurring_task"
                           value="1" {{!empty($task->auto_review) ? "checked" : ""}}>
                    Recurring Task
                </label>
            </div>

            <div class="hidden" id="recurringTasksDiv">
                <h4 >Recurring Type Options</h4>
                <div class="form-group">
                    <label for="recurring_type">Recurring Type:</label>
                    <select name="recurring_type" id="recurring_type">
                        @foreach(\App\RecurringTask::$recurring_type as $recurring_type)
                            <option value="{{$recurring_type}}">{{$recurring_type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="day_of_week">Day of Week:</label>
                    <input type="text" id="day_of_week" name="day_of_week" class="form-control" value="{{old('day_of_week')}}">
                </div>
                <div class="form-group">
                    <label for="day_of_month">Day Of Month:</label>
                    <input type="text" id="day_of_month" name="day_of_month" class="form-control" value="{{old('day_of_month')}}">
                </div>
                <div class="form-group">
                    <label for="month_of_year">Month of Year:</label>
                    <input type="text" id="month_of_year" name="month_of_year" class="form-control" value="{{old('month_of_year')}}">
                </div>
                <hr>
            </div>
            
            <div class="form-group">
                <label for="name">Task Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}">
            </div>

            <div class="form-group">
                <label for="assigned_at">Assigned:</label>
                <div class="input-group date" id="datetimepicker2">
                    <input type="text" id="assigned_at" name="assigned_at"
                           class="form-control" value="{{\Carbon\Carbon::now()->toDateTimeString()}}">
                    <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
                </div>
            </div>

            <div class="form-group">
                <label for="deadline_at">Deadline:</label>
                <div class="input-group date" id="datetimepicker1">
                    <input type="text" id="deadline_at" name="deadline_at"
                           class="form-control" value="{{\Carbon\Carbon::now()->toDateTimeString()}}">
                    <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
                </div>
            </div>

            <div class="form-group">
                <label for="assignee_id">Assignee:</label>
                <select name="assignee_id" id="assignee_id" class="form-control">
                    @foreach(\App\User::where('type','Staff')->get() as $assignee)
                    <option value="{{$assignee->id}}">{{$assignee->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="observers">Observers:</label>
                <select name="observers[]" id="observers" class="form-control" multiple="multiple">
                    @foreach(\App\User::where('type','Staff')->get() as $observer)
                        <option value="{{$observer->id}}">{{$observer->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="auto_review">
                    <input type="checkbox" id="auto_review" name="auto_review"
                           value="1" {{!empty($task->auto_review) ? "checked" : ""}}>
                    Turn On Task Auto Review (For Low-risk tasks)
                </label>
            </div>

            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" id="notes" cols="30" rows="5" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Create Task" class="btn btn-primary">
            </div>
        </form>

    </div>

@endsection

@push('scripts')

<script src="/js/tinymce/tinymce.min.js"></script>
<style>
    div.mce-fullscreen {
        z-index: 1050;
    }
</style>
<script>
    $(document).ready(function () {
        tinymce.init({
            selector:'textarea',
            plugins: 'autosave,advlist,charmap,codesample,emoticons,image,imagetools,lists,fullscreen,hr,link,print,searchreplace,table,template,textcolor,autoresize',
            menu : {
                file   : {title : 'File'  , items : 'newdocument print'},
                edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall searchreplace'},
                insert : {title : 'Insert', items : 'image hr link template'},
                view   : {title : 'View'  , items : 'visualaid fullscreen'},
                format : {title : 'Format', items : 'formats | removeformat'},
                table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
                tools  : {title : 'Tools' , items : 'spellchecker code'},
                newmenu: {title : 'New Menu', items : 'newmenuitem'}
            },
            toolbar: "undo redo | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent codesample",
        });
    });
</script>

<script>
    $('select').select2({
        width: '100%'
    });

    $('#datetimepicker1').datetimepicker();
    $('#datetimepicker2').datetimepicker();
</script>

{{-- Recurring Task Related --}}
<script>
    var isRecurring = $("#recurring_task").attr('checked');

    if(isRecurring) {
        $("#recurringTasksDiv").removeClass('hidden');
    }

    $("#recurring_task").change(function () {
        $("#recurringTasksDiv").toggleClass('hidden');
    });

</script>
@endpush