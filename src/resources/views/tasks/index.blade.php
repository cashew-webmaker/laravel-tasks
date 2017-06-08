@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>All Accessible Tasks</h1>
        <em>{{Auth::user()->name}}</em>

        <div class="panel-body">
            <table class="table table-striped task-table" id="myTasksTable">

                <h3>My Tasks</h3>
                <!-- Table Headings -->
                <thead>
                <th>Name</th>
                <th>Deadline</th>
                <th>Assigned</th>
                <th>Finished</th>
                <th>Status</th>
                <th>Assignor</th>
                <th>Assignee</th>
                <th>Reviewed</th>
                <th>Actions</th>
                </thead>

                <!-- Table Body -->
                <tbody id="tableBody">
                @foreach ($tasks as $task)
                    <tr>
                        <td class="table-text">
                            <div>{{ $task->name }}</div>
                        </td>
                        <td class="table-text deadline_at">
                            <div>{{ $task->deadline_at }}</div>
                        </td>
                        <td class="table-text">
                            <div>{{\Carbon\Carbon::parse($task->assigned_at)
                                        ->diffForHumans()}}</div>
                        </td>
                        <td class="table-text finished_at">
                            <div>{{$task->finished_at}}</div>
                        </td>
                        <td class="table-text">
                            <div>{{ $task->status }}</div>
                        </td>
                        <td class="table-text">
                            <div>{{$task->assignor->name}}</div>
                        </td>
                        <td>{{$task->assignee->name}}</td>
                        <td class="table-text">
                            <div>{{$task->reviewed_at}}</div>
                        </td>
                        <td><a href="{{route('tasks.edit',$task->id)}}"
                               class="btn btn-info btn-xs">See</a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            {{$tasks->links()}}
        </div>

        <div>
            <form action="{{route('tasks.indexSearch')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="form-group">
                    <label for="search">Search Task Name:</label>
                    <input type="text" id="search" name="search" class="form-control">
                </div>


                <div class="form-group">
                    <input type="submit" value="Search" class="btn btn-primary">
                </div>
            </form>
        </div>


    </div>
@endsection