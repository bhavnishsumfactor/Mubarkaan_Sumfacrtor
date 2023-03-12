@foreach($todos as $todo)
<li class="YK-todoItem @if($todo->todo_status == 1) {{'completed'}} @endif @if($todo->todo_status != 1 && !empty($todo->todo_reminder_at) && $todo->todo_timediff < 0) {{'is-overdue'}} @endif" attr-data-id="{{$todo->todo_id}}" attr-data-desc="{{$todo->todo_description}}">
    <div class="form-check">
        <label class="checkbox YK-completeTodo" title="{{$todo->todo_description}}">
            <input type="checkbox" @if($todo->todo_status == 1) checked @endif> {{$todo->todo_description}}
            @if(!empty($todo->todo_reminder_at))
            <small class="form-text text-muted">
                <i class="fas fa-bell"></i>
                {{ getConvertedAdminDateTime($todo->todo_reminder_at) }}
            </small>
        @endif <span disabled></span>
        </label>
        
    </div>
    <div class="todo-actions">
	<a href="javascript:;" title="" class="btn btn-light btn-sm btn-icon @if(empty($todo->todo_reminder_at)) {{'YK-reminderTodo'}} @endif" style="display:none;"><i class="fas fa-bell"></i> </a>
	<a href="javascript:;" title="" class="btn btn-light btn-sm btn-icon "><i class="flaticon-delete YK-removeTodo"></i></a>
	</div>
</li>
@endforeach