@extends('layouts.pdf')

@section('content')

    <div class="text-center">
        <h1>Accountability Breakdown</h1>
    </div>

    <div class="container">
        
        <div class="panel panel-default">
        	<div class="panel-body">
                <b>Last 30 Days</b>: Last 30 Days
                <br>
                <b>Printed On:</b> {{\Carbon\Carbon::now()}}
        	</div>
        </div>

        <h2>Summary</h2>
        
        <table class="table table-hover">
        	<thead>
        		<tr>
        			<th>#</th>
                    <th>Name</th>
                    <th>Number of Breakdowns (Team Tasks)</th>
                    <th>Number of Breakdowns (Self Tasks)</th>
        		</tr>
        	</thead>
        	<tbody>
        		@foreach($breakdownSummary as $name => $breakdown)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$name}}</td>
                        <td>{{$breakdown['team']}}</td>
                        <td>{{$breakdown['self']}}</td>
                    </tr>
                @endforeach
        	</tbody>
        </table>
        
        <h2>Details</h2>
        
        <table class="table table-striped">
        	<thead>
        		<tr>
        			<th>#</th>
                    <th>Assignor</th>
                    <th>Assignee</th>
                    <th>Task</th>
                    <th>When</th>
        		</tr>
        	</thead>
        	<tbody>
            @foreach($breakdowns as $breakdown)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$breakdown->assignor}}</td>
                    <td>{{$breakdown->assignee}}</td>
                    <td>{{$breakdown->task}}</td>
                    <td>{{$breakdown->created_at}}</td>
                </tr>
            @endforeach
        	</tbody>
        </table>
    </div>
@endsection
