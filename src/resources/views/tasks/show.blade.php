@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Task Show</h1>
        <hr style="height: 1px; background-color:black;" />

        <div class="panel panel-default">
            <table class="table table-hover table-striped">
                <tbody>
                <tr>
                    <th>Task Name</th>
                    <td>{{$task->name}}</td>
                    <th>Assignor</th>
                    <td>{{$task->assignor->name}}</td>
                    <th>Assignee</th>
                    <td>{{$task->assignee->name}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{$task->name}}">
        </div>

        <div class="form-group">
            <label for="deadline_at">Deadline:</label>
            <div class="input-group date" id="datetimepicker1">
                <input type="text" id="deadline_at" name="deadline_at"
                       class="form-control" value="{{$task->deadline_at}}">
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
            </div>
        </div>

        <div class="form-group">
            <label for="observers">Observers:</label>
            <select name="observers[]" id="observers" class="form-control" multiple="multiple" style="width: 100%;">
                @foreach($task->users as $observer)
                    <option value="{{$observer->id}}" selected>{{$observer->name}}</option>
                @endforeach
            </select>
        </div>

        <hr>

        {{--SUPPORTING FILES --}}
        <div class="panel panel-default">

            <div class="panel-heading">
                Supporting Files Uploaded
            </div>

            <!-- Table -->
            <table class="table">
                <thead>
                <tr>
                    <th>Serial</th>
                    <th>File Name</th>
                    <th>Owner</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                @foreach($task->files as $file)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{basename($file->path)}}</td>
                        <td>{{$file->owner->name}}</td>
                        <td><a href="{{route('taskFiles.show',$file->id)}}"
                               class="glyphicon glyphicon-arrow-down"></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea name="notes" id="" cols="30" rows="10" class="form-control" readonly>{{$task->notes}}</textarea>
        </div>

        <div class="form-group">
            <label for="finished">
                <input type="checkbox" id="finished" name="finished"
                       value="1" {{!empty($task->finished_at) ? "checked" : ""}}>
                Mark as Finished
            </label>
        </div>

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
        selectOnClose:true,
        width: '100%'

    });

    $('#datetimepicker1').datetimepicker();


</script>
@endpush