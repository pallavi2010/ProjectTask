<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Add a New Task</h1>
            <form action="{{ route('tasks.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="task-name" class="block text-sm font-medium text-gray-700">Task Name</label>
                        <input type="text" name="name" id="task-name" placeholder="Task Name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="project-id" class="block text-sm font-medium text-gray-700">Project</label>
                        <select name="project_id" id="project-id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border  bg-blue-600 py-2 px-4 text-sm font-medium mb-8">
                            Add Task
                        </button>
                    </div>
                </div>
            </form>
            <br><br>
            <h2 class="text-xl font-semibold text-gray-800 mb-4 mt-8 underline">Projects</h1>
            @foreach ($projects as $project)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $project->name }}</h2>
                    <form action="{{ route('tasks.reorder') }}" method="POST" class="task-reorder-form bg-gray-100 p-4 rounded-md shadow-inner">
                        @csrf
                        <table class="w-full table-auto bg-white rounded-lg text-center">
                            <thead>
                                <tr class="bg-gray-200 text-left text-gray-600 text-sm uppercase font-bold">
                                    <th class="px-4 py-2 text-left">Task Name</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="sortable task-list text-gray-700" data-project-id="{{ $project->id }}">
                                @foreach ($project->tasks as $task)
                                    <tr class="border-b border-gray-300" data-task-id="{{ $task->id }}">
                                        <td class="px-4 py-2 cursor-move">{{ $task->name }}</td>
                                        <td class="px-4 py-2 text-left flex  gap-x-2 justify-center">
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class=" mr-2 inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 ">
                                                    Delete
                                                </button>
                                            </form>

                                            <button type="button"
                                                class=" pl-2 inline-flex items-center justify-center px-3 py-1 border text-sm leading-4 font-medium rounded-md text-black  ml-2"
                                                onclick="openEditModal('{{ $task->id }}', '{{ $task->name }}', '{{ $project->id }}')">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($project->tasks->count()>0)
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                class=" text-black font-bold py-2 px-4 rounded  ">
                                Save Order
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Edit Task Modal -->
<div id="editTaskModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl p-6  max-w-lg">
            <h2 class="text-xl font-semibold mb-4">Edit Task</h2>
            <form id="editTaskForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="project_id" id="edit-project-id">
                <div class="mb-4">
                    <label for="edit-task-name" class="block text-sm font-medium text-gray-700">Task Name</label>
                    <input type="text" name="name" id="edit-task-name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="button"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
                        onclick="closeEditModal()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-green-600 text-black font-bold py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery and jQuery UI for Drag-and-Drop -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        // Make tasks sortable
        $(".sortable").sortable({
            cursor: "move",
            update: function (event, ui) {
                // Reordering happens dynamically here
            }
        });

        // Handle the form submission for reordering
        $(".task-reorder-form").on("submit", function (e) {
            e.preventDefault();

            const projectID = $(this).find(".task-list").data("project-id");
            const taskOrder = $(this).find(".sortable tr").map(function () {
                return $(this).data("task-id");
            }).get();

            $.post("{{ route('tasks.reorder') }}", {
                _token: "{{ csrf_token() }}",
                tasks: taskOrder,
                project_id: projectID
            }).done(function (response) {
                alert("Task order saved successfully!");
            }).fail(function () {
                alert("Failed to save task order.");
            });
        });
    });

    function openEditModal(taskId, taskName, projectId) {
        // Set the modal fields and form action dynamically
        document.getElementById('edit-task-name').value = taskName;
        document.getElementById('edit-project-id').value = projectId;
        document.getElementById('editTaskForm').action = `/tasks/${taskId}`;

        // Show the modal
        document.getElementById('editTaskModal').classList.remove('hidden');
    }

    function closeEditModal() {
        // Hide the modal
        document.getElementById('editTaskModal').classList.add('hidden');
    }
</script>

</x-app-layout>
