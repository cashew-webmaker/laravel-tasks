<?php

namespace Cashewdigital\Http\Controllers;

use App\Notifications\TaskHorse\TaskFinishedNotif;
use App\Notifications\TaskHorse\TaskFinishedObserverNotif;
use App\Repositories\TaskRepository;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = \Auth::user()->id;
        $tasks = Task::where('assignee_id', $me)
            ->orWhere('assignor_id', $me)
            ->orWhereHas('users', function ($query) use ($me) {
                $query->where('user_id', $me);
            })->orderBy('deadline_at','desc')->paginate();

        return view('tasks.index',compact('tasks'));

    }

    public function indexSearch()
    {
        $search = $_POST['search'] ?? null;

        $searchWords = explode(' ', $search);

        $me = \Auth::user()->id;


        $tasks = Task::with('assignor', 'assignee')
            ->where(function ($query) use ($me) {
                $query->where('assignee_id', $me)
                    ->orWhere('assignor_id', $me)
                    ->orWhereHas('users', function ($query) use ($me) {
                        $query->where('user_id', \Auth::user()->id);
                    });
            })
            ->where(function ($query) use ($searchWords) {

                foreach ($searchWords as $searchWord) {

                    $query->orWhere('name', 'like', "%$searchWord%");
                }
            })
            ->paginate();

        return view('tasks.index',compact('tasks'));
    }

    public function originalIndexSearch()
    {
        $search = $_POST['search'] ?? null;

        $searchWords = explode(' ', $search);

        $me = \Auth::user()->id;

        $tasks = Task::with('assignor', 'assignee')
            ->where('assignee_id', $me)
            ->orWhere('assignor_id', $me)
            ->where(function ($query) use ($searchWords) {

                foreach ($searchWords as $searchWord) {

                    $query->orWhere('name', 'like', "%$searchWord%");
                }
            })
            ->paginate();

        $observedTasks = Task::with('assignor','assignee')
            ->whereHas('users', function ($query) use ($me) {
                $query->where('user_id', \Auth::user()->id);
            })
            ->where(function ($query) use ($searchWords) {

                foreach ($searchWords as $searchWord) {

                    $query->orWhere('name', 'like', "%$searchWord%");

                }
            })->paginate();

        return view('tasks.index',compact('tasks','observedTasks'));

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $task = Task::create([
            'name' => $input['name'],
            'assignor_id' => \Auth::user()->id,
            'assignee_id' => $input['assignee_id'],
            'status' => 'Active',
            'deadline_at' => $input['deadline_at'],
            'assigned_at' => Carbon::now(),
            'notes' => $input['notes'],
            'auto_review' => empty($input['auto_review']) ? 0 : 1,
        ]);


        if (!empty($input['observers'])) {
            $observers = $input['observers'];

            foreach ($observers as $observer) {
                $task->users()->attach($observer, ['role' => 'Observer']);
            }
        }

        (new TaskRepository($task))->informNewTask();

        // Store Recurring Task
        if (!empty($request->recurring_task)) {
            $request->task = $task;
            (new RecurringTasksController())->store($request);
        }


        return redirect()->route('tasks.edit', $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        $this->authorize('view', $task);

        return view('tasks.show',compact('task'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        $this->authorize('view', $task);

        $assignees = User::where('type', 'Staff')->get();

        return view('tasks.edit', compact('task','assignees'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assigneeUpdate(Request $request, $id)
    {
        $task = Task::find($id);
        $input = $request->all();

        $oldTaskStatus = $task->status;

        /** Validation*/
        $this->authorize('assigneeUpdate', $task);

        if (!empty($task->reviewed_at) &&
            \Auth::user()->id != $task->assignor->id) {
            return redirect()->back()->with('warning', 'Reviewed tasks can no longer be changed');
        }

        /** Update the task*/
        $task->notes = $input['notes'];
        $task->deferred_till = empty($input['deferred_till']) ? null : $input['deferred_till'];

        /** Checkbox Validation for finished */
        if (!empty($input['finished'])) {
            $task->finished_at = Carbon::now();
            $task->status = 'Finished';

            /** Auto mark as reviewed if the assignor is also the assignee OR if auto_review flag is on */
            if ($task->assignor->id == $task->assignee->id || $task->auto_review == 1) {
                $task->reviewed_at = Carbon::now();
                $task->status = 'Reviewed';
            }

            $task->save();

        } else {
            /** Keep the task active. */
            $task->reviewed_at = null;
            $task->finished_at = null;
            $task->status = 'Active';
            $task->save();
        }

        $newTaskStatus = $task->status;

        /** Run notifications depending on taskStatusChange */
        if ($oldTaskStatus == 'Active' AND $newTaskStatus == 'Finished') {
            (new TaskRepository($task))->informTaskFinished();
        } elseif ($oldTaskStatus == 'Finished' AND $newTaskStatus == 'Active') {
            (new TaskRepository($task))->informTaskReopened();
        } elseif ($oldTaskStatus == 'Reviewed' AND $newTaskStatus == 'Active') {
            (new TaskRepository($task))->informTaskReopened();
        }

        return redirect()->home()->with('success', 'Task successfully editted');
    }

    public function assignorUpdate(Request $request, $id)
    {
        $task = Task::find($id);
        $input = $request->all();

        /** Validations */
        $this->authorize('assignorUpdate', $task);
        if (!empty($task->reviewed_at)) {
            return redirect()->back()->with('warning', 'Reviewed tasks can no longer be changed');
        }
        if (!empty($input['reviewed'])) {
            $reviewed_at = Carbon::now();
            if (empty($task->finished_at)) {
                return redirect()->back()->with('warning', 'Finish the task first, and then review it.');
            }
        } else {
            $reviewed_at = null;
        }

        $task->update([
            'assignee_id' => $input['assignee_id'],
            'name' => $input['name'],
            'deadline_at' => $input['deadline_at'],
            'reviewed_at' => $reviewed_at,
            'status' => empty($input['reviewed']) ? $task->status : 'Reviewed',
            'auto_review' => empty($input['auto_review']) ? 0 : 1,
        ]);

        return redirect()->home()->with('success', 'Task successfully editted');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reviewed($id)
    {
        $task = Task::find($id);
        $task->reviewed_at = Carbon::now();
        $task->status = 'Completed';
        $task->save();

        return redirect()->back()->with('success', 'Task successfully marked as completed');

    }

    public function defer(Request $request)
    {
        $input = $request->all();
        $task = Task::find($input['task_id']);
        $deferHowLong = $input['deferHowLong'];
        $deferred_till = Carbon::now()->addHours($deferHowLong);
        $task->update([
            'deferred_till' => $deferred_till,
        ]);

        $taskShortName = substr($task->name, 0, 15);

        return redirect()->back()->with("success","Task Deferred until $deferred_till - $taskShortName");
    }

    public function test()
    {
        echo("This is from the backend<br>");
        echo("From TaskController<br>");
        return 'done';
    }

}
