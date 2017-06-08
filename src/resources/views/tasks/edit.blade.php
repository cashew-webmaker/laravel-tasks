@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>Task Edit</h1>
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

        @if(Auth::user()->id == $task->assignor->id)
        <h3>Assignor Domain</h3>

        <div id="assignor">
            <form action="{{route('tasks.assignorUpdate',$task->id)}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                {{method_field('PUT')}}

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{$task->name}}">
                </div>

                <div class="form-group">
                    <label for="deadline_at">Deadline:</label>
                    <div class="input-group date dateTimePickerThis">
                        <input type="text" id="deadline_at" name="deadline_at"
                               class="form-control" value="{{$task->deadline_at}}">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="assignee_id">Assignee:</label>
                    <select name="assignee_id" id="assignee_id" class="form-control">
                        <option value="{{$task->assignee->id}}">{{$task->assignee->name}}</option>
                        @foreach($assignees as $assignee)
                            <option value="{{$assignee->id}}">{{$assignee->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="auto_review">
                        <input type="checkbox" id="auto_review" name="auto_review"
                               value="1" {{!empty($task->auto_review) ? "checked" : ""}}>
                        Turn On Auto Review
                    </label>
                </div>


                <div class="form-group">
                    <label for="reviewed">
                        <input type="checkbox" id="reviewed" name="reviewed"
                               value="1" {{!empty($task->reviewed_at) ? "checked" : ""}}>
                        Mark as Reviewed
                    </label>
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>

            </form>
        </div>

        @endif

        <hr>

        {{--SUPPORTING FILES --}}
        <div class="panel panel-default">

            <div class="panel-heading">
                Supporting Files Uploaded
                <button class="pull-right glyphicon glyphicon-plus" id="addSupportingFile"
                      data-toggle="modal" data-target="#myModal"></button>
            </div>

            <!-- Table -->
            <table class="table">
                <thead>
                <tr>
                    <th>Serial</th>
                    <th>File Name</th>
                    <th>Owner</th>
                    <th>Download</th>
                    <th>Delete</th>
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
                        <td>
                            <a href="{{route('taskFiles.destroy',$file->id)}}"
                               class="glyphicon glyphicon-remove-circle"></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        {{-- ASSIGNOR DOMAIN--}}
        <div id="assignor">

            <h3>Assignee Domain</h3>

            <form action="{{route('tasks.assigneeUpdate',$task->id)}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                {{method_field('PUT')}}

                <div class="form-group">
                    <label for="deferred_till">Deferred till:</label>
                    <div class="input-group date dateTimePickerThis">
                        <input type="text" id="deferred_till" name="deferred_till"
                               class="form-control" value="{{$task->deferred_till}}">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea name="notes" id="" cols="30" rows="10" class="form-control">{{$task->notes}}</textarea>
                </div>

                <div class="form-group">
                    <label for="finished">
                        <input type="checkbox" id="finished" name="finished"
                               value="1" {{!empty($task->finished_at) ? "checked" : ""}}>
                        Mark as Finished
                    </label>
                </div>

                <div class="form-group col-xs-6">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form>


        </div>
    </div>


{{-- MODALS --}}
<!-- Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Upload Supporting File</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('taskFiles.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <input type="hidden" id="task_id" name="task_id" class="form-control"
                           value="{{$task->id}}">

                    <div class="form-group">
                        <label for="file">File:</label>
                        <input type="file" id="file" name="file" class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Upload File" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
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

    $('.dateTimePickerThis').datetimepicker();



</script>
@endpush
